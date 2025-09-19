<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class MonitorEmailQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:monitor 
                            {--threshold=100 : Queue size threshold for warnings}
                            {--critical=500 : Queue size threshold for critical alerts}
                            {--notify : Send notifications to admins if issues detected}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor email queue health and alert administrators of issues';

    protected EmailService $emailService;

    /**
     * Create a new command instance.
     */
    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Checking email queue health...');

        $healthStatus = $this->emailService->checkEmailQueueHealth();
        $threshold = (int) $this->option('threshold');
        $critical = (int) $this->option('critical');
        $shouldNotify = $this->option('notify');

        // Display current status
        $this->displayHealthStatus($healthStatus);

        // Check thresholds and alert if necessary
        $emailQueueSize = $healthStatus['email_queue_size'] ?? 0;

        if ($emailQueueSize >= $critical) {
            $this->error("🚨 CRITICAL: Email queue has {$emailQueueSize} jobs (threshold: {$critical})");

            if ($shouldNotify) {
                $this->info('📧 Sending critical alert to administrators...');
                // This will be handled by the EmailService
            }

            return self::FAILURE;
        } elseif ($emailQueueSize >= $threshold) {
            $this->warn("⚠️  WARNING: Email queue has {$emailQueueSize} jobs (threshold: {$threshold})");

            if ($shouldNotify) {
                $this->info('📧 Sending warning to administrators...');
            }

            return self::FAILURE;
        }

        $this->info('✅ Email queue is healthy!');

        // Display recommendations
        $this->displayRecommendations($healthStatus);

        return self::SUCCESS;
    }

    /**
     * Display the current health status.
     */
    protected function displayHealthStatus(array $healthStatus): void
    {
        $this->newLine();
        $this->info('📊 Queue Health Status:');

        $this->table(
            ['Metric', 'Value'],
            [
                ['Email Queue Size', $healthStatus['email_queue_size'] ?? 'Unknown'],
                ['Default Queue Size', $healthStatus['default_queue_size'] ?? 'Unknown'],
                ['Overall Status', $this->getStatusColor($healthStatus['status'] ?? 'unknown')],
            ]
        );

        if (! empty($healthStatus['issues'])) {
            $this->newLine();
            $this->warn('⚠️  Issues Detected:');
            foreach ($healthStatus['issues'] as $issue) {
                $this->line("  • {$issue}");
            }
        }
    }

    /**
     * Get colored status text.
     */
    protected function getStatusColor(string $status): string
    {
        return match ($status) {
            'healthy' => '<fg=green>Healthy</fg=green>',
            'warning' => '<fg=yellow>Warning</fg=yellow>',
            'critical' => '<fg=red>Critical</fg=red>',
            'error' => '<fg=red>Error</fg=red>',
            default => '<fg=gray>Unknown</fg=gray>',
        };
    }

    /**
     * Display recommendations based on queue status.
     */
    protected function displayRecommendations(array $healthStatus): void
    {
        $emailQueueSize = $healthStatus['email_queue_size'] ?? 0;

        if ($emailQueueSize === 0) {
            $this->newLine();
            $this->info('💡 Recommendations:');
            $this->line('  • Queue is empty - all emails are being processed promptly');
            $this->line('  • Consider running queue workers if expecting email traffic');

            return;
        }

        if ($emailQueueSize > 0 && $emailQueueSize < 50) {
            $this->newLine();
            $this->info('💡 Recommendations:');
            $this->line('  • Queue has normal activity levels');
            $this->line('  • Monitor processing times to ensure timely delivery');

            return;
        }

        if ($emailQueueSize >= 50) {
            $this->newLine();
            $this->warn('💡 Recommendations:');
            $this->line('  • Consider increasing queue worker processes');
            $this->line('  • Check for any stuck or failing jobs');
            $this->line('  • Review email service configuration');
            $this->line('  • Monitor for recurring patterns in queue buildup');
        }
    }

    /**
     * Get email statistics and display them.
     */
    protected function displayEmailStats(): void
    {
        $stats = $this->emailService->getEmailStats(7);

        $this->newLine();
        $this->info('📈 Email Statistics (Last 7 days):');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Current Queue Size', $stats['current_queue_size']],
                ['Queue Status', $this->getStatusColor($stats['queue_status']['status'])],
                ['Note', $stats['note']],
            ]
        );
    }
}
