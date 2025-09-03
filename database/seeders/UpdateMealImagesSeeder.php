<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meal;

class UpdateMealImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * High-quality Filipino meal images for featured meals.
     * These are placeholder URLs that should be replaced with actual images.
     */
    public function run()
    {
        $mealImages = [
            'Chicken Adobo' => 'meals/placeholder-chicken-adobo.jpg',
            'Sinigang na Baboy' => 'meals/placeholder-sinigang-na-baboy.jpg',
            'Kare-Kare' => 'meals/placeholder-kare-kare.jpg',
            'Crispy Pata' => 'meals/placeholder-crispy-pata.jpg',
            'Lechon Kawali' => 'meals/placeholder-lechon-kawali.jpg',
            'Turon' => 'meals/placeholder-turon.jpg',
        ];

        foreach ($mealImages as $mealName => $imagePath) {
            $meal = Meal::where('name', $mealName)->first();
            if ($meal && !$meal->image_path) {
                $meal->update(['image_path' => $imagePath]);
                $this->command->info("Updated image for {$mealName}");
            }
        }

        $this->command->info('Meal images updated successfully!');
        $this->command->warn('Note: Placeholder image files need to be added to storage/app/public/meals/');
        $this->command->warn('Recommended image specifications:');
        $this->command->warn('- Format: JPG or PNG');
        $this->command->warn('- Size: 800x600 pixels (4:3 aspect ratio)');
        $this->command->warn('- File size: < 500KB');
        $this->command->warn('- High quality, well-lit food photography');
    }
}