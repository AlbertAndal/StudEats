<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSmtpConnectivity extends Command
{
    protected $signature = 'test:smtp-connectivity {host?} {port?}';
    protected $description = 'Test SMTP server connectivity and authentication';

    public function handle(): int
    {
        $host = $this->argument('host') ?: $this->ask('SMTP Host', 'smtp.gmail.com');
        $port = $this->argument('port') ?: $this->ask('SMTP Port', '587');

        $this->info("🌐 Testing connectivity to {$host}:{$port}");

        // Test basic connectivity
        $this->testConnection($host, $port);

        // Test common SMTP ports if default fails
        if (!$this->isConnectable($host, $port)) {
            $this->info('Testing alternative ports...');
            $commonPorts = ['25', '465', '587', '2525'];
            
            foreach ($commonPorts as $testPort) {
                if ($testPort != $port) {
                    $this->testConnection($host, $testPort);
                }
            }
        }

        // Network diagnostics
        $this->runNetworkDiagnostics($host);

        return self::SUCCESS;
    }

    protected function testConnection(string $host, string $port): bool
    {
        $this->line("Testing {$host}:{$port}...");

        if ($this->isConnectable($host, $port)) {
            $this->info("✅ {$host}:{$port} is reachable");
            
            // Try to get SMTP banner
            $this->getSmtpBanner($host, $port);
            return true;
        } else {
            $this->error("❌ {$host}:{$port} is not reachable");
            return false;
        }
    }

    protected function isConnectable(string $host, string $port): bool
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 10);
        
        if ($connection) {
            fclose($connection);
            return true;
        }
        
        return false;
    }

    protected function getSmtpBanner(string $host, string $port): void
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 10);
        
        if ($connection) {
            $banner = fgets($connection, 128);
            if ($banner) {
                $this->line("   Banner: " . trim($banner));
            }
            fclose($connection);
        }
    }

    protected function runNetworkDiagnostics(string $host): void
    {
        $this->newLine();
        $this->info('🔍 Network Diagnostics:');

        // DNS resolution
        $ip = gethostbyname($host);
        if ($ip !== $host) {
            $this->info("✅ DNS resolution: {$host} → {$ip}");
        } else {
            $this->error("❌ DNS resolution failed for {$host}");
        }

        // Check if running on Windows for additional commands
        if (PHP_OS_FAMILY === 'Windows') {
            $this->line('💡 You can also test connectivity manually:');
            $this->line("   telnet {$host} 587");
            $this->line("   nslookup {$host}");
        }

        // Common firewall ports info
        $this->newLine();
        $this->info('📋 Common SMTP Ports:');
        $this->table(
            ['Port', 'Security', 'Description'],
            [
                ['25', 'None', 'Standard SMTP (often blocked by ISPs)'],
                ['465', 'SSL/TLS', 'SMTP over SSL (legacy)'],
                ['587', 'STARTTLS', 'Submission port (recommended)'],
                ['2525', 'STARTTLS', 'Alternative submission port'],
            ]
        );
    }
}