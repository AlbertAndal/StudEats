<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates the default admin account for StudEats.
     * The admin should change the password immediately after first login.
     */
    public function run(): void
    {
        $adminEmail = 'admin@studeats.com';
        $adminPassword = 'admin123';

        // Check if admin already exists
        $existingAdmin = User::where('email', $adminEmail)->first();

        if ($existingAdmin) {
            Log::info('Default admin account already exists', [
                'email' => $adminEmail,
                'admin_id' => $existingAdmin->id,
            ]);
            
            $this->command->warn('Default admin account already exists: ' . $adminEmail);
            return;
        }

        // Create the default admin account
        $admin = User::create([
            'name' => 'StudEats Admin',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
            'role' => 'super_admin',
            'is_active' => true,
            'timezone' => 'Asia/Manila',
        ]);

        Log::info('Default admin account created successfully', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'role' => $admin->role,
        ]);

        $this->command->info('✓ Default admin account created successfully');
        $this->command->line('');
        $this->command->line('===========================================');
        $this->command->line('   DEFAULT ADMIN CREDENTIALS');
        $this->command->line('===========================================');
        $this->command->line('Email:    ' . $adminEmail);
        $this->command->line('Password: ' . $adminPassword);
        $this->command->line('Role:     Super Admin');
        $this->command->line('===========================================');
        $this->command->line('');
        $this->command->warn('⚠️  IMPORTANT: Change this password immediately after first login!');
        $this->command->line('');
    }
}
