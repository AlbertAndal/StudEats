<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListAdmins extends Command
{
    protected $signature = 'admin:list';
    protected $description = 'List all admin accounts';

    public function handle()
    {
        $this->info('=== ADMIN ACCOUNTS ===');
        $this->newLine();

        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();

        if ($admins->isEmpty()) {
            $this->warn('No admin accounts found.');
            return 0;
        }

        foreach ($admins as $admin) {
            $this->line('✓ ' . ucfirst(str_replace('_', ' ', $admin->role)));
            $this->line('  Email: ' . $admin->email);
            $this->line('  Name: ' . $admin->name);
            $this->line('  Active: ' . ($admin->is_active ? 'Yes' : 'No'));
            $this->line('  Verified: ' . ($admin->email_verified_at ? 'Yes' : 'No'));
            $this->newLine();
        }

        $this->info('Total admin accounts: ' . $admins->count());
        
        return 0;
    }
}
