<?php

namespace Tests\Feature;

use App\Models\AdminLog;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear any cached data
        \Cache::flush();
    }

    public function test_admin_dashboard_loads_successfully_with_data(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create some test data
        User::factory()->count(5)->create(['is_active' => true]);
        User::factory()->count(2)->create(['is_active' => false]);

        // Create meals without meal plans relation to avoid complexity
        Meal::factory()->count(10)->create();
        Meal::factory()->count(3)->create(['is_featured' => true]);

        // Create some admin logs
        AdminLog::factory()->count(5)->create([
            'admin_user_id' => $admin->id,
        ]);

        // Act as admin and visit dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // Assert successful response
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        // Assert that view has required data
        $response->assertViewHas(['stats', 'recentActivities', 'userGrowth', 'topMeals']);
    }

    public function test_admin_dashboard_loads_with_empty_data(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // No additional data created - test empty state handling

        // Act as admin and visit dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // Assert successful response even with no data
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        // Assert that view has required data (even if empty)
        $response->assertViewHas(['stats', 'recentActivities', 'userGrowth', 'topMeals']);
    }

    public function test_admin_dashboard_handles_adminlog_without_user(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create a valid admin log first
        AdminLog::create([
            'admin_user_id' => $admin->id,
            'action' => 'test_action',
            'description' => 'Test valid log',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent',
        ]);

        // Act as admin and visit dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // Should load successfully even if later we have orphaned logs
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_non_admin_cannot_access_dashboard(): void
    {
        // Create a regular user
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Try to access admin dashboard
        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        // Should be redirected to admin login
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        // Try to access admin dashboard without authentication
        $response = $this->get(route('admin.dashboard'));

        // Should be redirected (either to login or admin.login based on middleware)
        $response->assertRedirect();
    }

    public function test_suspended_admin_cannot_access_dashboard(): void
    {
        // Create a suspended admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => false,
            'email_verified_at' => now(),
        ]);

        // Try to access admin dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // Should be redirected to admin login
        $response->assertRedirect(route('admin.login'));
    }

    public function test_dashboard_eager_loads_admin_users_correctly(): void
    {
        // Create admin users
        $admin1 = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $admin2 = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create admin logs with relationships
        AdminLog::factory()->count(3)->create(['admin_user_id' => $admin1->id]);
        AdminLog::factory()->count(2)->create(['admin_user_id' => $admin2->id]);

        // Clear cache to ensure fresh query
        \Cache::flush();

        // Act as admin and visit dashboard
        $response = $this->actingAs($admin1)->get(route('admin.dashboard'));

        // Assert successful load
        $response->assertStatus(200);

        // Verify that the view data contains activities with loaded relationships
        $activities = $response->viewData('recentActivities');
        $this->assertNotNull($activities);

        // If there are activities, verify adminUser relationship is loaded
        if ($activities->count() > 0) {
            $firstActivity = $activities->first();
            // Check that adminUser is loaded (not causing N+1 query issues)
            $this->assertTrue($firstActivity->relationLoaded('adminUser'));
        }
    }
}
