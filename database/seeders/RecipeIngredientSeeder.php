<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeIngredientSeeder extends Seeder
{
    /**
     * Seed the recipe_ingredients table with sample Filipino recipes.
     */
    public function run(): void
    {
        // Find or create sample meals with recipes
        $this->seedAdobo();
        $this->seedSinigang();
        $this->seedTinola();
        $this->seedFriedRice();
        $this->seedPansitCanton();
        
        $this->command->info('Recipe ingredients seeded successfully!');
    }

    /**
     * Seed Adobo recipe ingredients.
     */
    private function seedAdobo(): void
    {
        $meal = Meal::updateOrCreate(
            ['name' => 'Chicken Adobo'],
            [
                'description' => 'Classic Filipino dish with chicken braised in soy sauce and vinegar',
                'cost' => 150.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'easy',
            ]
        );

        $recipe = Recipe::updateOrCreate(
            ['meal_id' => $meal->id],
            [
                'ingredients' => [
                    'Chicken (dressed)',
                    'Soy Sauce',
                    'Vinegar',
                    'Garlic',
                    'Bay Leaves',
                    'Peppercorns',
                ],
                'instructions' => "1. Combine chicken, soy sauce, vinegar, garlic, bay leaves, and peppercorns in a pot.\n2. Bring to a boil, then simmer for 30-40 minutes until chicken is tender.\n3. Remove chicken and reduce sauce.\n4. Return chicken to sauce and serve hot with rice.",
                'prep_time' => 10,
                'cook_time' => 40,
                'servings' => 4,
            ]
        );

        // Attach ingredients
        $chickenDressed = Ingredient::where('name', 'Chicken Dressed')->first();
        if ($chickenDressed) {
            $recipe->ingredientRelations()->syncWithoutDetaching([
                $chickenDressed->id => [
                    'quantity' => 1.0,
                    'unit' => 'kg',
                    'estimated_cost' => 150.00,
                    'notes' => 'Whole chicken, cut into pieces',
                ],
            ]);
        }
    }

    /**
     * Seed Sinigang recipe ingredients.
     */
    private function seedSinigang(): void
    {
        $meal = Meal::updateOrCreate(
            ['name' => 'Sinigang na Baboy'],
            [
                'description' => 'Sour tamarind soup with pork and vegetables',
                'cost' => 200.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'easy',
            ]
        );

        $recipe = Recipe::updateOrCreate(
            ['meal_id' => $meal->id],
            [
                'ingredients' => [
                    'Pork Kasim',
                    'Tomatoes',
                    'Onions',
                    'Kangkong',
                    'Radish',
                    'Tamarind',
                ],
                'instructions' => "1. Boil pork in water until tender.\n2. Add tomatoes and onions, cook until soft.\n3. Add tamarind broth for sourness.\n4. Add vegetables and cook for 5 minutes.\n5. Season with salt and serve hot.",
                'prep_time' => 15,
                'cook_time' => 45,
                'servings' => 6,
            ]
        );

        // Attach ingredients
        $porkKasim = Ingredient::where('name', 'Pork Kasim')->first();
        $tomatoes = Ingredient::where('name', 'Tomatoes')->first();
        $onions = Ingredient::where('name', 'Onions')->first();

        $attachments = [];
        if ($porkKasim) {
            $attachments[$porkKasim->id] = [
                'quantity' => 0.5,
                'unit' => 'kg',
                'estimated_cost' => 120.00,
                'notes' => 'Pork shoulder, cut into chunks',
            ];
        }
        if ($tomatoes) {
            $attachments[$tomatoes->id] = [
                'quantity' => 0.2,
                'unit' => 'kg',
                'estimated_cost' => 15.00,
                'notes' => 'Quartered',
            ];
        }
        if ($onions) {
            $attachments[$onions->id] = [
                'quantity' => 0.1,
                'unit' => 'kg',
                'estimated_cost' => 10.00,
                'notes' => 'Sliced',
            ];
        }

        if (!empty($attachments)) {
            $recipe->ingredientRelations()->syncWithoutDetaching($attachments);
        }
    }

    /**
     * Seed Tinola recipe ingredients.
     */
    private function seedTinola(): void
    {
        $meal = Meal::updateOrCreate(
            ['name' => 'Chicken Tinola'],
            [
                'description' => 'Ginger chicken soup with green papaya and chili leaves',
                'cost' => 140.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'easy',
            ]
        );

        $recipe = Recipe::updateOrCreate(
            ['meal_id' => $meal->id],
            [
                'ingredients' => [
                    'Chicken (dressed)',
                    'Ginger',
                    'Onions',
                    'Green Papaya',
                    'Chili Leaves',
                ],
                'instructions' => "1. Sauté ginger and onions until fragrant.\n2. Add chicken pieces and brown slightly.\n3. Pour water and bring to boil.\n4. Add green papaya and simmer until chicken is cooked.\n5. Add chili leaves last minute and serve hot.",
                'prep_time' => 10,
                'cook_time' => 30,
                'servings' => 4,
            ]
        );

        $chickenDressed = Ingredient::where('name', 'Chicken Dressed')->first();
        $ginger = Ingredient::where('name', 'Ginger')->first();
        $onions = Ingredient::where('name', 'Onions')->first();

        $attachments = [];
        if ($chickenDressed) {
            $attachments[$chickenDressed->id] = [
                'quantity' => 0.8,
                'unit' => 'kg',
                'estimated_cost' => 120.00,
                'notes' => 'Cut into serving pieces',
            ];
        }
        if ($ginger) {
            $attachments[$ginger->id] = [
                'quantity' => 0.05,
                'unit' => 'kg',
                'estimated_cost' => 5.00,
                'notes' => 'Sliced thinly',
            ];
        }
        if ($onions) {
            $attachments[$onions->id] = [
                'quantity' => 0.1,
                'unit' => 'kg',
                'estimated_cost' => 10.00,
                'notes' => 'Quartered',
            ];
        }

        if (!empty($attachments)) {
            $recipe->ingredientRelations()->syncWithoutDetaching($attachments);
        }
    }

    /**
     * Seed Fried Rice recipe ingredients.
     */
    private function seedFriedRice(): void
    {
        $meal = Meal::updateOrCreate(
            ['name' => 'Sinangag (Garlic Fried Rice)'],
            [
                'description' => 'Filipino garlic fried rice, perfect for breakfast',
                'cost' => 30.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'easy',
            ]
        );

        $recipe = Recipe::updateOrCreate(
            ['meal_id' => $meal->id],
            [
                'ingredients' => [
                    'Commercial Rice (Regular Milled)',
                    'Garlic',
                    'Cooking Oil',
                    'Salt',
                ],
                'instructions' => "1. Heat oil in a pan.\n2. Sauté minced garlic until golden brown.\n3. Add day-old rice and break up clumps.\n4. Stir-fry until rice is heated through.\n5. Season with salt and serve hot.",
                'prep_time' => 5,
                'cook_time' => 10,
                'servings' => 2,
            ]
        );

        $rice = Ingredient::where('name', 'LIKE', 'Commercial%Regular%')->first();
        $garlic = Ingredient::where('name', 'Garlic')->first();

        $attachments = [];
        if ($rice) {
            $attachments[$rice->id] = [
                'quantity' => 0.3,
                'unit' => 'kg',
                'estimated_cost' => 12.00,
                'notes' => 'Day-old cooked rice',
            ];
        }
        if ($garlic) {
            $attachments[$garlic->id] = [
                'quantity' => 0.02,
                'unit' => 'kg',
                'estimated_cost' => 3.00,
                'notes' => 'Minced',
            ];
        }

        if (!empty($attachments)) {
            $recipe->ingredientRelations()->syncWithoutDetaching($attachments);
        }
    }

    /**
     * Seed Pansit Canton recipe ingredients.
     */
    private function seedPansitCanton(): void
    {
        $meal = Meal::updateOrCreate(
            ['name' => 'Pansit Canton'],
            [
                'description' => 'Filipino stir-fried noodles with vegetables and meat',
                'cost' => 120.00,
                'cuisine_type' => 'Filipino',
                'difficulty' => 'medium',
            ]
        );

        $recipe = Recipe::updateOrCreate(
            ['meal_id' => $meal->id],
            [
                'ingredients' => [
                    'Canton Noodles',
                    'Pork Liempo',
                    'Chicken Dressed',
                    'Cabbage',
                    'Carrots',
                    'Onions',
                    'Garlic',
                    'Soy Sauce',
                ],
                'instructions' => "1. Soak noodles in water to soften.\n2. Sauté garlic and onions.\n3. Add pork and chicken, cook until done.\n4. Add vegetables and stir-fry.\n5. Add noodles and soy sauce, toss everything together.\n6. Cook until noodles are tender and serve hot.",
                'prep_time' => 20,
                'cook_time' => 25,
                'servings' => 6,
            ]
        );

        $porkLiempo = Ingredient::where('name', 'Pork Liempo')->first();
        $chickenDressed = Ingredient::where('name', 'Chicken Dressed')->first();
        $cabbage = Ingredient::where('name', 'Cabbage')->first();
        $carrots = Ingredient::where('name', 'Carrots')->first();
        $onions = Ingredient::where('name', 'Onions')->first();
        $garlic = Ingredient::where('name', 'Garlic')->first();

        $attachments = [];
        if ($porkLiempo) {
            $attachments[$porkLiempo->id] = [
                'quantity' => 0.2,
                'unit' => 'kg',
                'estimated_cost' => 60.00,
                'notes' => 'Thinly sliced',
            ];
        }
        if ($chickenDressed) {
            $attachments[$chickenDressed->id] = [
                'quantity' => 0.2,
                'unit' => 'kg',
                'estimated_cost' => 30.00,
                'notes' => 'Deboned and sliced',
            ];
        }
        if ($cabbage) {
            $attachments[$cabbage->id] = [
                'quantity' => 0.15,
                'unit' => 'kg',
                'estimated_cost' => 8.00,
                'notes' => 'Shredded',
            ];
        }
        if ($carrots) {
            $attachments[$carrots->id] = [
                'quantity' => 0.1,
                'unit' => 'kg',
                'estimated_cost' => 5.00,
                'notes' => 'Julienned',
            ];
        }
        if ($onions) {
            $attachments[$onions->id] = [
                'quantity' => 0.05,
                'unit' => 'kg',
                'estimated_cost' => 5.00,
                'notes' => 'Sliced',
            ];
        }
        if ($garlic) {
            $attachments[$garlic->id] = [
                'quantity' => 0.02,
                'unit' => 'kg',
                'estimated_cost' => 3.00,
                'notes' => 'Minced',
            ];
        }

        if (!empty($attachments)) {
            $recipe->ingredientRelations()->syncWithoutDetaching($attachments);
        }
    }
}
