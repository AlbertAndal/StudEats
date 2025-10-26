<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\IngredientPriceHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class BantayPresyoService
{
    /**
     * Base URL for Bantay Presyo website
     */
    private const BASE_URL = 'http://www.bantaypresyo.da.gov.ph/';

    /**
     * Region codes mapping (NCR as default)
     */
    private const REGIONS = [
        'NCR' => '130000000',
        'CAR' => '140000000',
        'REGION_I' => '010000000',
        'REGION_II' => '020000000',
        'REGION_III' => '030000000',
        'REGION_IV_A' => '040000000',
        'REGION_IV_B' => '170000000',
        'REGION_V' => '050000000',
        'REGION_VI' => '060000000',
        'REGION_VII' => '070000000',
        'REGION_VIII' => '080000000',
        'REGION_IX' => '090000000',
        'REGION_X' => '100000000',
        'REGION_XI' => '110000000',
        'REGION_XII' => '120000000',
        'REGION_XIII' => '160000000',
        'BARMM' => '150000000',
    ];

    /**
     * Commodity category IDs
     */
    private const COMMODITIES = [
        'RICE' => 1,
        'CORN' => 2,
        'FISH' => 4,
        'FRUITS' => 5,
        'HIGHLAND_VEGETABLES' => 6,
        'LOWLAND_VEGETABLES' => 7,
        'MEAT_AND_POULTRY' => 8,
        'SPICES' => 9,
        'OTHER_COMMODITIES' => 10,
    ];

    /**
     * Fetch prices for all commodities from Bantay Presyo
     *
     * @param string $regionCode Region code (default: NCR)
     * @param array $commodityIds Array of commodity IDs to fetch (null = all)
     * @return array Array of fetched prices with success/error status
     */
    public function fetchAllPrices(string $regionCode = 'NCR', ?array $commodityIds = null): array
    {
        $region = self::REGIONS[$regionCode] ?? self::REGIONS['NCR'];
        $commodities = $commodityIds ?? array_values(self::COMMODITIES);
        
        $results = [
            'success' => true,
            'fetched' => 0,
            'failed' => 0,
            'details' => [],
            'timestamp' => now(),
        ];

        foreach ($commodities as $commodityId) {
            try {
                $priceData = $this->fetchCommodityPrices($region, $commodityId);
                
                if (!empty($priceData)) {
                    $this->storePriceData($priceData, $regionCode);
                    $results['fetched'] += count($priceData);
                    $results['details'][] = [
                        'commodity_id' => $commodityId,
                        'status' => 'success',
                        'count' => count($priceData),
                    ];
                } else {
                    $results['failed']++;
                    $results['details'][] = [
                        'commodity_id' => $commodityId,
                        'status' => 'no_data',
                    ];
                }
            } catch (Exception $e) {
                $results['failed']++;
                $results['success'] = false;
                $results['details'][] = [
                    'commodity_id' => $commodityId,
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                
                Log::error('Failed to fetch Bantay Presyo prices', [
                    'commodity_id' => $commodityId,
                    'region' => $regionCode,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Bantay Presyo price fetch completed', [
            'region' => $regionCode,
            'fetched' => $results['fetched'],
            'failed' => $results['failed'],
        ]);

        return $results;
    }

    /**
     * Fetch prices for a specific commodity category
     *
     * @param string $region Region code
     * @param int $commodityId Commodity category ID
     * @return array Array of price data
     */
    private function fetchCommodityPrices(string $region, int $commodityId): array
    {
        try {
            // Fetch the price data from Bantay Presyo AJAX endpoint
            $response = Http::timeout(30)
                ->asForm()
                ->post(self::BASE_URL . 'tbl_price_get_comm_price.php', [
                    'region' => $region,
                    'commodity' => $commodityId,
                    'count' => 10, // This is used by their frontend, arbitrary number
                ]);

            if (!$response->successful()) {
                throw new Exception("HTTP request failed with status {$response->status()}");
            }

            // Parse the HTML response to extract price data
            return $this->parseHtmlPriceData($response->body(), $commodityId, $region);
        } catch (Exception $e) {
            Log::error('Failed to fetch commodity prices from Bantay Presyo', [
                'commodity_id' => $commodityId,
                'region' => $region,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Parse HTML response to extract price data
     *
     * @param string $html HTML content from response
     * @param int $commodityId Commodity category ID
     * @param string $region Region code
     * @return array Parsed price data
     */
    private function parseHtmlPriceData(string $html, int $commodityId, string $region): array
    {
        $priceData = [];
        
        // Remove whitespace and parse HTML
        $html = trim($html);
        
        if (empty($html)) {
            return $priceData;
        }

        // Use DOMDocument to parse HTML
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $rows = $xpath->query('//tr');

        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            
            if ($cells->length < 3) {
                continue; // Skip header or incomplete rows
            }

            // Extract data from cells
            $commodity = trim($cells->item(0)->textContent ?? '');
            $market = trim($cells->item(1)->textContent ?? '');
            
            // Extract prices from remaining cells
            for ($i = 2; $i < $cells->length; $i++) {
                $priceText = trim($cells->item($i)->textContent ?? '');
                
                // Extract numeric price
                $price = $this->extractPrice($priceText);
                
                if ($price > 0 && !empty($commodity)) {
                    $priceData[] = [
                        'commodity_name' => $commodity,
                        'market' => $market,
                        'price' => $price,
                        'commodity_id' => $commodityId,
                        'region' => $region,
                        'raw_text' => $priceText,
                    ];
                }
            }
        }

        return $priceData;
    }

    /**
     * Extract numeric price from text
     *
     * @param string $text Price text (e.g., "₱45.00", "45.50")
     * @return float Extracted price
     */
    private function extractPrice(string $text): float
    {
        // Remove currency symbols and non-numeric characters except decimal point
        $cleaned = preg_replace('/[^\d.]/', '', $text);
        
        return $cleaned ? (float) $cleaned : 0.0;
    }

    /**
     * Store fetched price data in database
     *
     * @param array $priceData Array of price data
     * @param string $regionCode Region code
     * @return int Number of records stored
     */
    private function storePriceData(array $priceData, string $regionCode): int
    {
        $stored = 0;
        $recordedAt = now();

        DB::beginTransaction();
        
        try {
            foreach ($priceData as $data) {
                // Find or create ingredient
                $ingredient = Ingredient::firstOrCreate(
                    [
                        'bantay_presyo_name' => $data['commodity_name'],
                        'bantay_presyo_commodity_id' => $data['commodity_id'],
                    ],
                    [
                        'name' => $this->normalizeIngredientName($data['commodity_name']),
                        'unit' => 'kg', // Default unit
                        'category' => $this->mapCommodityToCategory($data['commodity_id']),
                        'is_active' => true,
                    ]
                );

                // Update current price on ingredient
                $ingredient->update([
                    'current_price' => $data['price'],
                    'price_source' => 'bantay_presyo',
                    'price_updated_at' => $recordedAt,
                ]);

                // Store price history
                IngredientPriceHistory::create([
                    'ingredient_id' => $ingredient->id,
                    'price' => $data['price'],
                    'price_source' => 'bantay_presyo',
                    'region_code' => $regionCode,
                    'recorded_at' => $recordedAt,
                    'raw_data' => json_encode($data),
                ]);

                $stored++;
            }

            DB::commit();
            
            Log::info('Stored Bantay Presyo price data', [
                'count' => $stored,
                'region' => $regionCode,
            ]);
            
            return $stored;
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to store Bantay Presyo price data', [
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Normalize ingredient name for display
     *
     * @param string $bantayPresyoName Name from Bantay Presyo
     * @return string Normalized name
     */
    private function normalizeIngredientName(string $bantayPresyoName): string
    {
        // Convert to title case and clean up
        return ucwords(strtolower(trim($bantayPresyoName)));
    }

    /**
     * Map Bantay Presyo commodity ID to ingredient category
     *
     * @param int $commodityId Commodity ID
     * @return string Category name
     */
    private function mapCommodityToCategory(int $commodityId): string
    {
        $mapping = [
            1 => 'rice',        // Rice
            2 => 'rice',        // Corn  
            4 => 'fish',        // Fish
            5 => 'fruits',      // Fruits
            6 => 'vegetables',  // Highland vegetables
            7 => 'vegetables',  // Lowland vegetables
            8 => 'meat',        // Meat and poultry
            9 => 'others',      // Spices
            10 => 'others',     // Other commodities
        ];

        return $mapping[$commodityId] ?? 'others';
    }

    /**
     * Get the latest price update timestamp
     *
     * @return Carbon|null
     */
    public function getLastUpdateTimestamp(): ?Carbon
    {
        $latest = IngredientPriceHistory::where('price_source', 'bantay_presyo')
            ->latest('recorded_at')
            ->first();

        return $latest?->recorded_at;
    }

    /**
     * Get available region codes
     *
     * @return array
     */
    public static function getAvailableRegions(): array
    {
        return self::REGIONS;
    }

    /**
     * Get available commodity categories
     *
     * @return array
     */
    public static function getAvailableCommodities(): array
    {
        return self::COMMODITIES;
    }
}
