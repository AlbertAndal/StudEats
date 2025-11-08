<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:create {--reset : Reset existing admin password}';

    /**
     * The console command description.
     */
    protected $description = 'Create or reset the default admin account for StudEats';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $adminEmail = 'admin@studeats.com';
        $adminPassword = 'admin123';

        $this->info('🔐 StudEats Admin Account Manager');
        $this->line('=====================================');

        // Check if admin already exists
        $existingAdmin = User::where('email', $adminEmail)->first();

        if ($existingAdmin && !$this->option('reset')) {
            $this->warn('❌ Admin account already exists!');
            $this->line('');
            $this->line('Current admin details:');
            $this->table(
                ['Field', 'Value'],
                [
                    ['ID', $existingAdmin->id],
                    ['Name', $existingAdmin->name],
                    ['Email', $existingAdmin->email],
                    ['Role', $existingAdmin->role],
                    ['Status', $existingAdmin->is_active ? 'Active' : 'Suspended'],
                    ['Email Verified', $existingAdmin->email_verified_at ? 'Yes' : 'No'],
                    ['Created', $existingAdmin->created_at->format('Y-m-d H:i:s')],
                ]
            );
            $this->line('');
            $this->warn('Use --reset flag to reset the password: php artisan admin:create --reset');
            return self::FAILURE;
        }

        if ($existingAdmin) {
            // Reset existing admin
            $existingAdmin->update([
                'name' => 'StudEats Admin',
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'role' => 'super_admin',
                'is_active' => true,
                'timezone' => 'Asia/Manila',
            ]);

            Log::info('Admin password reset via artisan command', [
                'admin_id' => $existingAdmin->id,
                'email' => $adminEmail,
                'reset_by' => 'admin:create --reset',
            ]);

            $this->info('✅ Admin password has been reset!');
            $this->displayCredentials($adminEmail, $adminPassword, $existingAdmin->id);
            
        } else {
            // Create new admin
            $admin = User::create([
                'name' => 'StudEats Admin',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'role' => 'super_admin',
                'is_active' => true,
                'timezone' => 'Asia/Manila',
            ]);

            Log::info('Admin account created via artisan command', [
                'admin_id' => $admin->id,
                'email' => $adminEmail,
                'created_by' => 'admin:create',
            ]);

            $this->info('✅ Admin account created successfully!');
            $this->displayCredentials($adminEmail, $adminPassword, $admin->id);
        }

        $this->line('');
        $this->comment('🌐 Login at: https://studeats.laravel.cloud/admin/login');
        $this->line('');
        $this->error('⚠️  SECURITY: Change this password immediately after first login!');
        
        return self::SUCCESS;
    }

    /**
     * Display admin credentials in a formatted table.
     */
    private function displayCredentials(string $email, string $password, int $adminId): void
    {
        $this->line('');
        $this->info('📋 ADMIN LOGIN CREDENTIALS');
        $this->table(
            ['Field', 'Value'],
            [
                ['Admin ID', '#' . $adminId],
                ['Login URL', 'https://studeats.laravel.cloud/admin/login'],
                ['Email', $email],
                ['Password', $password],
                ['Role', 'Super Admin'],
                ['Status', 'Active & Email Verified'],
            ]
        );
    }
}