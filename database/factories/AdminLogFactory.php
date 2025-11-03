<?php

namespace Database\Factories;

use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminLog>
 */
class AdminLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdminLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = [
            'user_created',
            'user_updated',
            'user_suspended',
            'user_activated',
            'recipe_created',
            'recipe_updated',
            'recipe_deleted',
            'settings_updated',
        ];

        return [
            'admin_user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'description' => fake()->sentence(),
            'target_type' => null,
            'target_id' => null,
            'metadata' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
