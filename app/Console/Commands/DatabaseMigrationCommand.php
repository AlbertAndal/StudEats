<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseMigrationCommand extends Command
{
    protected $signature = 'db:migrate-to-postgresql 
                            {action : Action to perform: export, import, validate, or full}
                            {--validate : Run validation checks}
                            {--dry-run : Simulate without making changes}
                            {--path= : Custom import/export path}';

    protected $description = 'Migrate data from MySQL to PostgreSQL with validation';

    public function handle()
    {
        $action = $this->argument('action');

        match ($action) {
            'export' => $this->exportData(),
            'import' => $this->importData(),
            'validate' => $this->validateData(),
            'full' => $this->fullMigration(),
            default => $this->error("Unknown action: {$action}"),
        };
    }

    private function exportData()
    {
        $this->info('🚀 Starting MySQL data export...');
        
        $scriptPath = database_path('scripts/export-mysql-data.php');
        
        if (!file_exists($scriptPath)) {
            $this->error('Export script not found!');
            return 1;
        }

        $output = [];
        $returnCode = 0;
        
        exec("php {$scriptPath}", $output, $returnCode);
        
        foreach ($output as $line) {
            $this->line($line);
        }

        return $returnCode === 0 ? 0 : 1;
    }

    private function importData()
    {
        $this->info('🚀 Starting PostgreSQL data import...');
        
        $scriptPath = database_path('scripts/import-postgresql-data.php');
        
        if (!file_exists($scriptPath)) {
            $this->error('Import script not found!');
            return 1;
        }

        $command = "php {$scriptPath}";
        
        if ($this->option('validate')) {
            $command .= ' --validate';
        }
        
        if ($path = $this->option('path')) {
            $command .= " --path={$path}";
        }

        $output = [];
        $returnCode = 0;
        
        exec($command, $output, $returnCode);
        
        foreach ($output as $line) {
            $this->line($line);
        }

        return $returnCode === 0 ? 0 : 1;
    }

    private function validateData()
    {
        $this->info('🔍 Validating database migration...');

        $checks = [
            'Connection Tests' => $this->validateConnections(),
            'Row Counts' => $this->validateRowCounts(),
            'Foreign Keys' => $this->validateForeignKeys(),
            'Data Integrity' => $this->validateDataIntegrity(),
        ];

        $allPassed = true;

        foreach ($checks as $checkName => $result) {
            if ($result['passed']) {
                $this->info("✅ {$checkName}: Passed");
            } else {
                $this->error("❌ {$checkName}: Failed");
                foreach ($result['errors'] as $error) {
                    $this->error("   - {$error}");
                }
                $allPassed = false;
            }
        }

        return $allPassed ? 0 : 1;
    }

    private function fullMigration()
    {
        $this->info('🚀 Starting full database migration...');

        if (!$this->option('dry-run')) {
            if (!$this->confirm('This will migrate all data from MySQL to PostgreSQL. Continue?')) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        // Step 1: Backup
        $this->info("\n📦 Step 1/5: Creating backup...");
        if (!$this->option('dry-run')) {
            $this->call('db:backup');
        }

        // Step 2: Export
        $this->info("\n📤 Step 2/5: Exporting MySQL data...");
        if (!$this->option('dry-run')) {
            if ($this->exportData() !== 0) {
                $this->error('Export failed!');
                return 1;
            }
        }

        // Step 3: Prepare PostgreSQL
        $this->info("\n🗄️  Step 3/5: Preparing PostgreSQL database...");
        if (!$this->option('dry-run')) {
            $this->call('migrate:fresh', ['--database' => 'pgsql', '--force' => true]);
        }

        // Step 4: Import
        $this->info("\n📥 Step 4/5: Importing to PostgreSQL...");
        if (!$this->option('dry-run')) {
            if ($this->importData() !== 0) {
                $this->error('Import failed!');
                return 1;
            }
        }

        // Step 5: Validate
        $this->info("\n✅ Step 5/5: Validating migration...");
        if (!$this->option('dry-run')) {
            if ($this->validateData() !== 0) {
                $this->error('Validation failed!');
                return 1;
            }
        }

        $this->info("\n🎉 Migration completed successfully!");
        
        return 0;
    }

    private function validateConnections()
    {
        $errors = [];

        try {
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $errors[] = "MySQL connection failed: " . $e->getMessage();
        }

        try {
            DB::connection('pgsql')->getPdo();
        } catch (\Exception $e) {
            $errors[] = "PostgreSQL connection failed: " . $e->getMessage();
        }

        return [
            'passed' => empty($errors),
            'errors' => $errors,
        ];
    }

    private function validateRowCounts()
    {
        $errors = [];
        $tables = ['users', 'meals', 'ingredients', 'recipes', 'meal_plans'];

        foreach ($tables as $table) {
            try {
                $mysqlCount = DB::connection('mysql')->table($table)->count();
                $pgsqlCount = DB::connection('pgsql')->table($table)->count();

                if ($mysqlCount !== $pgsqlCount) {
                    $errors[] = "{$table}: MySQL={$mysqlCount}, PostgreSQL={$pgsqlCount}";
                }
            } catch (\Exception $e) {
                $errors[] = "{$table}: Error - " . $e->getMessage();
            }
        }

        return [
            'passed' => empty($errors),
            'errors' => $errors,
        ];
    }

    private function validateForeignKeys()
    {
        $errors = [];

        $checks = [
            'meal_plans' => ['user_id' => 'users', 'meal_id' => 'meals'],
            'recipes' => ['meal_id' => 'meals'],
            'recipe_ingredients' => ['recipe_id' => 'recipes', 'ingredient_id' => 'ingredients'],
        ];

        foreach ($checks as $table => $foreignKeys) {
            foreach ($foreignKeys as $fkColumn => $refTable) {
                try {
                    $orphans = DB::connection('pgsql')->table($table)
                        ->whereNotNull($fkColumn)
                        ->whereNotExists(function ($query) use ($refTable, $fkColumn, $table) {
                            $query->select(DB::raw(1))
                                ->from($refTable)
                                ->whereColumn("{$refTable}.id", '=', "{$table}.{$fkColumn}");
                        })
                        ->count();

                    if ($orphans > 0) {
                        $errors[] = "{$table}.{$fkColumn}: {$orphans} orphan records";
                    }
                } catch (\Exception $e) {
                    $errors[] = "{$table}.{$fkColumn}: Error - " . $e->getMessage();
                }
            }
        }

        return [
            'passed' => empty($errors),
            'errors' => $errors,
        ];
    }

    private function validateDataIntegrity()
    {
        $errors = [];

        // Check users table
        try {
            $usersWithoutEmail = DB::connection('pgsql')
                ->table('users')
                ->whereNull('email')
                ->count();

            if ($usersWithoutEmail > 0) {
                $errors[] = "Found {$usersWithoutEmail} users without email";
            }
        } catch (\Exception $e) {
            $errors[] = "Users check failed: " . $e->getMessage();
        }

        // Check JSON columns
        try {
            $invalidJson = DB::connection('pgsql')
                ->table('users')
                ->whereNotNull('dietary_preferences')
                ->whereRaw("NOT (dietary_preferences::text ~ '^[\\[\\{]')")
                ->count();

            if ($invalidJson > 0) {
                $errors[] = "Found {$invalidJson} users with invalid dietary_preferences JSON";
            }
        } catch (\Exception $e) {
            // JSON validation might not be available in all PostgreSQL versions
        }

        return [
            'passed' => empty($errors),
            'errors' => $errors,
        ];
    }
}
