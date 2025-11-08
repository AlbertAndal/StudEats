<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdriReference extends Model
{
    protected $fillable = [
        'gender',
        'age_min',
        'age_max',
        'activity_level',
        'energy_kcal',
        'protein_g',
        'carbohydrates_g',
        'total_fat_g',
        'fiber_g',
        'sodium_mg',
        'sugar_g',
    ];

    /**
     * Get PDRI recommendations for a user
     */
    public static function getRecommendationsForUser($user)
    {
        $age = $user->age ?? 25; // Default to 25 if age not set
        $gender = strtolower($user->gender ?? 'male');
        $activityLevel = $user->activity_level ?? 'low_active';

        return self::where('gender', $gender)
            ->where('age_min', '<=', $age)
            ->where('age_max', '>=', $age)
            ->where('activity_level', $activityLevel)
            ->first();
    }

    /**
     * Check if a nutrient amount is high (>30% of daily value per meal)
     */
    public static function isNutrientHigh($nutrientAmount, $dailyValue, $mealsPerDay = 3)
    {
        $perMealLimit = ($dailyValue / $mealsPerDay) * 1.3; // 30% above average
        return $nutrientAmount >= $perMealLimit;
    }

    /**
     * Check if a nutrient amount is dangerous (>50% of daily value per meal)
     */
    public static function isNutrientDangerous($nutrientAmount, $dailyValue, $mealsPerDay = 3)
    {
        $perMealLimit = ($dailyValue / $mealsPerDay) * 1.5; // 50% above average
        return $nutrientAmount >= $perMealLimit;
    }

    /**
     * Get nutrient warnings for a meal
     */
    public static function getNutrientWarnings($meal, $user)
    {
        $pdri = self::getRecommendationsForUser($user);
        if (!$pdri || !$meal->nutritionalInfo) {
            return [];
        }

        $warnings = [];
        $nutritionalInfo = $meal->nutritionalInfo;

        // Check Fat (assuming 3 meals per day)
        if (isset($nutritionalInfo->fat) && $nutritionalInfo->fat > 0) {
            if (self::isNutrientDangerous($nutritionalInfo->fat, $pdri->total_fat_g)) {
                $warnings[] = ['type' => 'danger', 'nutrient' => 'Fat', 'message' => 'Very High in Fat'];
            } elseif (self::isNutrientHigh($nutritionalInfo->fat, $pdri->total_fat_g)) {
                $warnings[] = ['type' => 'warning', 'nutrient' => 'Fat', 'message' => 'High in Fat'];
            }
        }

        // Check Sodium
        if (isset($nutritionalInfo->sodium) && $nutritionalInfo->sodium > 0 && $pdri->sodium_mg) {
            if (self::isNutrientDangerous($nutritionalInfo->sodium, $pdri->sodium_mg)) {
                $warnings[] = ['type' => 'danger', 'nutrient' => 'Sodium', 'message' => 'Very High in Sodium'];
            } elseif (self::isNutrientHigh($nutritionalInfo->sodium, $pdri->sodium_mg)) {
                $warnings[] = ['type' => 'warning', 'nutrient' => 'Sodium', 'message' => 'High in Sodium'];
            }
        }

        // Check Sugar
        if (isset($nutritionalInfo->sugar) && $nutritionalInfo->sugar > 0 && $pdri->sugar_g) {
            if (self::isNutrientDangerous($nutritionalInfo->sugar, $pdri->sugar_g)) {
                $warnings[] = ['type' => 'danger', 'nutrient' => 'Sugar', 'message' => 'Very High in Sugar'];
            } elseif (self::isNutrientHigh($nutritionalInfo->sugar, $pdri->sugar_g)) {
                $warnings[] = ['type' => 'warning', 'nutrient' => 'Sugar', 'message' => 'High in Sugar'];
            }
        }

        // Check Carbohydrates
        if (isset($nutritionalInfo->carbohydrates) && $nutritionalInfo->carbohydrates > 0) {
            if (self::isNutrientDangerous($nutritionalInfo->carbohydrates, $pdri->carbohydrates_g)) {
                $warnings[] = ['type' => 'danger', 'nutrient' => 'Carbohydrates', 'message' => 'Very High in Carbs'];
            } elseif (self::isNutrientHigh($nutritionalInfo->carbohydrates, $pdri->carbohydrates_g)) {
                $warnings[] = ['type' => 'warning', 'nutrient' => 'Carbohydrates', 'message' => 'High in Carbs'];
            }
        }

        return $warnings;
    }
}
