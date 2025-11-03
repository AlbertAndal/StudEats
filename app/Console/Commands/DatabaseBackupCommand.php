<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'db:backup 
                            {--connection=mysql : Database connection to backup}
                            {--path= : Custom backup path}
                            {--compress : Compress the backup file}';

    protected $description = 'Create a database backup';

    public function handle()
    {
        $connection = $this->option('connection');
        $config = config("database.connections.{$connection}");

        if (!$config) {
            $this->error("Database connection '{$connection}' not found");
            return 1;
        }

        $backupPath = $this->option('path') 
            ?? storage_path('backups/db_backup_' . date('Y-m-d_His') . '.sql');

        $backupDir = dirname($backupPath);
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $this->info("🗄️  Creating backup of '{$connection}' database...");

        if ($config['driver'] === 'mysql') {
            $this->backupMySQL($config, $backupPath);
        } elseif ($config['driver'] === 'pgsql') {
            $this->backupPostgreSQL($config, $backupPath);
        } else {
            $this->error("Unsupported database driver: {$config['driver']}");
            return 1;
        }

        if ($this->option('compress')) {
            $this->info('🗜️  Compressing backup...');
            $this->compressBackup($backupPath);
        }

        $this->info("✅ Backup created: {$backupPath}");
        
        return 0;
    }

    private function backupMySQL($config, $backupPath)
    {
        $command = sprintf(
            'mysqldump -h %s -P %s -u %s %s %s > %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            $config['password'] ? '-p' . escapeshellarg($config['password']) : '',
            escapeshellarg($config['database']),
            escapeshellarg($backupPath)
        );

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error('Backup failed!');
            $this->error(implode("\n", $output));
            return 1;
        }

        return 0;
    }

    private function backupPostgreSQL($config, $backupPath)
    {
        $command = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -p %s -U %s -d %s -F p -f %s',
            escapeshellarg($config['password']),
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($backupPath)
        );

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error('Backup failed!');
            $this->error(implode("\n", $output));
            return 1;
        }

        return 0;
    }

    private function compressBackup($backupPath)
    {
        $compressedPath = $backupPath . '.gz';
        
        exec("gzip -c {$backupPath} > {$compressedPath}", $output, $returnCode);

        if ($returnCode === 0 && file_exists($compressedPath)) {
            unlink($backupPath);
            $this->info("Compressed to: {$compressedPath}");
        }
    }
}
