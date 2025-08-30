<?php<?php



namespace Database\Seeders;namespace Database\Seeders;



use App\Models\User;use App\Models\User;

use Illuminate\Database\Seeder;use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Support\Facades\Hash;use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder

{class AdminUserSeeder extends Seeder

    public function run(): void{

    {    /**

        // Create an admin user     * Run the database seeder.

        User::create([     */

            'name' => 'StudEats Admin',    public function run(): void

            'email' => 'admin@studeats.com',    {

            'password' => Hash::make('admin123'),        // Create an admin user

            'role' => 'admin',        User::create([

            'is_active' => true,            'name' => 'StudEats Admin',

            'dietary_preferences' => ['none'],            'email' => 'admin@studeats.com',

            'daily_budget' => 500.00,            'password' => Hash::make('admin123'),

            'age' => '25-30',            'role' => 'admin',

            'email_verified_at' => now(),            'is_active' => true,

        ]);            'dietary_preferences' => ['none'],

            'daily_budget' => 500.00,

        echo "Admin user created: admin@studeats.com / admin123\n";            'age' => '25-30',

    }            'email_verified_at' => now(),

}        ]);

        // Create a super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@studeats.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'is_active' => true,
            'dietary_preferences' => ['none'],
            'daily_budget' => 1000.00,
            'age' => '30-35',
            'email_verified_at' => now(),
        ]);

        echo "Admin users created:\n";
        echo "Admin: admin@studeats.com / admin123\n";
        echo "Super Admin: superadmin@studeats.com / superadmin123\n";
    }
}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
