<?php

/**
 * MySQL Data Export Script for PostgreSQL Migration
 * 
 * This script exports all StudEats data from MySQL in a PostgreSQL-compatible format
 * with proper type conversions and validation checks.
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MySQLDataExporter
{
    private $exportPath;
    private $errors = [];
    private $stats = [];

    public function __construct()
    {
        $this->exportPath = storage_path('exports/mysql_to_pgsql_' . date('Y-m-d_His'));
        if (!is_dir($this->exportPath)) {
            mkdir($this->exportPath, 0755, true);
        }
    }

    public function export()
    {
        $this->log("=== StudEats MySQL Data Export Started ===\n");
        $this->log("Export Path: {$this->exportPath}\n");

        // Tables to export in dependency order
        $tables = [
            // Core tables without dependencies
            'users',
            'meals',
            'ingredients',
            
            // Tables with foreign keys - Level 1
            'nutritional_info',
            'recipes',
            'meal_plans',
            'admin_logs',
            'activity_logs',
            'email_verification_otps',
            
            // Tables with foreign keys - Level 2
            'recipe_ingredients',
            'ingredient_price_history',
            
            // System tables
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'password_reset_tokens',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->exportTable($table);
            } else {
                $this->log("⚠️  Table '{$table}' does not exist, skipping...\n");
            }
        }

        $this->generateReport();
        return $this->errors === [];
    }

    private function exportTable($table)
    {
        $this->log("\n📊 Exporting table: {$table}");

        try {
            $count = DB::table($table)->count();
            $this->log(" ({$count} records)");
            
            if ($count === 0) {
                $this->log(" - Empty table, skipping\n");
                $this->stats[$table] = ['total' => 0, 'exported' => 0];
                return;
            }

            $columns = $this->getTableColumns($table);
            $data = DB::table($table)->get();

            $exported = 0;
            $file = fopen("{$this->exportPath}/{$table}.json", 'w');
            
            $exportData = [
                'table' => $table,
                'exported_at' => now()->toIso8601String(),
                'total_records' => $count,
                'columns' => $columns,
                'data' => []
            ];

            foreach ($data as $row) {
                $transformedRow = $this->transformRow((array)$row, $table, $columns);
                if ($transformedRow !== null) {
                    $exportData['data'][] = $transformedRow;
                    $exported++;
                }
            }

            fwrite($file, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fclose($file);

            $this->stats[$table] = [
                'total' => $count,
                'exported' => $exported
            ];

            $this->log(" ✅ Exported {$exported}/{$count} records\n");

        } catch (\Exception $e) {
            $this->errors[] = "Error exporting {$table}: " . $e->getMessage();
            $this->log(" ❌ Error: " . $e->getMessage() . "\n");
        }
    }

    private function getTableColumns($table)
    {
        $columns = Schema::getColumns($table);
        return array_map(function($col) {
            return [
                'name' => $col['name'],
                'type' => $col['type_name'],
                'nullable' => $col['nullable'],
            ];
        }, $columns);
    }

    private function transformRow($row, $table, $columns)
    {
        $transformed = [];

        foreach ($row as $key => $value) {
            // Find column type
            $columnInfo = collect($columns)->firstWhere('name', $key);
            $type = $columnInfo['type'] ?? 'unknown';

            // Transform based on type
            if ($value === null) {
                $transformed[$key] = null;
            } elseif (in_array($type, ['json', 'longtext']) && $this->isJson($value)) {
                // Validate and preserve JSON
                $transformed[$key] = json_decode($value, true);
            } elseif ($type === 'tinyint' && in_array($value, [0, 1, '0', '1'])) {
                // Convert tinyint to boolean for PostgreSQL
                $transformed[$key] = (bool)$value;
            } elseif (in_array($type, ['timestamp', 'datetime'])) {
                // Ensure proper timestamp format
                $transformed[$key] = $value ? date('Y-m-d H:i:s', strtotime($value)) : null;
            } elseif ($type === 'date') {
                $transformed[$key] = $value ? date('Y-m-d', strtotime($value)) : null;
            } elseif (in_array($type, ['decimal', 'float', 'double'])) {
                $transformed[$key] = $value !== null ? (float)$value : null;
            } elseif (in_array($type, ['int', 'bigint', 'mediumint', 'smallint'])) {
                $transformed[$key] = $value !== null ? (int)$value : null;
            } else {
                $transformed[$key] = $value;
            }
        }

        return $transformed;
    }

    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function generateReport()
    {
        $report = "\n=== Export Report ===\n\n";
        
        $totalRecords = 0;
        $totalExported = 0;

        foreach ($this->stats as $table => $stat) {
            $totalRecords += $stat['total'];
            $totalExported += $stat['exported'];
            $status = $stat['total'] === $stat['exported'] ? '✅' : '⚠️ ';
            $report .= sprintf(
                "%s %-30s %6d / %6d records\n",
                $status,
                $table,
                $stat['exported'],
                $stat['total']
            );
        }

        $report .= "\n" . str_repeat('-', 60) . "\n";
        $report .= sprintf("Total: %d / %d records exported\n", $totalExported, $totalRecords);

        if (!empty($this->errors)) {
            $report .= "\n❌ Errors:\n";
            foreach ($this->errors as $error) {
                $report .= "  - {$error}\n";
            }
        } else {
            $report .= "\n✅ Export completed successfully!\n";
        }

        $report .= "\nExport location: {$this->exportPath}\n";

        $this->log($report);

        // Save report to file
        file_put_contents(
            "{$this->exportPath}/export_report.txt",
            $report
        );
    }

    private function log($message)
    {
        echo $message;
    }
}

// Run the export
$exporter = new MySQLDataExporter();
$success = $exporter->export();

exit($success ? 0 : 1);
