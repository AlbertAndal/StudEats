<?php

namespace App\Console\Commands;

use App\Services\OtpService;
use Illuminate\Console\Command;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup
                            {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired email verification OTPs from the database';

    /**
     * Execute the console command.
     */
    public function handle(OtpService $otpService): int
    {
        if (! $this->option('force')) {
            if (! $this->confirm('Are you sure you want to clean up expired OTPs?')) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        }

        $this->info('Cleaning up expired OTPs...');

        $deletedCount = $otpService->cleanupExpiredOtps();

        if ($deletedCount > 0) {
            $this->info("Successfully cleaned up {$deletedCount} expired OTP(s).");
        } else {
            $this->info('No expired OTPs found to clean up.');
        }

        return self::SUCCESS;
    }
}
