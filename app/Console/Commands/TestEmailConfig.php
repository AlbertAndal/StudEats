<?php

namespace App\Console\Commands;

use App\Services\OtpService;
use Illuminate\Console\Command;

class TestEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-config {email? : Email address to test with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration and OTP sending';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Testing Email Configuration ===');

        // Display current environment
        $this->table(
            ['Setting', 'Value'],
            [
                ['MAIL_MAILER', env('MAIL_MAILER', 'not set')],
                ['MAIL_HOST', env('MAIL_HOST', 'not set')],
                ['MAIL_PORT', env('MAIL_PORT', 'not set')],
                ['MAIL_USERNAME', env('MAIL_USERNAME', 'not set')],
                ['MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'not set')],
            ]
        );

        // Display config values
        $this->info('=== Config Values ===');
        $mailConfig = config('mail');
        $this->table(
            ['Setting', 'Value'],
            [
                ['default', $mailConfig['default']],
                ['smtp.host', $mailConfig['mailers']['smtp']['host']],
                ['smtp.port', $mailConfig['mailers']['smtp']['port']],
                ['smtp.username', $mailConfig['mailers']['smtp']['username'] ?? 'not set'],
                ['from.address', $mailConfig['from']['address']],
            ]
        );

        // Test OTP generation and sending
        $email = $this->argument('email') ?: 'test@example.com';

        $this->info("=== Testing OTP Generation for {$email} ===");

        try {
            $otpService = app(OtpService::class);

            // Generate OTP
            $otp = $otpService->generateOtp($email);
            $this->info("✓ OTP generated: {$otp->otp_code}");

            // Test email sending
            $this->info('=== Testing Email Sending ===');
            $otpService->sendOtpEmail($email, $otp->otp_code);
            $this->info('✓ Email sent successfully');

            // Check if using log driver
            if (config('mail.default') === 'log') {
                $this->warn('WARNING: Using log mail driver. Check storage/logs/laravel.log for email content.');
            }

        } catch (\Exception $e) {
            $this->error('✗ Error: '.$e->getMessage());
            $this->error('Trace: '.$e->getTraceAsString());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
