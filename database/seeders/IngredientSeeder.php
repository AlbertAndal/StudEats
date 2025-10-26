<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            // Rice - Commodity ID: 1
            [
                'name' => 'Regular Milled Rice',
                'bantay_presyo_name' => 'Regular Milled Rice',
                'unit' => 'kg',
                'category' => 'rice',
                'bantay_presyo_commodity_id' => 1,
                'is_active' => true,
                'description' => 'Standard white rice',
                'alternative_names' => json_encode(['bigas', 'regular rice', 'white rice']),
            ],
            [
                'name' => 'Well Milled Rice',
                'bantay_presyo_name' => 'Well Milled Rice',
                'unit' => 'kg',
                'category' => 'rice',
                'bantay_presyo_commodity_id' => 1,
                'is_active' => true,
                'description' => 'Higher quality white rice',
                'alternative_names' => json_encode(['premium rice', 'well-milled rice']),
            ],

            // Meat and Poultry - Commodity ID: 8
            [
                'name' => 'Chicken Whole',
                'bantay_presyo_name' => 'Chicken - Whole',
                'unit' => 'kg',
                'category' => 'meat',
                'bantay_presyo_commodity_id' => 8,
                'is_active' => true,
                'description' => 'Whole chicken',
                'alternative_names' => json_encode(['manok', 'native chicken', 'broiler chicken']),
            ],
            [
                'name' => 'Chicken Dressed',
                'bantay_presyo_name' => 'Chicken - Dressed',
                'unit' => 'kg',
                'category' => 'meat',
                'bantay_presyo_commodity_id' => 8,
                'is_active' => true,
                'description' => 'Cleaned and dressed chicken',
                'alternative_names' => json_encode(['dressed chicken', 'processed chicken']),
            ],
            [
                'name' => 'Pork Liempo',
                'bantay_presyo_name' => 'Pork - Liempo',
                'unit' => 'kg',
                'category' => 'meat',
                'bantay_presyo_commodity_id' => 8,
                'is_active' => true,
                'description' => 'Pork belly',
                'alternative_names' => json_encode(['pork belly', 'baboy liempo']),
            ],
            [
                'name' => 'Pork Kasim',
                'bantay_presyo_name' => 'Pork - Kasim',
                'unit' => 'kg',
                'category' => 'meat',
                'bantay_presyo_commodity_id' => 8,
                'is_active' => true,
                'description' => 'Pork shoulder',
                'alternative_names' => json_encode(['pork shoulder', 'kasim']),
            ],
            [
                'name' => 'Beef Brisket',
                'bantay_presyo_name' => 'Beef - Brisket',
                'unit' => 'kg',
                'category' => 'meat',
                'bantay_presyo_commodity_id' => 8,
                'is_active' => true,
                'description' => 'Beef brisket cut',
                'alternative_names' => json_encode(['beef tadyang', 'brisket']),
            ],

            // Fish - Commodity ID: 4
            [
                'name' => 'Tilapia',
                'bantay_presyo_name' => 'Tilapia',
                'unit' => 'kg',
                'category' => 'fish',
                'bantay_presyo_commodity_id' => 4,
                'is_active' => true,
                'description' => 'Freshwater fish',
                'alternative_names' => json_encode(['tilapya']),
            ],
            [
                'name' => 'Bangus (Milkfish)',
                'bantay_presyo_name' => 'Bangus',
                'unit' => 'kg',
                'category' => 'fish',
                'bantay_presyo_commodity_id' => 4,
                'is_active' => true,
                'description' => 'Philippine milkfish',
                'alternative_names' => json_encode(['milkfish', 'bangus']),
            ],
            [
                'name' => 'Galunggong',
                'bantay_presyo_name' => 'Galunggong',
                'unit' => 'kg',
                'category' => 'fish',
                'bantay_presyo_commodity_id' => 4,
                'is_active' => true,
                'description' => 'Round scad fish',
                'alternative_names' => json_encode(['mackerel scad', 'round scad']),
            ],

            // Lowland Vegetables - Commodity ID: 7
            [
                'name' => 'Tomato',
                'bantay_presyo_name' => 'Tomato',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 7,
                'is_active' => true,
                'description' => 'Fresh tomatoes',
                'alternative_names' => json_encode(['kamatis']),
            ],
            [
                'name' => 'Onion Red',
                'bantay_presyo_name' => 'Onion - Red',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 7,
                'is_active' => true,
                'description' => 'Red onions',
                'alternative_names' => json_encode(['sibuyas pula', 'red onion']),
            ],
            [
                'name' => 'Garlic',
                'bantay_presyo_name' => 'Garlic',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 7,
                'is_active' => true,
                'description' => 'Fresh garlic',
                'alternative_names' => json_encode(['bawang']),
            ],
            [
                'name' => 'Eggplant',
                'bantay_presyo_name' => 'Eggplant',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 7,
                'is_active' => true,
                'description' => 'Fresh eggplant',
                'alternative_names' => json_encode(['talong']),
            ],
            [
                'name' => 'Bitter Gourd',
                'bantay_presyo_name' => 'Ampalaya',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 7,
                'is_active' => true,
                'description' => 'Bitter melon',
                'alternative_names' => json_encode(['ampalaya', 'bitter melon']),
            ],
            [
                'name' => 'String Beans',
                'bantay_presyo_name' => 'Sitaw',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 7,
                'is_active' => true,
                'description' => 'Long beans',
                'alternative_names' => json_encode(['sitaw', 'yard long beans']),
            ],

            // Highland Vegetables - Commodity ID: 6
            [
                'name' => 'Cabbage',
                'bantay_presyo_name' => 'Cabbage',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 6,
                'is_active' => true,
                'description' => 'Fresh cabbage',
                'alternative_names' => json_encode(['repolyo']),
            ],
            [
                'name' => 'Carrots',
                'bantay_presyo_name' => 'Carrot',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 6,
                'is_active' => true,
                'description' => 'Fresh carrots',
                'alternative_names' => json_encode(['karot']),
            ],
            [
                'name' => 'Potato',
                'bantay_presyo_name' => 'Potato',
                'unit' => 'kg',
                'category' => 'vegetables',
                'bantay_presyo_commodity_id' => 6,
                'is_active' => true,
                'description' => 'Fresh potatoes',
                'alternative_names' => json_encode(['patatas']),
            ],

            // Spices - Commodity ID: 9
            [
                'name' => 'Chili',
                'bantay_presyo_name' => 'Chili - Labuyo',
                'unit' => 'kg',
                'category' => 'others',
                'bantay_presyo_commodity_id' => 9,
                'is_active' => true,
                'description' => 'Hot chili peppers',
                'alternative_names' => json_encode(['siling labuyo', 'bird\'s eye chili']),
            ],
            [
                'name' => 'Ginger',
                'bantay_presyo_name' => 'Ginger',
                'unit' => 'kg',
                'category' => 'others',
                'bantay_presyo_commodity_id' => 9,
                'is_active' => true,
                'description' => 'Fresh ginger root',
                'alternative_names' => json_encode(['luya']),
            ],

            // Other Commodities - Commodity ID: 10
            [
                'name' => 'Eggs Chicken',
                'bantay_presyo_name' => 'Egg - Chicken',
                'unit' => 'piece',
                'category' => 'others',
                'bantay_presyo_commodity_id' => 10,
                'is_active' => true,
                'description' => 'Chicken eggs',
                'alternative_names' => json_encode(['itlog ng manok', 'chicken eggs']),
            ],
            [
                'name' => 'Cooking Oil',
                'bantay_presyo_name' => 'Cooking Oil',
                'unit' => 'liter',
                'category' => 'others',
                'bantay_presyo_commodity_id' => 10,
                'is_active' => true,
                'description' => 'Vegetable cooking oil',
                'alternative_names' => json_encode(['mantika', 'vegetable oil']),
            ],
            [
                'name' => 'Sugar Refined',
                'bantay_presyo_name' => 'Sugar - Refined',
                'unit' => 'kg',
                'category' => 'others',
                'bantay_presyo_commodity_id' => 10,
                'is_active' => true,
                'description' => 'White refined sugar',
                'alternative_names' => json_encode(['asukal', 'white sugar']),
            ],
            [
                'name' => 'Salt',
                'bantay_presyo_name' => 'Salt',
                'unit' => 'kg',
                'category' => 'others',
                'bantay_presyo_commodity_id' => 10,
                'is_active' => true,
                'description' => 'Table salt',
                'alternative_names' => json_encode(['asin']),
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate(
                [
                    'bantay_presyo_name' => $ingredient['bantay_presyo_name'],
                    'bantay_presyo_commodity_id' => $ingredient['bantay_presyo_commodity_id'],
                ],
                $ingredient
            );
        }

        $this->command->info('Successfully seeded ' . count($ingredients) . ' ingredients!');
    }
}
