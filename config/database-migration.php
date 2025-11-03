<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Migration Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for migrating from MySQL to PostgreSQL
    |
    */

    'source' => [
        'connection' => env('DB_MIGRATION_SOURCE', 'mysql'),
        'backup_before_export' => env('DB_MIGRATION_BACKUP_SOURCE', true),
    ],

    'target' => [
        'connection' => env('DB_MIGRATION_TARGET', 'pgsql'),
        'drop_before_import' => env('DB_MIGRATION_DROP_TARGET', false),
    ],

    'export' => [
        'path' => storage_path('exports'),
        'format' => 'json', // json or csv
        'chunk_size' => 100,
        'compress' => false,
    ],

    'import' => [
        'chunk_size' => 100,
        'validate' => true,
        'stop_on_error' => true,
        'defer_constraints' => true,
    ],

    'validation' => [
        'check_row_counts' => true,
        'check_foreign_keys' => true,
        'check_data_integrity' => true,
        'check_json_validity' => true,
    ],

    'performance' => [
        'disable_indexes_during_import' => false,
        'use_transactions' => true,
        'transaction_size' => 1000,
    ],

    'tables' => [
        // Tables to migrate in order (respecting dependencies)
        'order' => [
            'users',
            'meals',
            'ingredients',
            'nutritional_info',
            'recipes',
            'meal_plans',
            'admin_logs',
            'activity_logs',
            'email_verification_otps',
            'recipe_ingredients',
            'ingredient_price_history',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'password_reset_tokens',
        ],

        // Tables to skip during migration
        'skip' => [
            'migrations',
            // Add phpMyAdmin tables if needed
            'pma__bookmark',
            'pma__central_columns',
            // ... other pma tables
        ],
    ],

    'type_mapping' => [
        // MySQL to PostgreSQL type mapping
        'tinyint' => 'boolean',
        'datetime' => 'timestamp',
        'longtext' => 'text',
        'mediumtext' => 'text',
        'enum' => 'varchar',
    ],

];
