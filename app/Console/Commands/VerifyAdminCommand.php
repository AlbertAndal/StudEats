<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VerifyAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:verify';

    /**
     * The console command description.
     */
    protected $description = 'Verify that the admin account is properly configured and can login';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 StudEats Admin Account Verification');
        $this->line('======================================');

        $adminEmail = 'admin@studeats.com';
        $adminPassword = 'admin123';

        // Test 1: Check if admin exists
        $this->line('1. Checking if admin account exists...');
        $admin = User::where('email', $adminEmail)->first();

        if (!$admin) {
            $this->error('❌ Admin account not found!');
            $this->line('   Run: php artisan admin:create');
            return self::FAILURE;
        }

        $this->info('✅ Admin account found');

        // Test 2: Verify password
        $this->line('2. Verifying password...');
        if (!Hash::check($adminPassword, $admin->password)) {
            $this->error('❌ Password does not match!');
            $this->line('   Run: php artisan admin:create --reset');
            return self::FAILURE;
        }

        $this->info('✅ Password verified');

        // Test 3: Check role and permissions
        $this->line('3. Checking admin permissions...');
        if (!$admin->isAdmin()) {
            $this->error('❌ User does not have admin role!');
            $this->line("   Current role: {$admin->role}");
            return self::FAILURE;
        }

        $this->info('✅ Admin permissions confirmed');

        // Test 4: Check account status
        $this->line('4. Checking account status...');
        if (!$admin->is_active) {
            $this->error('❌ Admin account is suspended!');
            return self::FAILURE;
        }

        $this->info('✅ Account is active');

        // Test 5: Check email verification
        $this->line('5. Checking email verification...');
        if (!$admin->hasVerifiedEmail()) {
            $this->error('❌ Email not verified!');
            return self::FAILURE;
        }

        $this->info('✅ Email verified');

        // Test 6: Test authentication flow
        $this->line('6. Testing authentication flow...');
        try {
            $credentials = ['email' => $adminEmail, 'password' => $adminPassword];
            
            if (!Auth::attempt($credentials)) {
                $this->error('❌ Authentication failed!');
                return self::FAILURE;
            }

            // Clear the auth session
            Auth::logout();
            $this->info('✅ Authentication successful');

        } catch (\Exception $e) {
            $this->error('❌ Authentication error: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Display final results
        $this->line('');
        $this->info('🎉 ALL TESTS PASSED!');
        $this->line('');

        // Display admin details
        $this->table(
            ['Field', 'Value'],
            [
                ['Admin ID', '#' . $admin->id],
                ['Name', $admin->name],
                ['Email', $admin->email],
                ['Role', $admin->role],
                ['Status', $admin->is_active ? 'Active' : 'Suspended'],
                ['Email Verified', $admin->email_verified_at ? 'Yes' : 'No'],
                ['Last Updated', $admin->updated_at->format('Y-m-d H:i:s')],
            ]
        );

        $this->line('');
        $this->comment('🌐 Login URL: https://studeats.laravel.cloud/admin/login');
        $this->comment('📧 Email: ' . $adminEmail);
        $this->comment('🔑 Password: ' . $adminPassword);
        $this->line('');
        $this->warn('⚠️  Remember to change the password after first login!');

        return self::SUCCESS;
    }
}