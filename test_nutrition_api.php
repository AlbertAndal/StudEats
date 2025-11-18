<?php
// Test Nutrition API
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Nutrition API\n";
echo str_repeat('=', 50) . "\n";

// Check if API key is loaded
$apiKey = env('NUTRITION_API_KEY');
echo "API Key Status: " . ($apiKey ? '✅ Loaded' : '❌ Not Found') . "\n";
if ($apiKey) {
    echo "API Key Length: " . strlen($apiKey) . " characters\n";
    echo "API Key Preview: " . substr($apiKey, 0, 10) . "...\n";
}
echo "\n";

// Test the service
try {
    $service = app(\App\Services\NutritionApiService::class);
    echo "📡 Searching for 'chicken breast'...\n";
    
    $result = $service->searchFood('chicken breast');
    
    if (isset($result['foods']) && count($result['foods']) > 0) {
        echo "✅ API Working Successfully!\n";
        echo "Found " . count($result['foods']) . " results\n\n";
        
        echo "📋 First 3 Results:\n";
        echo str_repeat('-', 50) . "\n";
        for ($i = 0; $i < min(3, count($result['foods'])); $i++) {
            $food = $result['foods'][$i];
            echo ($i + 1) . ". " . $food['description'] . "\n";
            echo "   Brand: " . ($food['brandName'] ?? 'Generic') . "\n";
            echo "   Type: " . ($food['dataType'] ?? 'N/A') . "\n";
            echo "\n";
        }
        
        // Try getting nutrition for first item
        if (isset($result['foods'][0]['fdcId'])) {
            echo "🔬 Testing Nutrition Details...\n";
            $fdcId = $result['foods'][0]['fdcId'];
            $nutrition = $service->getFoodNutrition($fdcId);
            
            if (isset($nutrition['foodNutrients'])) {
                echo "✅ Nutrition data retrieved successfully!\n";
                echo "Found " . count($nutrition['foodNutrients']) . " nutrients\n";
            } else {
                echo "⚠️ Nutrition data format unexpected\n";
            }
        }
        
    } elseif (isset($result['error'])) {
        echo "❌ API Error: " . $result['error'] . "\n";
        if (isset($result['message'])) {
            echo "Message: " . $result['message'] . "\n";
        }
    } else {
        echo "⚠️ API responded but no results found\n";
        echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "Test Complete!\n";
?>