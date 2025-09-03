<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\NutritionalInfo;

class FilipinoMealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Filipino Breakfast Options
        $this->createFilipinoBreakfast();
        
        // Filipino Lunch Options
        $this->createFilipinoLunch();
        
        // Filipino Dinner Options
        $this->createFilipinoDinner();
        
        // Filipino Snack Options
        $this->createFilipinoSnacks();
    }

    private function createFilipinoBreakfast(): void
    {
        // 1. Tapsilog (Beef Tapa with Garlic Rice and Egg)
        $tapsilog = Meal::create([
            'name' => 'Tapsilog',
            'description' => 'Classic Filipino breakfast consisting of beef tapa (marinated beef), sinangag (garlic fried rice), and itlog (fried egg). A hearty and flavorful way to start the day.',
            'calories' => 650,
            'cost' => 120.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'medium',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $tapsilog->id,
            'ingredients' => [
                'For Beef Tapa:',
                '500g beef sirloin, thinly sliced',
                '1/4 cup soy sauce',
                '2 tbsp brown sugar',
                '1 tbsp calamansi juice (or lemon juice)',
                '4 cloves garlic, minced',
                '1 tsp ground black pepper',
                '1 tbsp cooking oil',
                '',
                'For Garlic Rice:',
                '3 cups cooked rice (preferably day-old)',
                '4 cloves garlic, minced',
                '2 tbsp cooking oil',
                'Salt to taste',
                '',
                'For Fried Egg:',
                '2-3 eggs',
                '1 tbsp cooking oil',
                'Salt and pepper to taste'
            ],
            'instructions' => "1. Marinate beef slices in soy sauce, brown sugar, calamansi juice, garlic, and pepper for at least 30 minutes or overnight.\n2. Heat oil in a pan over medium-high heat. Cook marinated beef for 3-4 minutes per side until caramelized.\n3. For garlic rice: Heat oil in a large pan, sauté minced garlic until golden. Add rice and stir-fry for 3-5 minutes. Season with salt.\n4. In another pan, fry eggs sunny-side up or over-easy according to preference.\n5. Serve beef tapa, garlic rice, and fried egg together on one plate.\n6. Traditionally served with sliced tomatoes and cucumber on the side.",
            'prep_time' => 45,
            'cook_time' => 20,
            'servings' => 2,
            'local_alternatives' => [
                'Beef sirloin → Pork shoulder or chicken thighs',
                'Calamansi → Lemon or lime juice',
                'Brown sugar → White sugar or palm sugar'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $tapsilog->id,
            'calories' => 650.00,
            'protein' => 35.00,
            'carbs' => 45.00,
            'fats' => 32.00,
            'fiber' => 2.50,
            'sugar' => 8.00,
            'sodium' => 1200.00,
        ]);

        // 2. Longsilog (Filipino Sausage with Rice and Egg)
        $longsilog = Meal::create([
            'name' => 'Longsilog',
            'description' => 'Popular Filipino breakfast featuring longganisa (Filipino sausage), sinangag (garlic fried rice), and itlog (fried egg). Sweet and savory flavors combined in one satisfying meal.',
            'calories' => 580,
            'cost' => 100.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'easy',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $longsilog->id,
            'ingredients' => [
                'For Longganisa:',
                '6-8 pieces Filipino longganisa sausages',
                '1/4 cup water',
                '1 tbsp cooking oil',
                '',
                'For Garlic Rice:',
                '3 cups cooked rice (day-old preferred)',
                '4 cloves garlic, minced',
                '2 tbsp cooking oil',
                'Salt to taste',
                '',
                'For Fried Egg:',
                '2-3 eggs',
                '1 tbsp cooking oil',
                'Salt and pepper to taste'
            ],
            'instructions' => "1. In a pan, add longganisa with water. Cover and cook over medium heat for 10 minutes.\n2. Remove cover, add oil, and continue cooking until sausages are browned and fully cooked (5-7 minutes).\n3. For garlic rice: Heat oil in a large pan, sauté garlic until fragrant and golden.\n4. Add rice to the pan and stir-fry for 3-5 minutes until heated through. Season with salt.\n5. In another pan, fry eggs to your liking (sunny-side up is traditional).\n6. Serve longganisa, garlic rice, and fried egg together.\n7. Accompany with atchara (pickled papaya) or sliced tomatoes.",
            'prep_time' => 10,
            'cook_time' => 20,
            'servings' => 2,
            'local_alternatives' => [
                'Longganisa → Chorizo or any sweet breakfast sausage',
                'Day-old rice → Freshly cooked rice (slightly less ideal texture)'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $longsilog->id,
            'calories' => 580.00,
            'protein' => 28.00,
            'carbs' => 42.00,
            'fats' => 28.00,
            'fiber' => 2.00,
            'sugar' => 6.00,
            'sodium' => 1100.00,
        ]);

        // 3. Champorado (Chocolate Rice Porridge)
        $champorado = Meal::create([
            'name' => 'Champorado',
            'description' => 'Traditional Filipino chocolate rice porridge made with glutinous rice and cocoa. Often served with tuyo (dried fish) or condensed milk for a perfect sweet and salty combination.',
            'calories' => 420,
            'cost' => 60.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'easy',
            'is_featured' => false,
        ]);

        Recipe::create([
            'meal_id' => $champorado->id,
            'ingredients' => [
                '1 cup glutinous rice (malagkit)',
                '4 cups water',
                '1/2 cup unsweetened cocoa powder',
                '1/2 cup brown sugar (adjust to taste)',
                '1/4 cup condensed milk',
                '1/4 cup evaporated milk',
                'Pinch of salt',
                '',
                'Optional toppings:',
                'Tuyo (dried fish), flaked',
                'Extra condensed milk',
                'Toasted rice crispies'
            ],
            'instructions' => "1. Rinse glutinous rice until water runs clear. In a pot, bring water to boil.\n2. Add rice and cook over medium heat, stirring occasionally, for 15-20 minutes until rice is soft.\n3. In a small bowl, mix cocoa powder with a little water to make a smooth paste.\n4. Add cocoa paste to the rice and stir well. Cook for another 5 minutes.\n5. Add brown sugar and salt. Stir until sugar dissolves completely.\n6. Pour in condensed milk and evaporated milk. Simmer for 2-3 minutes.\n7. Adjust sweetness and consistency as desired (add more milk for creamier texture).\n8. Serve hot in bowls, topped with tuyo flakes or extra condensed milk.",
            'prep_time' => 10,
            'cook_time' => 30,
            'servings' => 4,
            'local_alternatives' => [
                'Glutinous rice → Regular rice (different texture but still good)',
                'Cocoa powder → Tablea (Filipino chocolate tablets)',
                'Brown sugar → White sugar or coconut sugar'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $champorado->id,
            'calories' => 420.00,
            'protein' => 8.00,
            'carbs' => 78.00,
            'fats' => 8.00,
            'fiber' => 4.00,
            'sugar' => 25.00,
            'sodium' => 150.00,
        ]);
    }

    private function createFilipinoLunch(): void
    {
        // 1. Chicken Adobo
        $adobo = Meal::create([
            'name' => 'Chicken Adobo',
            'description' => 'The Philippines\' national dish featuring chicken braised in vinegar, soy sauce, and garlic. This savory and tangy dish is best served with steamed rice.',
            'calories' => 480,
            'cost' => 150.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'easy',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $adobo->id,
            'ingredients' => [
                '1 kg chicken, cut into pieces',
                '1/2 cup soy sauce',
                '1/4 cup white vinegar',
                '1 head garlic, minced',
                '3 bay leaves',
                '1 tsp whole peppercorns',
                '1 tbsp brown sugar',
                '1 cup water',
                '2 tbsp cooking oil',
                'Salt to taste',
                '',
                'For serving:',
                '4 cups steamed rice',
                'Hard-boiled eggs (optional)'
            ],
            'instructions' => "1. In a pot, combine chicken, soy sauce, vinegar, garlic, bay leaves, and peppercorns. Marinate for 30 minutes.\n2. Bring to boil over medium heat without stirring (to prevent vinegar from becoming bitter).\n3. Lower heat and simmer for 20 minutes until chicken is tender.\n4. Remove chicken pieces and set aside. Continue simmering the sauce until reduced by half.\n5. In a separate pan, heat oil and fry chicken pieces until golden brown.\n6. Return chicken to the pot with reduced sauce. Add brown sugar and water if needed.\n7. Simmer for another 5 minutes. Adjust seasoning with salt.\n8. Remove bay leaves before serving.\n9. Serve hot with steamed rice and hard-boiled eggs if desired.",
            'prep_time' => 40,
            'cook_time' => 45,
            'servings' => 4,
            'local_alternatives' => [
                'Chicken → Pork belly or beef chuck',
                'White vinegar → Cane vinegar or apple cider vinegar',
                'Brown sugar → White sugar or palm sugar'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $adobo->id,
            'calories' => 480.00,
            'protein' => 42.00,
            'carbs' => 8.00,
            'fats' => 30.00,
            'fiber' => 1.00,
            'sugar' => 6.00,
            'sodium' => 1500.00,
        ]);

        // 2. Sinigang na Baboy (Pork Sour Soup)
        $sinigang = Meal::create([
            'name' => 'Sinigang na Baboy',
            'description' => 'Classic Filipino sour soup made with pork, tamarind, and fresh vegetables. This comforting soup is perfect for rainy days and is traditionally served with rice.',
            'calories' => 380,
            'cost' => 180.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'medium',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $sinigang->id,
            'ingredients' => [
                '1 kg pork belly or ribs, cut into pieces',
                '1 packet sinigang mix (tamarind base)',
                '1 large onion, quartered',
                '2 tomatoes, quartered',
                '1 radish, sliced',
                '10 pieces string beans, cut into 2-inch pieces',
                '2 pieces taro (gabi), peeled and quartered',
                '1 bunch kangkong (water spinach)',
                '3-4 pieces green chili (siling haba)',
                '6 cups water',
                'Fish sauce to taste',
                'Salt and pepper to taste'
            ],
            'instructions' => "1. In a large pot, boil pork pieces in water for 30 minutes until tender. Skim off any scum.\n2. Add onions and tomatoes. Simmer for 10 minutes until tomatoes are soft.\n3. Add sinigang mix and stir until dissolved. Taste and adjust sourness.\n4. Add radish and taro. Cook for 10 minutes until vegetables start to soften.\n5. Add string beans and simmer for 5 minutes.\n6. Add green chili and kangkong leaves. Cook for 2 minutes until kangkong wilts.\n7. Season with fish sauce, salt, and pepper to taste.\n8. Serve hot with steamed rice.\n9. Traditionally served with bagoong (shrimp paste) on the side.",
            'prep_time' => 20,
            'cook_time' => 60,
            'servings' => 6,
            'local_alternatives' => [
                'Pork belly → Beef short ribs or fish (bangus)',
                'Sinigang mix → Fresh tamarind or green mango',
                'Kangkong → Spinach or bok choy',
                'Taro → Potato or sweet potato'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $sinigang->id,
            'calories' => 380.00,
            'protein' => 25.00,
            'carbs' => 15.00,
            'fats' => 25.00,
            'fiber' => 5.00,
            'sugar' => 8.00,
            'sodium' => 800.00,
        ]);

        // 3. Kare-Kare
        $karekare = Meal::create([
            'name' => 'Kare-Kare',
            'description' => 'Traditional Filipino stew made with oxtail, tripe, and vegetables in a rich peanut sauce. This hearty dish is typically served with bagoong (shrimp paste) and rice.',
            'calories' => 520,
            'cost' => 250.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'hard',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $karekare->id,
            'ingredients' => [
                '1 kg oxtail, cut into pieces',
                '500g beef tripe, cleaned and sliced',
                '1 cup peanut butter',
                '1/4 cup ground toasted rice',
                '1 bundle pechay (bok choy)',
                '1 bundle string beans, cut into 2-inch pieces',
                '4 pieces eggplant, sliced',
                '1 banana heart, sliced (optional)',
                '8 cups water',
                '1 onion, minced',
                '4 cloves garlic, minced',
                '2 tbsp cooking oil',
                'Salt and pepper to taste',
                'Bagoong for serving'
            ],
            'instructions' => "1. In a large pot, boil oxtail and tripe for 2-3 hours until very tender. Reserve the broth.\n2. In a separate pan, heat oil and sauté onions and garlic until fragrant.\n3. Add cooked oxtail and tripe. Stir for 5 minutes.\n4. Pour in 6 cups of reserved broth. Bring to boil.\n5. Mix peanut butter with 1 cup warm broth until smooth. Add to the pot.\n6. Add ground rice and simmer for 15 minutes, stirring occasionally.\n7. Add eggplant and banana heart. Cook for 10 minutes.\n8. Add string beans and cook for 5 minutes.\n9. Finally, add pechay and cook for 2 minutes until wilted.\n10. Season with salt and pepper. Adjust thickness with more broth if needed.\n11. Serve hot with rice and bagoong on the side.",
            'prep_time' => 30,
            'cook_time' => 180,
            'servings' => 6,
            'local_alternatives' => [
                'Oxtail → Beef short ribs or pork hocks',
                'Peanut butter → Ground peanuts',
                'Ground toasted rice → Cornstarch slurry',
                'Banana heart → More eggplant or okra'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $karekare->id,
            'calories' => 520.00,
            'protein' => 35.00,
            'carbs' => 18.00,
            'fats' => 35.00,
            'fiber' => 6.00,
            'sugar' => 8.00,
            'sodium' => 600.00,
        ]);
    }

    private function createFilipinoDinner(): void
    {
        // 1. Crispy Pata
        $crispyPata = Meal::create([
            'name' => 'Crispy Pata',
            'description' => 'Deep-fried pork leg (pata) that is crispy on the outside and tender on the inside. This indulgent dish is perfect for special occasions and family gatherings.',
            'calories' => 680,
            'cost' => 300.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'hard',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $crispyPata->id,
            'ingredients' => [
                '1 whole pork leg (pata), about 2-3 kg',
                '1 tbsp salt',
                '1 tsp ground black pepper',
                '3 bay leaves',
                '1 tbsp whole peppercorns',
                '1 onion, quartered',
                '6 cloves garlic',
                'Oil for deep frying',
                '',
                'For dipping sauce:',
                '1/4 cup soy sauce',
                '2 tbsp vinegar',
                '1 onion, chopped',
                '2 cloves garlic, minced',
                '2 pieces chili, chopped'
            ],
            'instructions' => "1. Clean pork leg thoroughly and make shallow cuts on the skin.\n2. In a large pot, boil pork leg with salt, pepper, bay leaves, peppercorns, onion, and garlic for 1.5-2 hours until tender.\n3. Remove from pot and let cool completely. Pat dry with paper towels.\n4. Refrigerate for at least 2 hours or overnight for best results.\n5. Heat oil in a large, deep pan or fryer to 350°F (175°C).\n6. Carefully lower the pork leg into hot oil. Fry for 15-20 minutes, turning occasionally, until golden brown and crispy.\n7. Remove and drain on paper towels.\n8. For dipping sauce: Mix soy sauce, vinegar, onion, garlic, and chili.\n9. Let crispy pata rest for 5 minutes before chopping into serving pieces.\n10. Serve with the dipping sauce and steamed rice.",
            'prep_time' => 30,
            'cook_time' => 180,
            'servings' => 6,
            'local_alternatives' => [
                'Whole pork leg → Pork shoulder or pork belly',
                'Deep frying → Oven roasting at high temperature',
                'Bay leaves → Thyme or oregano'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $crispyPata->id,
            'calories' => 680.00,
            'protein' => 45.00,
            'carbs' => 2.00,
            'fats' => 55.00,
            'fiber' => 0.50,
            'sugar' => 1.00,
            'sodium' => 1000.00,
        ]);

        // 2. Lechon Kawali
        $lechonKawali = Meal::create([
            'name' => 'Lechon Kawali',
            'description' => 'Deep-fried crispy pork belly that mimics the texture of lechon. The pork is first boiled until tender, then deep-fried for an incredibly crispy skin.',
            'calories' => 590,
            'cost' => 200.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'medium',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $lechonKawali->id,
            'ingredients' => [
                '1 kg pork belly, skin on',
                '1 tbsp salt',
                '1 tsp ground black pepper',
                '3 bay leaves',
                '1 onion, quartered',
                '4 cloves garlic',
                '1 tbsp whole peppercorns',
                'Oil for deep frying',
                '',
                'For lechon sauce:',
                '1/4 cup liver spread',
                '2 tbsp soy sauce',
                '2 tbsp vinegar',
                '1 tbsp brown sugar',
                '1/4 cup water',
                '2 cloves garlic, minced'
            ],
            'instructions' => "1. Score the pork belly skin in a crosshatch pattern, about 1/4 inch deep.\n2. Rub salt and pepper all over the pork belly.\n3. In a large pot, boil pork belly with bay leaves, onion, garlic, and peppercorns for 45 minutes until tender.\n4. Remove pork and let cool completely. Pat dry thoroughly with paper towels.\n5. Refrigerate for at least 1 hour to ensure skin is completely dry.\n6. Heat oil to 350°F (175°C) in a deep pan or fryer.\n7. Carefully fry the pork belly, skin side down first, for 8-10 minutes until golden and crispy.\n8. Flip and fry for another 5-8 minutes until all sides are crispy.\n9. For sauce: Sauté garlic, add liver spread, soy sauce, vinegar, sugar, and water. Simmer until thick.\n10. Let lechon kawali rest for 5 minutes, then chop into serving pieces.\n11. Serve with lechon sauce and rice.",
            'prep_time' => 20,
            'cook_time' => 75,
            'servings' => 4,
            'local_alternatives' => [
                'Pork belly → Pork shoulder with skin',
                'Liver spread → Mashed chicken liver',
                'Deep frying → Air frying or oven roasting'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $lechonKawali->id,
            'calories' => 590.00,
            'protein' => 38.00,
            'carbs' => 3.00,
            'fats' => 47.00,
            'fiber' => 0.50,
            'sugar' => 2.00,
            'sodium' => 950.00,
        ]);

        // 3. Pancit Canton
        $pancitCanton = Meal::create([
            'name' => 'Pancit Canton',
            'description' => 'Popular Filipino stir-fried noodle dish with vegetables, meat, and seafood. This versatile dish is often served during celebrations and family gatherings.',
            'calories' => 420,
            'cost' => 120.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'medium',
            'is_featured' => false,
        ]);

        Recipe::create([
            'meal_id' => $pancitCanton->id,
            'ingredients' => [
                '500g canton noodles (egg noodles)',
                '200g pork, sliced thin',
                '200g shrimp, peeled',
                '100g Chinese sausage, sliced',
                '1 carrot, julienned',
                '1 cup cabbage, shredded',
                '1 cup snow peas',
                '1 onion, sliced',
                '4 cloves garlic, minced',
                '3 tbsp soy sauce',
                '2 tbsp oyster sauce',
                '2 cups chicken broth',
                '3 tbsp cooking oil',
                'Salt and pepper to taste',
                'Green onions for garnish',
                'Lemon wedges for serving'
            ],
            'instructions' => "1. Soak canton noodles in warm water until soft. Drain and set aside.\n2. Heat oil in a large wok or pan over medium-high heat.\n3. Sauté garlic and onion until fragrant.\n4. Add pork and cook until no longer pink. Add Chinese sausage.\n5. Add shrimp and cook until pink and cooked through.\n6. Add carrots and cook for 2 minutes. Add snow peas and cabbage.\n7. Pour in soy sauce, oyster sauce, and chicken broth. Bring to boil.\n8. Add softened noodles and toss everything together.\n9. Cook for 5-8 minutes until noodles absorb most of the liquid.\n10. Season with salt and pepper to taste.\n11. Garnish with green onions and serve with lemon wedges.\n12. Traditionally served with fish sauce and calamansi on the side.",
            'prep_time' => 25,
            'cook_time' => 20,
            'servings' => 6,
            'local_alternatives' => [
                'Canton noodles → Fresh egg noodles or yakisoba',
                'Chinese sausage → Regular sausage or ham',
                'Snow peas → Green beans or bell peppers',
                'Oyster sauce → Soy sauce with a pinch of sugar'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $pancitCanton->id,
            'calories' => 420.00,
            'protein' => 22.00,
            'carbs' => 45.00,
            'fats' => 18.00,
            'fiber' => 4.00,
            'sugar' => 6.00,
            'sodium' => 1200.00,
        ]);
    }

    private function createFilipinoSnacks(): void
    {
        // 1. Turon (Banana Spring Rolls)
        $turon = Meal::create([
            'name' => 'Turon',
            'description' => 'Popular Filipino snack made with sliced bananas and jackfruit strips wrapped in spring roll wrapper, rolled in brown sugar, and deep-fried until golden and crispy.',
            'calories' => 180,
            'cost' => 40.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'easy',
            'is_featured' => true,
        ]);

        Recipe::create([
            'meal_id' => $turon->id,
            'ingredients' => [
                '6 ripe saba bananas, sliced lengthwise',
                '12 pieces spring roll wrapper',
                '1/4 cup brown sugar',
                '12 strips langka (jackfruit), optional',
                'Oil for deep frying',
                '1 egg white for sealing',
                '',
                'Optional coating:',
                'Extra brown sugar for rolling'
            ],
            'instructions' => "1. Prepare all ingredients and have them within reach as assembly is quick.\n2. Place a spring roll wrapper on a flat surface in diamond orientation.\n3. Put 1-2 banana slices and a jackfruit strip (if using) on the lower third of wrapper.\n4. Sprinkle with brown sugar over the filling.\n5. Fold the bottom corner over the filling, then fold in the sides.\n6. Roll tightly towards the top corner. Seal edge with egg white.\n7. Repeat with remaining ingredients.\n8. Heat oil to 350°F (175°C) in a deep pan.\n9. Optional: Roll turon in brown sugar before frying for extra caramelization.\n10. Fry 3-4 pieces at a time for 3-4 minutes until golden brown and crispy.\n11. Remove and drain on paper towels.\n12. Serve immediately while hot and crispy.\n13. Best enjoyed with coffee or as an afternoon snack.",
            'prep_time' => 20,
            'cook_time' => 15,
            'servings' => 4,
            'local_alternatives' => [
                'Saba bananas → Regular bananas or plantains',
                'Langka → Thin apple slices or mango strips',
                'Spring roll wrapper → Lumpia wrapper',
                'Brown sugar → White sugar or palm sugar'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $turon->id,
            'calories' => 180.00,
            'protein' => 3.00,
            'carbs' => 32.00,
            'fats' => 6.00,
            'fiber' => 2.50,
            'sugar' => 18.00,
            'sodium' => 150.00,
        ]);

        // 2. Fresh Lumpia
        $lumpia = Meal::create([
            'name' => 'Fresh Lumpia (Lumpiang Sariwa)',
            'description' => 'Fresh Filipino spring rolls filled with vegetables and sometimes meat, wrapped in a soft crepe-like wrapper and served with a sweet peanut sauce.',
            'calories' => 220,
            'cost' => 60.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'medium',
            'is_featured' => false,
        ]);

        Recipe::create([
            'meal_id' => $lumpia->id,
            'ingredients' => [
                'For wrapper:',
                '1 cup all-purpose flour',
                '2 eggs',
                '1 1/2 cups water',
                '1/2 tsp salt',
                '1 tbsp oil',
                '',
                'For filling:',
                '1 cup cabbage, shredded',
                '1 carrot, julienned',
                '1 cup bean sprouts',
                '1/2 cup green beans, chopped',
                '200g ground pork or chicken (optional)',
                '2 cloves garlic, minced',
                '1 onion, chopped',
                '2 tbsp soy sauce',
                'Salt and pepper to taste',
                '',
                'For sauce:',
                '1/4 cup peanut butter',
                '2 tbsp brown sugar',
                '1/4 cup water',
                '2 tbsp soy sauce',
                '1 clove garlic, minced'
            ],
            'instructions' => "1. For wrapper: Mix flour, eggs, water, salt, and oil until smooth. Let batter rest for 30 minutes.\n2. Heat a non-stick pan and make thin crepes with the batter. Set aside to cool.\n3. For filling: If using meat, cook ground pork/chicken with garlic and onion until done.\n4. Add vegetables and stir-fry for 3-5 minutes until tender-crisp. Season with soy sauce, salt, and pepper.\n5. Let filling cool completely before assembling.\n6. For sauce: Combine all sauce ingredients in a pan and simmer until thick.\n7. To assemble: Place lettuce leaf on wrapper, add 2-3 tbsp filling.\n8. Fold bottom of wrapper over filling, fold sides, then roll tightly.\n9. Cut in half diagonally if desired.\n10. Serve immediately with peanut sauce on the side.\n11. Garnish with chopped peanuts and fried garlic if available.",
            'prep_time' => 45,
            'cook_time' => 30,
            'servings' => 4,
            'local_alternatives' => [
                'Fresh wrapper → Store-bought crepes or tortillas',
                'Ground pork → Tofu or mushrooms for vegetarian version',
                'Peanut butter → Ground peanuts',
                'Bean sprouts → Shredded lettuce or cucumber'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $lumpia->id,
            'calories' => 220.00,
            'protein' => 12.00,
            'carbs' => 25.00,
            'fats' => 10.00,
            'fiber' => 4.00,
            'sugar' => 8.00,
            'sodium' => 600.00,
        ]);

        // 3. Puto (Steamed Rice Cakes)
        $puto = Meal::create([
            'name' => 'Puto',
            'description' => 'Traditional Filipino steamed rice cakes that are soft, fluffy, and slightly sweet. Often topped with cheese or salted egg and perfect for merienda (afternoon snack).',
            'calories' => 160,
            'cost' => 30.00,
            'cuisine_type' => 'Filipino',
            'difficulty' => 'easy',
            'is_featured' => false,
        ]);

        Recipe::create([
            'meal_id' => $puto->id,
            'ingredients' => [
                '2 cups rice flour',
                '1/2 cup sugar',
                '1 tbsp baking powder',
                '1/2 tsp salt',
                '1 1/4 cups coconut milk',
                '1/4 cup water',
                '2 tbsp melted butter',
                'Food coloring (optional)',
                '',
                'For topping:',
                '1/2 cup grated cheese',
                '2 salted eggs, sliced (optional)',
                'Banana leaves for lining (optional)'
            ],
            'instructions' => "1. In a large bowl, mix rice flour, sugar, baking powder, and salt.\n2. In another bowl, combine coconut milk, water, and melted butter.\n3. Gradually add wet ingredients to dry ingredients, mixing until smooth.\n4. Add food coloring if desired for different colors.\n5. Prepare steamer and line puto molds with paper liners or banana leaves.\n6. Fill molds 3/4 full with batter.\n7. Top each with grated cheese or salted egg slice.\n8. Steam for 15-20 minutes or until a toothpick inserted comes out clean.\n9. Let cool for 5 minutes before removing from molds.\n10. Serve warm or at room temperature.\n11. Store in airtight container for up to 3 days.\n12. Best paired with dinuguan (blood stew) or enjoyed with coffee.",
            'prep_time' => 15,
            'cook_time' => 20,
            'servings' => 12,
            'local_alternatives' => [
                'Rice flour → All-purpose flour (different texture)',
                'Coconut milk → Regular milk with 1 tbsp coconut oil',
                'Salted egg → More cheese or skip topping',
                'Banana leaves → Cupcake liners'
            ]
        ]);

        NutritionalInfo::create([
            'meal_id' => $puto->id,
            'calories' => 160.00,
            'protein' => 4.00,
            'carbs' => 28.00,
            'fats' => 4.50,
            'fiber' => 1.00,
            'sugar' => 12.00,
            'sodium' => 200.00,
        ]);
    }
}