<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {email=admin@studeats.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the admin password to the default (admin123)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $defaultPassword = 'admin123';

        $this->info("Looking for admin account: {$email}");

        $admin = User::where('email', $email)->first();

        if (!$admin) {
            $this->error("Admin account not found: {$email}");
            
            if ($this->confirm('Would you like to create the admin account?', true)) {
                $admin = User::create([
                    'name' => 'StudEats Admin',
                    'email' => $email,
                    'password' => Hash::make($defaultPassword),
                    'email_verified_at' => now(),
                    'role' => 'super_admin',
                    'is_active' => true,
                    'timezone' => 'Asia/Manila',
                ]);

                Log::info('Admin account created via command', [
                    'admin_id' => $admin->id,
                    'email' => $admin->email,
                ]);

                $this->newLine();
                $this->info('✅ Admin account created successfully!');
                $this->displayCredentials($email, $defaultPassword);
                
                return Command::SUCCESS;
            }
            
            return Command::FAILURE;
        }

        // Reset password
        $admin->password = Hash::make($defaultPassword);
        $admin->email_verified_at = now();
        $admin->is_active = true;
        $admin->role = 'super_admin';
        $admin->save();

        Log::info('Admin password reset via command', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
        ]);

        $this->newLine();
        $this->info('✅ Admin password reset successfully!');
        $this->displayCredentials($email, $defaultPassword);

        return Command::SUCCESS;
    }

    /**
     * Display admin credentials in a formatted box.
     */
    private function displayCredentials(string $email, string $password): void
    {
        $this->newLine();
        $this->line('===========================================');
        $this->line('   ADMIN CREDENTIALS');
        $this->line('===========================================');
        $this->line('Email:    ' . $email);
        $this->line('Password: ' . $password);
        $this->line('===========================================');
        $this->newLine();
        $this->warn('⚠️  IMPORTANT: Change this password immediately after login!');
        $this->newLine();
    }
}
