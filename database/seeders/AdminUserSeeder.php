<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::updateOrCreate(
            ['email' => 'admin@studeats.com'],
            [
                'name' => 'StudEats Admin',
                'email' => 'admin@studeats.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'dietary_preferences' => ['none'],
                'daily_budget' => 500.00,
                'age' => '25-30',
                'email_verified_at' => now(),
            ]
        );

        // Create a super admin user
        User::updateOrCreate(
            ['email' => 'superadmin@studeats.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@studeats.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'is_active' => true,
                'dietary_preferences' => ['none'],
                'daily_budget' => 1000.00,
                'age' => '30-35',
                'email_verified_at' => now(),
            ]
        );

        echo "Admin users created/updated:\n";
        echo "Admin: admin@studeats.com / admin123\n";
        echo "Super Admin: superadmin@studeats.com / superadmin123\n";
    }
}
