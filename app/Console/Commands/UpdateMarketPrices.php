<?php

namespace App\Console\Commands;

use App\Services\BantayPresyoService;
use Illuminate\Console\Command;

class UpdateMarketPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:update 
                            {--region=NCR : Region code to fetch prices for}
                            {--commodities=* : Specific commodity IDs to fetch (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update ingredient prices from Bantay Presyo';

    /**
     * Execute the console command.
     */
    public function handle(BantayPresyoService $bantayPresyoService): int
    {
        $this->info('Starting Bantay Presyo price update...');
        
        $region = $this->option('region');
        $commodities = $this->option('commodities');
        
        // Convert commodities to integers if provided
        $commodityIds = !empty($commodities) ? array_map('intval', $commodities) : null;

        $this->info("Fetching prices for region: {$region}");
        
        if ($commodityIds) {
            $this->info('Fetching specific commodities: ' . implode(', ', $commodityIds));
        } else {
            $this->info('Fetching all commodities');
        }

        try {
            // Fetch prices
            $results = $bantayPresyoService->fetchAllPrices($region, $commodityIds);

            // Display results
            $this->newLine();
            $this->info('Price Update Results:');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Successfully Fetched', $results['fetched']],
                    ['Failed', $results['failed']],
                    ['Timestamp', $results['timestamp']->format('Y-m-d H:i:s')],
                ]
            );

            // Display details if there are any failures
            if ($results['failed'] > 0) {
                $this->newLine();
                $this->warn('Failed commodities:');
                
                foreach ($results['details'] as $detail) {
                    if ($detail['status'] !== 'success') {
                        $this->line("  - Commodity {$detail['commodity_id']}: {$detail['status']}");
                        if (isset($detail['message'])) {
                            $this->error("    Error: {$detail['message']}");
                        }
                    }
                }
            }

            // Display last update time
            $lastUpdate = $bantayPresyoService->getLastUpdateTimestamp();
            if ($lastUpdate) {
                $this->newLine();
                $this->info("Last successful update: {$lastUpdate->format('Y-m-d H:i:s')} ({$lastUpdate->diffForHumans()})");
            }

            if ($results['success']) {
                $this->newLine();
                $this->info('✓ Price update completed successfully!');
                return Command::SUCCESS;
            } else {
                $this->newLine();
                $this->warn('⚠ Price update completed with some errors.');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('✗ Price update failed!');
            $this->error("Error: {$e->getMessage()}");
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            return Command::FAILURE;
        }
    }
}
