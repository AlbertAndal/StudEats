<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PdriReferenceSeeder extends Seeder
{
    /**
     * Seed PDRI reference values based on Philippine Dietary Reference Intake
     */
    public function run(): void
    {
        // Check if data already exists
        if (DB::table('pdri_references')->count() > 0) {
            $this->command->info('PDRI reference data already exists. Skipping seeding.');
            return;
        }

        $pdriData = [
            // MALES - Young Adults (19-29 years)
            ['gender' => 'male', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'sedentary', 'energy_kcal' => 2100, 'protein_g' => 56, 'carbohydrates_g' => 289, 'total_fat_g' => 58, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'low_active', 'energy_kcal' => 2400, 'protein_g' => 56, 'carbohydrates_g' => 330, 'total_fat_g' => 67, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'active', 'energy_kcal' => 2700, 'protein_g' => 56, 'carbohydrates_g' => 371, 'total_fat_g' => 75, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'very_active', 'energy_kcal' => 3000, 'protein_g' => 56, 'carbohydrates_g' => 413, 'total_fat_g' => 83, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            
            // MALES - Adults (30-59 years)
            ['gender' => 'male', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'sedentary', 'energy_kcal' => 2050, 'protein_g' => 56, 'carbohydrates_g' => 282, 'total_fat_g' => 57, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'low_active', 'energy_kcal' => 2350, 'protein_g' => 56, 'carbohydrates_g' => 323, 'total_fat_g' => 65, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'active', 'energy_kcal' => 2650, 'protein_g' => 56, 'carbohydrates_g' => 364, 'total_fat_g' => 74, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'very_active', 'energy_kcal' => 2950, 'protein_g' => 56, 'carbohydrates_g' => 406, 'total_fat_g' => 82, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            
            // MALES - Elderly (60+ years)
            ['gender' => 'male', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'sedentary', 'energy_kcal' => 1850, 'protein_g' => 56, 'carbohydrates_g' => 254, 'total_fat_g' => 51, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'low_active', 'energy_kcal' => 2100, 'protein_g' => 56, 'carbohydrates_g' => 289, 'total_fat_g' => 58, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'active', 'energy_kcal' => 2350, 'protein_g' => 56, 'carbohydrates_g' => 323, 'total_fat_g' => 65, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'male', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'very_active', 'energy_kcal' => 2600, 'protein_g' => 56, 'carbohydrates_g' => 358, 'total_fat_g' => 72, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            
            // FEMALES - Young Adults (19-29 years)
            ['gender' => 'female', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'sedentary', 'energy_kcal' => 1750, 'protein_g' => 46, 'carbohydrates_g' => 241, 'total_fat_g' => 49, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'low_active', 'energy_kcal' => 2000, 'protein_g' => 46, 'carbohydrates_g' => 275, 'total_fat_g' => 56, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'active', 'energy_kcal' => 2250, 'protein_g' => 46, 'carbohydrates_g' => 309, 'total_fat_g' => 63, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 19, 'age_max' => 29, 'activity_level' => 'very_active', 'energy_kcal' => 2500, 'protein_g' => 46, 'carbohydrates_g' => 344, 'total_fat_g' => 69, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            
            // FEMALES - Adults (30-59 years)
            ['gender' => 'female', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'sedentary', 'energy_kcal' => 1700, 'protein_g' => 46, 'carbohydrates_g' => 234, 'total_fat_g' => 47, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'low_active', 'energy_kcal' => 1950, 'protein_g' => 46, 'carbohydrates_g' => 268, 'total_fat_g' => 54, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'active', 'energy_kcal' => 2200, 'protein_g' => 46, 'carbohydrates_g' => 303, 'total_fat_g' => 61, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 30, 'age_max' => 59, 'activity_level' => 'very_active', 'energy_kcal' => 2450, 'protein_g' => 46, 'carbohydrates_g' => 337, 'total_fat_g' => 68, 'fiber_g' => 25, 'sodium_mg' => 2000, 'sugar_g' => 50],
            
            // FEMALES - Elderly (60+ years)
            ['gender' => 'female', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'sedentary', 'energy_kcal' => 1550, 'protein_g' => 46, 'carbohydrates_g' => 213, 'total_fat_g' => 43, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'low_active', 'energy_kcal' => 1750, 'protein_g' => 46, 'carbohydrates_g' => 241, 'total_fat_g' => 49, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'active', 'energy_kcal' => 1950, 'protein_g' => 46, 'carbohydrates_g' => 268, 'total_fat_g' => 54, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
            ['gender' => 'female', 'age_min' => 60, 'age_max' => 120, 'activity_level' => 'very_active', 'energy_kcal' => 2150, 'protein_g' => 46, 'carbohydrates_g' => 296, 'total_fat_g' => 60, 'fiber_g' => 20, 'sodium_mg' => 2000, 'sugar_g' => 50],
        ];

        DB::table('pdri_references')->insert($pdriData);
    }
}
