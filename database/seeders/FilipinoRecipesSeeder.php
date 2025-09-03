<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class FilipinoRecipesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            // Adobong Manok Recipe
            [
                'meal_name' => 'Adobong Manok',
                'ingredients' => [
                    '1 kg chicken, cut into pieces',
                    '1/2 cup soy sauce',
                    '1/4 cup white vinegar',
                    '6 cloves garlic, minced',
                    '2 bay leaves',
                    '1 tsp black peppercorns',
                    '1 medium onion, sliced',
                    '2 tbsp cooking oil',
                    '1 cup water',
                    'Salt to taste'
                ],
                'instructions' => [
                    'In a bowl, marinate chicken with soy sauce, vinegar, garlic, and peppercorns for 30 minutes.',
                    'Heat oil in a pan and sauté onions until translucent.',
                    'Add marinated chicken and cook until lightly browned.',
                    'Pour in the marinade and water, add bay leaves.',
                    'Bring to a boil, then simmer for 30-40 minutes until chicken is tender.',
                    'Season with salt if needed.',
                    'Serve hot with steamed rice.'
                ],
                'tips' => 'For richer flavor, let it simmer longer. Some prefer to fry the chicken first before adding the sauce.'
            ],

            // Ginisang Monggo Recipe
            [
                'meal_name' => 'Ginisang Monggo',
                'ingredients' => [
                    '1 cup mung beans',
                    '4 cups water',
                    '1 medium onion, chopped',
                    '3 cloves garlic, minced',
                    '2 medium tomatoes, chopped',
                    '100g pork, diced (optional)',
                    '2 cups malunggay leaves',
                    '1 tbsp fish sauce',
                    '2 tbsp cooking oil',
                    'Salt and pepper to taste'
                ],
                'instructions' => [
                    'Wash and boil mung beans in water for 15-20 minutes until soft.',
                    'Mash some beans to thicken the soup.',
                    'In another pan, sauté garlic, onion, and tomatoes.',
                    'Add pork (if using) and cook until tender.',
                    'Add the cooked mung beans and simmer for 10 minutes.',
                    'Season with fish sauce, salt, and pepper.',
                    'Add malunggay leaves and cook for 2 more minutes.',
                    'Serve hot with rice.'
                ],
                'tips' => 'You can add shrimp or fish instead of pork. Malunggay leaves can be substituted with spinach.'
            ],

            // Pancit Canton Recipe
            [
                'meal_name' => 'Pancit Canton',
                'ingredients' => [
                    '500g canton noodles',
                    '200g pork, sliced thin',
                    '200g shrimp, peeled',
                    '1 medium onion, sliced',
                    '3 cloves garlic, minced',
                    '2 carrots, julienned',
                    '1 cup cabbage, shredded',
                    '1/4 cup soy sauce',
                    '2 tbsp oyster sauce',
                    '3 tbsp cooking oil',
                    '2 cups chicken broth',
                    'Green onions for garnish'
                ],
                'instructions' => [
                    'Soak canton noodles in warm water until soft, then drain.',
                    'Heat oil in a large pan or wok.',
                    'Sauté garlic and onion until fragrant.',
                    'Add pork and cook until browned.',
                    'Add shrimp and cook until pink.',
                    'Add carrots and cook for 2 minutes.',
                    'Add noodles, soy sauce, oyster sauce, and broth.',
                    'Toss everything together and cook for 3-5 minutes.',
                    'Add cabbage and cook until wilted.',
                    'Garnish with green onions and serve immediately.'
                ],
                'tips' => 'Don\'t overcook the noodles. Add vegetables last to keep them crisp.'
            ],

            // Tapsilog Recipe
            [
                'meal_name' => 'Tapsilog',
                'ingredients' => [
                    '500g beef sirloin, sliced thin',
                    '1/4 cup soy sauce',
                    '2 tbsp brown sugar',
                    '2 cloves garlic, minced',
                    '1/4 tsp black pepper',
                    '2 tbsp cooking oil',
                    '3 cups cooked garlic rice',
                    '3 eggs',
                    'Pickled vegetables (atchara)'
                ],
                'instructions' => [
                    'Marinate beef with soy sauce, brown sugar, garlic, and pepper for 2 hours.',
                    'Heat oil in a pan over medium-high heat.',
                    'Cook marinated beef for 3-4 minutes per side until caramelized.',
                    'In another pan, fry eggs sunny-side up or scrambled.',
                    'Serve beef with garlic rice and fried egg.',
                    'Garnish with pickled vegetables on the side.'
                ],
                'tips' => 'For authentic tapa, let beef marinate overnight. Cook on high heat for better caramelization.'
            ],

            // Sinigang na Baboy Recipe
            [
                'meal_name' => 'Sinigang na Baboy',
                'ingredients' => [
                    '1 kg pork ribs, cut into pieces',
                    '1 packet sinigang mix (tamarind)',
                    '1 medium onion, quartered',
                    '2 medium tomatoes, quartered',
                    '1 radish, sliced',
                    '6 pieces okra',
                    '1 bunch kangkong leaves',
                    '2 green chilies',
                    '6 cups water',
                    'Fish sauce to taste'
                ],
                'instructions' => [
                    'In a pot, boil pork ribs in water for 45 minutes until tender.',
                    'Add onions and tomatoes, simmer for 5 minutes.',
                    'Add sinigang mix and stir until dissolved.',
                    'Add radish and cook for 3 minutes.',
                    'Add okra and green chilies, cook for 3 minutes.',
                    'Season with fish sauce.',
                    'Add kangkong leaves last and cook for 1 minute.',
                    'Serve hot with rice.'
                ],
                'tips' => 'Adjust sourness by adding more or less sinigang mix. Add vegetables according to cooking time.'
            ],

            // Palabok Recipe
            [
                'meal_name' => 'Palabok',
                'ingredients' => [
                    '500g rice noodles (bihon)',
                    '2 cups shrimp broth',
                    '1/4 cup annatto oil',
                    '2 tbsp flour',
                    '200g shrimp, peeled',
                    '2 hard-boiled eggs, sliced',
                    '1/2 cup crushed chicharon',
                    '2 green onions, chopped',
                    '2 cloves garlic, minced',
                    'Fish sauce to taste'
                ],
                'instructions' => [
                    'Cook rice noodles according to package directions, drain and set aside.',
                    'Make annatto oil by heating oil with annatto seeds, strain.',
                    'In a pan, sauté garlic in annatto oil.',
                    'Add flour and cook for 1 minute.',
                    'Gradually add shrimp broth while stirring to avoid lumps.',
                    'Add shrimp and cook until pink.',
                    'Season with fish sauce.',
                    'Pour sauce over noodles.',
                    'Top with sliced eggs, chicharon, and green onions.'
                ],
                'tips' => 'For authentic color, use annatto instead of food coloring. Sauce should be thick enough to coat noodles.'
            ]
        ];

        foreach ($recipes as $recipeData) {
            $meal = Meal::where('name', $recipeData['meal_name'])->first();
            
            if ($meal) {
                Recipe::create([
                    'meal_id' => $meal->id,
                    'ingredients' => json_encode($recipeData['ingredients']),
                    'instructions' => json_encode($recipeData['instructions']),
                    'cooking_tips' => $recipeData['tips'] ?? null,
                    'servings' => 4,
                    'prep_time' => $meal->prep_time,
                    'difficulty_level' => $meal->difficulty,
                ]);
            }
        }
    }
}
