#!/usr/bin/env php
<?php

/**
 * Database Migration Helper Script
 * 
 * Quick commands for database migration tasks
 */

$command = $argv[1] ?? 'help';

switch ($command) {
    case 'test-connection':
        echo "Testing PostgreSQL connection...\n";
        exec('php artisan tinker --execute="DB::connection(\'pgsql\')->select(\'SELECT version()\');"');
        break;

    case 'export':
        echo "Exporting MySQL data...\n";
        exec('php database/scripts/export-mysql-data.php');
        break;

    case 'import':
        echo "Importing to PostgreSQL...\n";
        $validate = isset($argv[2]) && $argv[2] === '--validate' ? '--validate' : '';
        exec("php database/scripts/import-postgresql-data.php {$validate}");
        break;

    case 'backup':
        echo "Creating database backup...\n";
        exec('php artisan db:backup');
        break;

    case 'validate':
        echo "Validating migration...\n";
        exec('php artisan db:migrate-to-postgresql validate');
        break;

    case 'test':
        echo "Running migration tests...\n";
        exec('php artisan test --filter=DatabaseMigrationTest');
        break;

    case 'performance-test':
        echo "Running performance tests...\n";
        exec('php artisan test --filter=DatabasePerformanceTest');
        break;

    case 'full':
        echo "Running full migration...\n";
        exec('php artisan db:migrate-to-postgresql full --validate');
        break;

    case 'switch-to-mysql':
        echo "Switching to MySQL...\n";
        echo "Update your .env file:\n";
        echo "DB_CONNECTION=mysql\n";
        echo "Then run: php artisan config:clear && php artisan config:cache\n";
        break;

    case 'switch-to-pgsql':
        echo "Switching to PostgreSQL...\n";
        echo "Update your .env file:\n";
        echo "DB_CONNECTION=pgsql\n";
        echo "Then run: php artisan config:clear && php artisan config:cache\n";
        break;

    case 'health':
        echo "Checking database health...\n";
        exec('php artisan tinker --execute="
            try {
                DB::connection()->select(\'SELECT 1\');
                echo \"✅ Database connection: OK\\n\";
                echo \"📊 Connection: \" . config(\'database.default\') . \"\\n\";
                echo \"🗄️  Database: \" . config(\'database.connections.\' . config(\'database.default\') . \'.database\') . \"\\n\";
            } catch (Exception \$e) {
                echo \"❌ Database connection: FAILED\\n\";
                echo \"Error: \" . \$e->getMessage() . \"\\n\";
            }
        "');
        break;

    case 'help':
    default:
        echo <<<HELP

StudEats Database Migration Helper
===================================

Available Commands:

  test-connection       Test PostgreSQL connection
  export               Export data from MySQL
  import [--validate]  Import data to PostgreSQL (with optional validation)
  backup               Create database backup
  validate             Validate migration integrity
  test                 Run migration tests
  performance-test     Run performance benchmarks
  full                 Run complete migration with validation
  switch-to-mysql      Instructions to switch to MySQL
  switch-to-pgsql      Instructions to switch to PostgreSQL
  health               Check database health and connection
  help                 Show this help message

Examples:

  php migrate.php test-connection
  php migrate.php export
  php migrate.php import --validate
  php migrate.php full
  php migrate.php health

For more information, see:
  - docs/database-migration-guide.md
  - docs/database-migration-complete.md

HELP;
        break;
}

echo "\nDone!\n";
