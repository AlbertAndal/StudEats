<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login page loads successfully.
     */
    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.admin-login');
        $response->assertSee('Admin Sign In');
    }

    /**
     * Test admin can login with valid credentials.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin');
        $response->assertSessionHas('success');
    }

    /**
     * Test super admin can login with valid credentials.
     */
    public function test_super_admin_can_login_with_valid_credentials(): void
    {
        // Create super admin user
        $superAdmin = User::factory()->create([
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'superadmin@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin');
        $response->assertSessionHas('success');
    }

    /**
     * Test admin cannot login with invalid password.
     */
    public function test_admin_cannot_login_with_invalid_password(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test regular user cannot access admin login.
     */
    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
            'role' => 'user', // Regular user
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'user@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
        $response->assertSessionHas('error');
    }

    /**
     * Test suspended admin cannot login.
     */
    public function test_suspended_admin_cannot_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => false, // Suspended
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
        $response->assertSessionHas('error', 'Your admin account has been suspended.');
    }

    /**
     * Test admin login is rate limited.
     */
    public function test_admin_login_is_rate_limited(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Make 6 failed login attempts
        for ($i = 0; $i < 6; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@test.com',
                'password' => 'wrongpassword',
            ]);
        }

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many', $response->getSession()->get('errors')->first('email'));
    }

    /**
     * Test admin can access admin dashboard after login.
     */
    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    /**
     * Test admin logout works correctly.
     */
    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
        $response->assertSessionHas('success');
    }

    /**
     * Test password hashing works correctly.
     */
    public function test_admin_password_is_hashed(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => 'password123', // Will be auto-hashed
            'role' => 'admin',
        ]);

        $this->assertNotEquals('password123', $admin->password);
        $this->assertTrue(Hash::check('password123', $admin->password));
    }

    /**
     * Test isAdmin method works correctly.
     */
    public function test_is_admin_method_works_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($superAdmin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test isSuperAdmin method works correctly.
     */
    public function test_is_super_admin_method_works_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertFalse($admin->isSuperAdmin());
        $this->assertTrue($superAdmin->isSuperAdmin());
        $this->assertFalse($user->isSuperAdmin());
    }
}
