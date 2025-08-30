<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\Recipe;
use App\Models\NutritionalInfo;
use Illuminate\Database\Seeder;

class FilipinoCuisineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meals = [
            [
                'name' => 'Adobo',
                'description' => 'Classic Filipino dish with chicken or pork marinated in soy sauce, vinegar, and garlic.',
                'calories' => 350,
                'cost' => 120.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'Easy',
                'is_featured' => true,
                'ingredients' => [
                    '1 kg chicken or pork',
                    '1/2 cup soy sauce',
                    '1/2 cup vinegar',
                    '4 cloves garlic, minced',
                    '1 onion, sliced',
                    '2 bay leaves',
                    '1 tsp black peppercorns',
                    '1 cup water'
                ],
                'instructions' => "1. Marinate meat in soy sauce, vinegar, garlic, and bay leaves for 30 minutes\n2. Heat oil in a pan and brown the meat\n3. Add the marinade and water\n4. Simmer for 30-40 minutes until meat is tender\n5. Add onions and cook for 5 more minutes\n6. Serve hot with rice",
                'prep_time' => 15,
                'cook_time' => 45,
                'servings' => 4,
                'local_alternatives' => [
                    'Use chicken instead of pork for a lighter option',
                    'Substitute white vinegar with coconut vinegar for authentic taste',
                    'Add potatoes for a more filling meal'
                ],
                'nutritional_info' => [
                    'calories' => 350,
                    'protein' => 25,
                    'carbs' => 8,
                    'fats' => 22,
                    'fiber' => 2,
                    'sugar' => 3,
                    'sodium' => 1200
                ]
            ],
            [
                'name' => 'Sinigang',
                'description' => 'Sour tamarind soup with pork, vegetables, and tamarind broth.',
                'calories' => 280,
                'cost' => 150.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'Medium',
                'is_featured' => false,
                'ingredients' => [
                    '500g pork belly',
                    '1 packet sinigang mix',
                    '1 eggplant, sliced',
                    '1 bunch kangkong (water spinach)',
                    '2 tomatoes, quartered',
                    '1 onion, sliced',
                    '2 cups water',
                    'Salt to taste'
                ],
                'instructions' => "1. Boil pork in water until tender\n2. Add sinigang mix and stir well\n3. Add tomatoes and onions\n4. Simmer for 10 minutes\n5. Add eggplant and cook for 5 minutes\n6. Add kangkong and cook for 2 minutes\n7. Season with salt and serve hot",
                'prep_time' => 20,
                'cook_time' => 60,
                'servings' => 4,
                'local_alternatives' => [
                    'Use fish instead of pork for a lighter version',
                    'Add more vegetables like okra and radish',
                    'Use fresh tamarind instead of mix for authentic taste'
                ],
                'nutritional_info' => [
                    'calories' => 280,
                    'protein' => 18,
                    'carbs' => 12,
                    'fats' => 18,
                    'fiber' => 4,
                    'sugar' => 5,
                    'sodium' => 800
                ]
            ],
            [
                'name' => 'Pancit Canton',
                'description' => 'Stir-fried noodles with vegetables and meat, a popular Filipino noodle dish.',
                'calories' => 420,
                'cost' => 100.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'Easy',
                'is_featured' => false,
                'ingredients' => [
                    '500g pancit canton noodles',
                    '200g chicken, sliced',
                    '1 carrot, julienned',
                    '1 cabbage, shredded',
                    '2 cloves garlic, minced',
                    '1 onion, sliced',
                    '2 tbsp soy sauce',
                    '2 tbsp oyster sauce',
                    '2 tbsp cooking oil'
                ],
                'instructions' => "1. Cook noodles according to package instructions\n2. Heat oil and sauté garlic and onions\n3. Add chicken and cook until done\n4. Add vegetables and stir-fry\n5. Add noodles and sauces\n6. Toss well and serve hot",
                'prep_time' => 15,
                'cook_time' => 20,
                'servings' => 4,
                'local_alternatives' => [
                    'Use shrimp instead of chicken',
                    'Add more vegetables like bell peppers',
                    'Use different types of noodles like bihon'
                ],
                'nutritional_info' => [
                    'calories' => 420,
                    'protein' => 22,
                    'carbs' => 45,
                    'fats' => 18,
                    'fiber' => 3,
                    'sugar' => 4,
                    'sodium' => 900
                ]
            ],
            [
                'name' => 'Tapsilog',
                'description' => 'Breakfast favorite with cured beef, garlic rice, and fried egg.',
                'calories' => 380,
                'cost' => 80.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'Easy',
                'is_featured' => true,
                'ingredients' => [
                    '200g beef tapa (cured beef)',
                    '2 cups cooked rice',
                    '2 eggs',
                    '4 cloves garlic, minced',
                    '2 tbsp cooking oil',
                    'Salt and pepper to taste'
                ],
                'instructions' => "1. Cook beef tapa in oil until done\n2. Sauté garlic in oil until golden\n3. Add rice and stir-fry with garlic\n4. Fry eggs sunny side up\n5. Serve beef, rice, and egg together",
                'prep_time' => 10,
                'cook_time' => 15,
                'servings' => 2,
                'local_alternatives' => [
                    'Use chicken instead of beef',
                    'Add tomatoes and onions as side dish',
                    'Use brown rice for healthier option'
                ],
                'nutritional_info' => [
                    'calories' => 380,
                    'protein' => 28,
                    'carbs' => 35,
                    'fats' => 16,
                    'fiber' => 1,
                    'sugar' => 1,
                    'sodium' => 600
                ]
            ],
            [
                'name' => 'Ginisang Monggo',
                'description' => 'Healthy mung bean soup with vegetables and pork.',
                'calories' => 220,
                'cost' => 70.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'Easy',
                'is_featured' => false,
                'ingredients' => [
                    '1 cup mung beans',
                    '100g pork, diced',
                    '1 bunch malunggay leaves',
                    '2 cloves garlic, minced',
                    '1 onion, sliced',
                    '2 tomatoes, sliced',
                    '4 cups water',
                    'Salt to taste'
                ],
                'instructions' => "1. Boil mung beans in water until soft\n2. Sauté garlic, onions, and tomatoes\n3. Add pork and cook until done\n4. Add cooked mung beans\n5. Add malunggay leaves\n6. Season with salt and serve",
                'prep_time' => 10,
                'cook_time' => 45,
                'servings' => 4,
                'local_alternatives' => [
                    'Use shrimp instead of pork',
                    'Add more vegetables like squash',
                    'Use spinach instead of malunggay'
                ],
                'nutritional_info' => [
                    'calories' => 220,
                    'protein' => 15,
                    'carbs' => 25,
                    'fats' => 8,
                    'fiber' => 8,
                    'sugar' => 2,
                    'sodium' => 400
                ]
            ],
            [
                'name' => 'Chicken Tinola',
                'description' => 'Light chicken soup with ginger, papaya, and malunggay leaves.',
                'calories' => 180,
                'cost' => 110.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'Easy',
                'is_featured' => false,
                'ingredients' => [
                    '1 kg chicken, cut into pieces',
                    '1 thumb ginger, sliced',
                    '1 unripe papaya, cubed',
                    '1 bunch malunggay leaves',
                    '2 cloves garlic, minced',
                    '1 onion, sliced',
                    '6 cups water',
                    'Fish sauce to taste'
                ],
                'instructions' => "1. Sauté ginger, garlic, and onions\n2. Add chicken and cook until browned\n3. Add water and bring to boil\n4. Simmer for 30 minutes\n5. Add papaya and cook for 10 minutes\n6. Add malunggay leaves\n7. Season with fish sauce",
                'prep_time' => 15,
                'cook_time' => 45,
                'servings' => 6,
                'local_alternatives' => [
                    'Use fish instead of chicken',
                    'Add chayote instead of papaya',
                    'Use spinach instead of malunggay'
                ],
                'nutritional_info' => [
                    'calories' => 180,
                    'protein' => 22,
                    'carbs' => 8,
                    'fats' => 8,
                    'fiber' => 3,
                    'sugar' => 4,
                    'sodium' => 500
                ]
            ]
        ];

        foreach ($meals as $mealData) {
            $nutritionalInfo = $mealData['nutritional_info'];
            unset($mealData['nutritional_info']);
            
            $ingredients = $mealData['ingredients'];
            $instructions = $mealData['instructions'];
            $prep_time = $mealData['prep_time'];
            $cook_time = $mealData['cook_time'];
            $servings = $mealData['servings'];
            $local_alternatives = $mealData['local_alternatives'];
            
            unset($mealData['ingredients'], $mealData['instructions'], $mealData['prep_time'], 
                  $mealData['cook_time'], $mealData['servings'], $mealData['local_alternatives']);

            $meal = Meal::create($mealData);

            // Create recipe
            Recipe::create([
                'meal_id' => $meal->id,
                'ingredients' => $ingredients,
                'instructions' => $instructions,
                'prep_time' => $prep_time,
                'cook_time' => $cook_time,
                'servings' => $servings,
                'local_alternatives' => $local_alternatives
            ]);

            // Create nutritional info
            NutritionalInfo::create([
                'meal_id' => $meal->id,
                'calories' => $nutritionalInfo['calories'],
                'protein' => $nutritionalInfo['protein'],
                'carbs' => $nutritionalInfo['carbs'],
                'fats' => $nutritionalInfo['fats'],
                'fiber' => $nutritionalInfo['fiber'],
                'sugar' => $nutritionalInfo['sugar'],
                'sodium' => $nutritionalInfo['sodium']
            ]);
        }
    }
}
