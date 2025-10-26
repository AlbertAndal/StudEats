<?php

namespace App\Console\Commands;

use App\Services\MagicLinkDebugger;
use App\Services\OtpService;
use Illuminate\Console\Command;

class DebugVerificationLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:debug 
                            {email : The email address to debug}
                            {--token= : The verification token to test}
                            {--generate : Generate a new verification link}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug magic link verification issues';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $token = $this->option('token');
        $generate = $this->option('generate');

        $debugger = app(MagicLinkDebugger::class);
        $otpService = app(OtpService::class);

        $this->info("Magic Link Verification Debugger");
        $this->info("=====================================");
        $this->line("Email: {$email}");

        if ($generate) {
            $this->info("\n🔧 Generating new verification link...");
            try {
                $result = $otpService->generateAndSendVerificationLink($email);
                $this->info("✅ Verification link generated successfully!");
                $this->line("Token: {$result->verification_token}");
                
                // Generate test URL
                $urlInfo = $debugger->generateTestVerificationUrl($email, $result->verification_token);
                $this->line("URL: {$urlInfo['full_url']}");
                
            } catch (\Exception $e) {
                $this->error("❌ Failed to generate link: {$e->getMessage()}");
                return 1;
            }
        }

        if ($token) {
            $this->info("\n🔍 Debugging verification attempt...");
            $debugInfo = $debugger->debugVerificationAttempt($email, $token);
            
            $this->displayDebugInfo($debugInfo);
            
            // Test verification
            $this->info("\n🧪 Testing verification...");
            $result = $otpService->verifyToken($email, $token);
            
            if ($result['success']) {
                $this->info("✅ Verification successful!");
                $this->line("Message: {$result['message']}");
            } else {
                $this->error("❌ Verification failed!");
                $this->line("Message: {$result['message']}");
            }
        }

        return 0;
    }

    /**
     * Display debug information in a readable format.
     */
    private function displayDebugInfo(array $debugInfo): void
    {
        $this->info("\n📊 Debug Analysis:");
        
        // Token Analysis
        $tokenInfo = $debugInfo['token_analysis'];
        $this->line("🔑 Token:");
        $this->line("  - Length: {$tokenInfo['length']}");
        $this->line("  - Format: {$tokenInfo['format']}");
        $this->line("  - Valid Hex: " . ($tokenInfo['hex_validation'] ? 'Yes' : 'No'));
        $this->line("  - URL Encoded: " . ($tokenInfo['url_encoded_chars'] ? 'Yes' : 'No'));

        // Database Check
        $dbInfo = $debugInfo['database_check'];
        $this->line("\n🗄️ Database:");
        $this->line("  - Total records for email: {$dbInfo['total_records_for_email']}");
        $this->line("  - Exact match found: " . ($dbInfo['exact_match_found'] ? 'Yes' : 'No'));
        
        if ($dbInfo['exact_match_found']) {
            $details = $dbInfo['exact_match_details'];
            $this->line("  - Record ID: {$details['id']}");
            $this->line("  - Is used: " . ($details['is_used'] ? 'Yes' : 'No'));
            $this->line("  - Is expired: " . ($details['is_expired'] ? 'Yes' : 'No'));
            $this->line("  - Expires at: {$details['expires_at']}");
        }

        // Verification Flow
        if (isset($debugInfo['verification_flow'])) {
            $this->line("\n🔄 Verification Flow:");
            foreach ($debugInfo['verification_flow'] as $step) {
                $status = $step['success'] ? '✅' : '❌';
                $this->line("  {$status} {$step['step']}: {$step['details']}");
            }
        }

        // Timezone Analysis
        $timezone = $debugInfo['timezone_analysis'];
        $this->line("\n🌍 Timezone:");
        $this->line("  - App timezone: {$timezone['app_timezone']}");
        $this->line("  - Current time: {$timezone['current_time_app']}");
        $this->line("  - UTC time: {$timezone['current_time_utc']}");
    }
}