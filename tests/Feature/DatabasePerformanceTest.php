<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Meal;
use App\Models\MealPlan;

class DatabasePerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->createTestData();
    }

    private function createTestData(): void
    {
        // Create 100 users
        $users = User::factory()->count(100)->create();

        // Create 50 meals
        $meals = Meal::factory()->count(50)->create();

        // Create meal plans for each user
        foreach ($users->take(10) as $user) {
            foreach ($meals->take(5) as $meal) {
                MealPlan::factory()->create([
                    'user_id' => $user->id,
                    'meal_id' => $meal->id,
                ]);
            }
        }
    }

    /**
     * Test query response time for user login
     */
    public function test_user_login_performance(): void
    {
        $email = User::first()->email;

        $start = microtime(true);
        $user = User::where('email', $email)->first();
        $duration = (microtime(true) - $start) * 1000;

        $this->assertNotNull($user);
        $this->assertLessThan(50, $duration, 'User login should take less than 50ms');
    }

    /**
     * Test query response time for meal plan retrieval
     */
    public function test_meal_plan_load_performance(): void
    {
        $user = User::first();
        $date = now()->format('Y-m-d');

        $start = microtime(true);
        $mealPlans = MealPlan::where('user_id', $user->id)
            ->where('scheduled_date', $date)
            ->with(['meal.nutritionalInfo'])
            ->get();
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(100, $duration, 'Meal plan load should take less than 100ms');
    }

    /**
     * Test N+1 query prevention
     */
    public function test_no_n_plus_one_queries(): void
    {
        $user = User::first();

        DB::enableQueryLog();

        $mealPlans = MealPlan::where('user_id', $user->id)
            ->with(['meal.nutritionalInfo', 'meal.recipe'])
            ->limit(10)
            ->get();

        // Access relationships
        foreach ($mealPlans as $plan) {
            $name = $plan->meal->name;
            $calories = $plan->meal->nutritionalInfo->calories ?? 0;
        }

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Should be approximately 3-4 queries:
        // 1. meal_plans
        // 2. meals
        // 3. nutritional_info
        // 4. recipes
        $this->assertLessThan(10, count($queries), 'Should not have N+1 query problem');
    }

    /**
     * Test bulk insert performance
     */
    public function test_bulk_insert_performance(): void
    {
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'name' => "Test Meal {$i}",
                'description' => 'Test description',
                'calories' => rand(200, 800),
                'cost' => rand(50, 300),
                'difficulty' => 'easy',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $start = microtime(true);
        DB::table('meals')->insert($data);
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(500, $duration, 'Bulk insert should take less than 500ms');
    }

    /**
     * Test pagination performance
     */
    public function test_pagination_performance(): void
    {
        $start = microtime(true);
        $meals = Meal::paginate(20);
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(100, $duration, 'Pagination should take less than 100ms');
        $this->assertNotNull($meals->total());
    }

    /**
     * Test search performance
     */
    public function test_search_performance(): void
    {
        $start = microtime(true);
        $meals = Meal::where('name', 'ILIKE', '%Chicken%')
            ->orWhere('description', 'ILIKE', '%Chicken%')
            ->limit(20)
            ->get();
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(150, $duration, 'Search should take less than 150ms');
    }

    /**
     * Test complex join performance
     */
    public function test_complex_join_performance(): void
    {
        $start = microtime(true);
        
        $results = DB::table('meal_plans')
            ->join('meals', 'meal_plans.meal_id', '=', 'meals.id')
            ->join('users', 'meal_plans.user_id', '=', 'users.id')
            ->select('users.name', 'meals.name as meal_name', 'meal_plans.scheduled_date')
            ->limit(50)
            ->get();
        
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(200, $duration, 'Complex join should take less than 200ms');
    }

    /**
     * Test aggregation performance
     */
    public function test_aggregation_performance(): void
    {
        $user = User::first();

        $start = microtime(true);
        
        $stats = MealPlan::where('user_id', $user->id)
            ->join('meals', 'meal_plans.meal_id', '=', 'meals.id')
            ->selectRaw('
                COUNT(*) as total_meals,
                SUM(meals.calories) as total_calories,
                AVG(meals.cost) as avg_cost
            ')
            ->first();
        
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(100, $duration, 'Aggregation should take less than 100ms');
    }

    /**
     * Test concurrent query handling
     */
    public function test_concurrent_queries(): void
    {
        $queries = [];
        
        for ($i = 0; $i < 10; $i++) {
            $queries[] = function() {
                return User::inRandomOrder()->first();
            };
        }

        $start = microtime(true);
        
        foreach ($queries as $query) {
            $query();
        }
        
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(500, $duration, 'Concurrent queries should complete in less than 500ms');
    }

    /**
     * Test transaction performance
     */
    public function test_transaction_performance(): void
    {
        $start = microtime(true);
        
        DB::transaction(function () {
            $user = User::factory()->create();
            $meal = Meal::factory()->create();
            
            for ($i = 0; $i < 10; $i++) {
                MealPlan::factory()->create([
                    'user_id' => $user->id,
                    'meal_id' => $meal->id,
                ]);
            }
        });
        
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(300, $duration, 'Transaction should complete in less than 300ms');
    }

    /**
     * Load test: simulate multiple users accessing data
     */
    public function test_load_simulation(): void
    {
        $users = User::limit(10)->get();
        $totalTime = 0;
        $queries = 0;

        foreach ($users as $user) {
            $start = microtime(true);
            
            // Simulate typical user request
            $mealPlans = MealPlan::where('user_id', $user->id)
                ->with(['meal.nutritionalInfo'])
                ->limit(5)
                ->get();
            
            $totalTime += (microtime(true) - $start) * 1000;
            $queries++;
        }

        $avgTime = $totalTime / $queries;

        $this->assertLessThan(100, $avgTime, 'Average query time should be less than 100ms');
    }
}
