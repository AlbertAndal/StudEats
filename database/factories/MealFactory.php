<?php

namespace Database\Factories;

use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cuisineTypes = ['Filipino', 'Asian', 'Western', 'Mediterranean', 'Mexican', 'Italian'];
        $difficulties = ['easy', 'medium', 'hard']; // lowercase to match enum in migration

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'calories' => fake()->numberBetween(200, 800),
            'cost' => fake()->randomFloat(2, 50, 500),
            'cuisine_type' => fake()->randomElement($cuisineTypes),
            'difficulty' => fake()->randomElement($difficulties),
            'image_path' => null,
            'is_featured' => fake()->boolean(20), // 20% chance of being featured
        ];
    }

    /**
     * Indicate that the meal is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
