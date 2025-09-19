<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ConfigureSmtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:smtp {--test : Test the configuration after setup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive SMTP configuration setup with testing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔧 StudEats SMTP Configuration Setup');
        $this->newLine();

        // Check current configuration
        $this->info('📋 Current Configuration:');
        $this->displayCurrentConfig();
        $this->newLine();

        // Ask user for configuration method
        $method = $this->choice(
            'How would you like to configure SMTP?',
            [
                'mailtrap' => 'Mailtrap (Recommended for development)',
                'gmail' => 'Gmail SMTP',
                'mailhog' => 'MailHog (Local testing)',
                'custom' => 'Custom SMTP server',
                'log' => 'Log driver (testing only)',
            ],
            'mailtrap'
        );

        switch ($method) {
            case 'mailtrap':
                return $this->configureMailtrap();
            case 'gmail':
                return $this->configureGmail();
            case 'mailhog':
                return $this->configureMailhog();
            case 'custom':
                return $this->configureCustom();
            case 'log':
                return $this->configureLog();
        }

        return self::SUCCESS;
    }

    /**
     * Display current mail configuration.
     */
    protected function displayCurrentConfig(): void
    {
        $this->table(
            ['Setting', 'Current Value'],
            [
                ['MAIL_MAILER', config('mail.default')],
                ['MAIL_HOST', config('mail.mailers.smtp.host')],
                ['MAIL_PORT', config('mail.mailers.smtp.port')],
                ['MAIL_USERNAME', config('mail.mailers.smtp.username') ?: 'Not set'],
                ['MAIL_FROM_ADDRESS', config('mail.from.address')],
                ['MAIL_FROM_NAME', config('mail.from.name')],
            ]
        );
    }

    /**
     * Configure Mailtrap SMTP.
     */
    protected function configureMailtrap(): int
    {
        $this->info('📧 Configuring Mailtrap SMTP');
        $this->line('1. Sign up at https://mailtrap.io (free)');
        $this->line('2. Create an inbox');
        $this->line('3. Get your SMTP credentials');
        $this->newLine();

        $username = $this->ask('Enter your Mailtrap username');
        $password = $this->secret('Enter your Mailtrap password');

        $config = [
            'MAIL_MAILER=smtp',
            'MAIL_HOST=sandbox.smtp.mailtrap.io',
            'MAIL_PORT=2525',
            "MAIL_USERNAME={$username}",
            "MAIL_PASSWORD={$password}",
            'MAIL_ENCRYPTION=tls',
            'MAIL_FROM_ADDRESS=test@studeats.dev',
            'MAIL_FROM_NAME="StudEats"',
        ];

        return $this->writeConfig($config);
    }

    /**
     * Configure Gmail SMTP.
     */
    protected function configureGmail(): int
    {
        $this->info('📧 Configuring Gmail SMTP');
        $this->warn('⚠️  Important: You need to use an App Password, not your regular password!');
        $this->line('1. Enable 2-Factor Authentication on your Gmail account');
        $this->line('2. Go to Google Account → Security → 2-Step Verification → App passwords');
        $this->line('3. Generate an app password for "Mail"');
        $this->newLine();

        $email = $this->ask('Enter your Gmail address');
        $password = $this->secret('Enter your 16-character App Password');

        $config = [
            'MAIL_MAILER=smtp',
            'MAIL_HOST=smtp.gmail.com',
            'MAIL_PORT=587',
            "MAIL_USERNAME={$email}",
            "MAIL_PASSWORD={$password}",
            'MAIL_ENCRYPTION=tls',
            "MAIL_FROM_ADDRESS={$email}",
            'MAIL_FROM_NAME="StudEats"',
        ];

        return $this->writeConfig($config);
    }

    /**
     * Configure MailHog.
     */
    protected function configureMailhog(): int
    {
        $this->info('📧 Configuring MailHog');
        $this->line('Make sure MailHog is running on localhost:1025');
        $this->line('Install: https://github.com/mailhog/MailHog');
        $this->newLine();

        $config = [
            'MAIL_MAILER=smtp',
            'MAIL_HOST=127.0.0.1',
            'MAIL_PORT=1025',
            'MAIL_USERNAME=null',
            'MAIL_PASSWORD=null',
            'MAIL_ENCRYPTION=null',
            'MAIL_FROM_ADDRESS=test@studeats.local',
            'MAIL_FROM_NAME="StudEats"',
        ];

        return $this->writeConfig($config);
    }

    /**
     * Configure custom SMTP.
     */
    protected function configureCustom(): int
    {
        $this->info('📧 Configuring Custom SMTP');

        $host = $this->ask('SMTP Host');
        $port = $this->ask('SMTP Port', '587');
        $username = $this->ask('SMTP Username');
        $password = $this->secret('SMTP Password');
        $encryption = $this->choice('Encryption', ['tls', 'ssl', 'none'], 'tls');
        $fromEmail = $this->ask('From Email Address');

        $config = [
            'MAIL_MAILER=smtp',
            "MAIL_HOST={$host}",
            "MAIL_PORT={$port}",
            "MAIL_USERNAME={$username}",
            "MAIL_PASSWORD={$password}",
            "MAIL_ENCRYPTION={$encryption}",
            "MAIL_FROM_ADDRESS={$fromEmail}",
            'MAIL_FROM_NAME="StudEats"',
        ];

        return $this->writeConfig($config);
    }

    /**
     * Configure log driver.
     */
    protected function configureLog(): int
    {
        $this->warn('⚠️  Log driver will write emails to storage/logs/laravel.log');

        $config = [
            'MAIL_MAILER=log',
            'MAIL_FROM_ADDRESS=test@studeats.local',
            'MAIL_FROM_NAME="StudEats"',
        ];

        return $this->writeConfig($config);
    }

    /**
     * Write configuration to .env file.
     */
    protected function writeConfig(array $config): int
    {
        $this->info('💾 Writing configuration...');

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // Remove existing mail configuration
        $envContent = preg_replace('/^MAIL_.*$/m', '', $envContent);
        
        // Clean up extra newlines
        $envContent = preg_replace('/\n\n+/', "\n\n", $envContent);

        // Add new mail configuration
        $envContent .= "\n# Mail Configuration\n";
        $envContent .= implode("\n", $config) . "\n";

        file_put_contents($envPath, $envContent);

        $this->info('✅ Configuration written to .env file');

        // Clear config cache
        $this->call('config:clear');
        $this->info('✅ Configuration cache cleared');

        // Test configuration if requested
        if ($this->option('test') || $this->confirm('Test the configuration now?', true)) {
            return $this->testConfiguration();
        }

        return self::SUCCESS;
    }

    /**
     * Test the email configuration.
     */
    protected function testConfiguration(): int
    {
        $this->info('🧪 Testing email configuration...');

        $testEmail = $this->ask('Enter email address to test with', 'test@example.com');

        try {
            Mail::raw('This is a test email from StudEats SMTP configuration.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('StudEats SMTP Test - ' . now()->format('Y-m-d H:i:s'));
            });

            $this->info('✅ Test email sent successfully!');

            if (config('mail.default') === 'log') {
                $this->warn('📋 Check storage/logs/laravel.log for the email content');
            } else {
                $this->info("📧 Check {$testEmail} for the test email");
            }

            // Test OTP sending
            if ($this->confirm('Test OTP sending as well?', true)) {
                $this->call('test:email-config', ['email' => $testEmail]);
            }

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->newLine();
            
            $this->error('Common issues:');
            $this->line('• Check your credentials');
            $this->line('• Verify network connectivity');
            $this->line('• Check firewall settings');
            $this->line('• For Gmail: Ensure you\'re using an App Password');
            
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
