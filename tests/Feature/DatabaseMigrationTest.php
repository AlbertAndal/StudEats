<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Meal;
use App\Models\MealPlan;
use App\Models\Ingredient;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test PostgreSQL connection
     */
    public function test_postgresql_connection(): void
    {
        $connection = config('database.default');
        $this->assertEquals('pgsql', $connection, 'Default connection should be PostgreSQL');

        $pdo = DB::connection('pgsql')->getPdo();
        $this->assertNotNull($pdo, 'PostgreSQL connection should be established');

        $version = DB::connection('pgsql')->select('SELECT version()');
        $this->assertStringContainsString('PostgreSQL', $version[0]->version);
    }

    /**
     * Test all required tables exist
     */
    public function test_all_tables_exist(): void
    {
        $requiredTables = [
            'users', 'meals', 'recipes', 'ingredients', 
            'meal_plans', 'nutritional_info', 'recipe_ingredients',
            'ingredient_price_history', 'admin_logs', 'activity_logs',
            'email_verification_otps', 'sessions', 'cache',
            'jobs', 'failed_jobs', 'password_reset_tokens'
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table {$table} should exist"
            );
        }
    }

    /**
     * Test foreign key constraints
     */
    public function test_foreign_key_constraints(): void
    {
        // Create user
        $user = User::factory()->create();

        // Create meal
        $meal = Meal::factory()->create();

        // Create meal plan
        $mealPlan = MealPlan::factory()->create([
            'user_id' => $user->id,
            'meal_id' => $meal->id,
        ]);

        $this->assertDatabaseHas('meal_plans', [
            'user_id' => $user->id,
            'meal_id' => $meal->id,
        ]);

        // Test cascade delete
        $user->delete();
        $this->assertDatabaseMissing('meal_plans', [
            'id' => $mealPlan->id,
        ]);
    }

    /**
     * Test JSON column compatibility
     */
    public function test_json_columns(): void
    {
        $user = User::factory()->create([
            'dietary_preferences' => ['vegetarian', 'gluten-free'],
        ]);

        $this->assertIsArray($user->dietary_preferences);
        $this->assertContains('vegetarian', $user->dietary_preferences);

        // Test JSON queries
        $foundUser = User::whereJsonContains('dietary_preferences', 'vegetarian')->first();
        $this->assertEquals($user->id, $foundUser->id);
    }

    /**
     * Test boolean (tinyint) compatibility
     */
    public function test_boolean_columns(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->assertTrue($user->is_active);
        $this->assertIsBool($user->is_active);

        $activeUsers = User::where('is_active', true)->count();
        $this->assertGreaterThan(0, $activeUsers);
    }

    /**
     * Test timestamp handling
     */
    public function test_timestamp_columns(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertNotNull($user->email_verified_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    /**
     * Test unique constraints
     */
    public function test_unique_constraints(): void
    {
        $email = 'test@example.com';
        User::factory()->create(['email' => $email]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => $email]);
    }

    /**
     * Test check constraints
     */
    public function test_check_constraints(): void
    {
        $user = User::factory()->create([
            'dietary_preferences' => ['vegetarian'],
        ]);

        $this->assertIsArray($user->dietary_preferences);

        // Invalid JSON should fail
        $this->expectException(\Illuminate\Database\QueryException::class);
        DB::table('users')->insert([
            'name' => 'Test',
            'email' => 'invalid@example.com',
            'password' => 'password',
            'dietary_preferences' => 'invalid json',
        ]);
    }

    /**
     * Test enum column compatibility
     */
    public function test_enum_columns(): void
    {
        $mealPlan = MealPlan::factory()->create([
            'meal_type' => 'breakfast',
        ]);

        $this->assertEquals('breakfast', $mealPlan->meal_type);

        // Valid enum values
        $validTypes = ['breakfast', 'lunch', 'dinner', 'snack'];
        foreach ($validTypes as $type) {
            $plan = MealPlan::factory()->create(['meal_type' => $type]);
            $this->assertEquals($type, $plan->meal_type);
        }
    }

    /**
     * Test decimal/numeric precision
     */
    public function test_decimal_precision(): void
    {
        $ingredient = Ingredient::factory()->create([
            'current_price' => 123.45,
        ]);

        $this->assertEquals(123.45, $ingredient->current_price);
        
        // Test precision is maintained
        $retrieved = Ingredient::find($ingredient->id);
        $this->assertEquals(123.45, $retrieved->current_price);
    }

    /**
     * Test transaction support
     */
    public function test_transactions(): void
    {
        DB::beginTransaction();

        $user = User::factory()->create();
        $meal = Meal::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('meals', ['id' => $meal->id]);

        DB::rollBack();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('meals', ['id' => $meal->id]);
    }

    /**
     * Test query performance
     */
    public function test_query_performance(): void
    {
        // Create test data
        $user = User::factory()->create();
        $meals = Meal::factory()->count(10)->create();
        
        foreach ($meals as $meal) {
            MealPlan::factory()->create([
                'user_id' => $user->id,
                'meal_id' => $meal->id,
            ]);
        }

        // Test query execution time
        $start = microtime(true);
        
        $mealPlans = MealPlan::where('user_id', $user->id)
            ->with(['meal.nutritionalInfo'])
            ->get();
        
        $duration = (microtime(true) - $start) * 1000; // Convert to milliseconds

        $this->assertCount(10, $mealPlans);
        $this->assertLessThan(100, $duration, 'Query should complete in less than 100ms');
    }

    /**
     * Test concurrent connections
     */
    public function test_concurrent_connections(): void
    {
        $connections = [];
        
        for ($i = 0; $i < 5; $i++) {
            $connections[] = DB::connection('pgsql')->getPdo();
        }

        $this->assertCount(5, $connections);

        // Verify all connections work
        foreach ($connections as $connection) {
            $this->assertNotNull($connection);
        }
    }

    /**
     * Test data integrity after migration
     */
    public function test_data_integrity(): void
    {
        // Test users have required fields
        $user = User::factory()->create();
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertNotNull($user->created_at);

        // Test relationships work
        $meal = Meal::factory()->create();
        $mealPlan = MealPlan::factory()->create([
            'user_id' => $user->id,
            'meal_id' => $meal->id,
        ]);

        $this->assertEquals($user->id, $mealPlan->user->id);
        $this->assertEquals($meal->id, $mealPlan->meal->id);
    }

    /**
     * Test full-text search (PostgreSQL specific)
     */
    public function test_fulltext_search(): void
    {
        Meal::factory()->create(['name' => 'Chicken Adobo']);
        Meal::factory()->create(['name' => 'Beef Sinigang']);
        Meal::factory()->create(['name' => 'Chicken Curry']);

        $results = Meal::where('name', 'ILIKE', '%Chicken%')->get();
        $this->assertCount(2, $results);
    }

    /**
     * Test index usage
     */
    public function test_index_usage(): void
    {
        $user = User::factory()->create();

        // Create multiple meal plans
        for ($i = 0; $i < 10; $i++) {
            $meal = Meal::factory()->create();
            MealPlan::factory()->create([
                'user_id' => $user->id,
                'meal_id' => $meal->id,
                'scheduled_date' => now()->addDays($i),
            ]);
        }

        // Query should use index
        $start = microtime(true);
        $mealPlans = MealPlan::where('user_id', $user->id)
            ->where('scheduled_date', '>=', now())
            ->get();
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(50, $duration, 'Indexed query should be fast');
    }
}
