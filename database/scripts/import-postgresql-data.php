<?php

/**
 * PostgreSQL Data Import Script
 * 
 * This script imports data exported from MySQL into PostgreSQL
 * with validation and integrity checks.
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostgreSQLDataImporter
{
    private $importPath;
    private $errors = [];
    private $stats = [];
    private $validate = false;

    public function __construct($importPath = null, $validate = false)
    {
        if ($importPath === null) {
            // Find the most recent export
            $exports = glob(storage_path('exports/mysql_to_pgsql_*'));
            rsort($exports);
            $importPath = $exports[0] ?? null;
        }

        if (!$importPath || !is_dir($importPath)) {
            throw new \Exception("Import path not found: {$importPath}");
        }

        $this->importPath = $importPath;
        $this->validate = $validate;
    }

    public function import()
    {
        $this->log("=== StudEats PostgreSQL Data Import Started ===\n");
        $this->log("Import Path: {$this->importPath}\n");
        $this->log("Validation Mode: " . ($this->validate ? 'ON' : 'OFF') . "\n\n");

        // Get all JSON files in dependency order
        $files = [
            'users.json',
            'meals.json',
            'ingredients.json',
            'nutritional_info.json',
            'recipes.json',
            'meal_plans.json',
            'admin_logs.json',
            'activity_logs.json',
            'email_verification_otps.json',
            'recipe_ingredients.json',
            'ingredient_price_history.json',
            'sessions.json',
            'cache.json',
            'cache_locks.json',
            'jobs.json',
            'job_batches.json',
            'failed_jobs.json',
            'password_reset_tokens.json',
        ];

        DB::connection('pgsql')->beginTransaction();

        try {
            foreach ($files as $file) {
                $filePath = "{$this->importPath}/{$file}";
                if (file_exists($filePath)) {
                    $this->importFile($filePath);
                }
            }

            if ($this->validate) {
                $this->log("\n🔍 Running validation checks...\n");
                $this->validateImport();
            }

            if (empty($this->errors)) {
                DB::connection('pgsql')->commit();
                $this->log("\n✅ Transaction committed successfully\n");
            } else {
                DB::connection('pgsql')->rollBack();
                $this->log("\n❌ Transaction rolled back due to errors\n");
            }

            $this->generateReport();

        } catch (\Exception $e) {
            DB::connection('pgsql')->rollBack();
            $this->errors[] = "Fatal error: " . $e->getMessage();
            $this->log("\n❌ Fatal Error: " . $e->getMessage() . "\n");
            $this->log("Stack trace:\n" . $e->getTraceAsString() . "\n");
        }

        return empty($this->errors);
    }

    private function importFile($filePath)
    {
        $data = json_decode(file_get_contents($filePath), true);
        $table = $data['table'];

        $this->log("\n📥 Importing table: {$table}");
        $this->log(" ({$data['total_records']} records)");

        if (empty($data['data'])) {
            $this->log(" - No data to import\n");
            $this->stats[$table] = ['total' => 0, 'imported' => 0];
            return;
        }

        try {
            // Disable constraints temporarily for faster import
            DB::connection('pgsql')->statement('SET CONSTRAINTS ALL DEFERRED');

            $imported = 0;
            $chunkSize = 100;

            foreach (array_chunk($data['data'], $chunkSize) as $chunk) {
                $inserted = DB::connection('pgsql')->table($table)->insert($chunk);
                if ($inserted) {
                    $imported += count($chunk);
                }
            }

            // Reset sequence for auto-increment columns
            if (Schema::connection('pgsql')->hasColumn($table, 'id')) {
                $maxId = DB::connection('pgsql')->table($table)->max('id') ?? 0;
                $sequence = "{$table}_id_seq";
                DB::connection('pgsql')->statement("SELECT setval('{$sequence}', {$maxId}, true)");
            }

            $this->stats[$table] = [
                'total' => $data['total_records'],
                'imported' => $imported
            ];

            $this->log(" ✅ Imported {$imported}/{$data['total_records']} records\n");

        } catch (\Exception $e) {
            $this->errors[] = "Error importing {$table}: " . $e->getMessage();
            $this->log(" ❌ Error: " . $e->getMessage() . "\n");
        }
    }

    private function validateImport()
    {
        $validations = [
            'users' => [
                'count' => true,
                'foreign_keys' => [],
                'unique_constraints' => ['email']
            ],
            'meals' => [
                'count' => true,
                'foreign_keys' => [],
            ],
            'meal_plans' => [
                'count' => true,
                'foreign_keys' => ['user_id' => 'users', 'meal_id' => 'meals'],
                'unique_constraints' => ['user_id', 'scheduled_date', 'meal_type']
            ],
            'nutritional_info' => [
                'count' => true,
                'foreign_keys' => ['meal_id' => 'meals'],
            ],
            'recipes' => [
                'count' => true,
                'foreign_keys' => ['meal_id' => 'meals'],
            ],
            'ingredients' => [
                'count' => true,
            ],
            'recipe_ingredients' => [
                'count' => true,
                'foreign_keys' => ['recipe_id' => 'recipes', 'ingredient_id' => 'ingredients'],
            ],
        ];

        foreach ($validations as $table => $checks) {
            if ($checks['count']) {
                $count = DB::connection('pgsql')->table($table)->count();
                $expected = $this->stats[$table]['total'] ?? 0;
                
                if ($count === $expected) {
                    $this->log("  ✅ {$table}: Count matches ({$count})\n");
                } else {
                    $this->errors[] = "{$table}: Count mismatch (expected {$expected}, got {$count})";
                    $this->log("  ❌ {$table}: Count mismatch\n");
                }
            }

            if (!empty($checks['foreign_keys'])) {
                foreach ($checks['foreign_keys'] as $fkColumn => $refTable) {
                    $orphans = DB::connection('pgsql')->table($table)
                        ->whereNotNull($fkColumn)
                        ->whereNotExists(function ($query) use ($refTable, $fkColumn) {
                            $query->select(DB::raw(1))
                                ->from($refTable)
                                ->whereColumn("{$refTable}.id", '=', $fkColumn);
                        })
                        ->count();

                    if ($orphans === 0) {
                        $this->log("  ✅ {$table}.{$fkColumn} → {$refTable}: No orphans\n");
                    } else {
                        $this->errors[] = "{$table}.{$fkColumn}: Found {$orphans} orphan records";
                        $this->log("  ❌ {$table}.{$fkColumn}: {$orphans} orphan records\n");
                    }
                }
            }
        }
    }

    private function generateReport()
    {
        $report = "\n=== Import Report ===\n\n";
        
        $totalRecords = 0;
        $totalImported = 0;

        foreach ($this->stats as $table => $stat) {
            $totalRecords += $stat['total'];
            $totalImported += $stat['imported'];
            $status = $stat['total'] === $stat['imported'] ? '✅' : '⚠️ ';
            $report .= sprintf(
                "%s %-30s %6d / %6d records\n",
                $status,
                $table,
                $stat['imported'],
                $stat['total']
            );
        }

        $report .= "\n" . str_repeat('-', 60) . "\n";
        $report .= sprintf("Total: %d / %d records imported\n", $totalImported, $totalRecords);

        if (!empty($this->errors)) {
            $report .= "\n❌ Errors:\n";
            foreach ($this->errors as $error) {
                $report .= "  - {$error}\n";
            }
            $report .= "\n⚠️  Import was rolled back due to errors.\n";
        } else {
            $report .= "\n✅ Import completed successfully!\n";
        }

        $this->log($report);

        // Save report to file
        file_put_contents(
            "{$this->importPath}/import_report.txt",
            $report
        );
    }

    private function log($message)
    {
        echo $message;
    }
}

// Parse command line arguments
$options = getopt('', ['validate', 'path:']);
$validate = isset($options['validate']);
$path = $options['path'] ?? null;

// Run the import
try {
    $importer = new PostgreSQLDataImporter($path, $validate);
    $success = $importer->import();
    exit($success ? 0 : 1);
} catch (\Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    exit(1);
}
