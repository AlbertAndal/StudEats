<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        Storage::fake('public');
    }

    /** @test */
    public function user_can_upload_profile_photo()
    {
        $file = UploadedFile::fake()->image('profile.jpg', 800, 600);

        $response = $this->actingAs($this->user)
            ->post('/profile/photo/upload', [
                'photo' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $responseData = $response->json();
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('temp_path', $responseData['data']);
        $this->assertArrayHasKey('preview_url', $responseData['data']);
    }

    /** @test */
    public function user_can_crop_and_save_profile_photo()
    {
        // First upload a photo
        $file = UploadedFile::fake()->image('profile.jpg', 800, 600);
        
        $uploadResponse = $this->actingAs($this->user)
            ->post('/profile/photo/upload', [
                'photo' => $file,
            ]);

        $uploadData = $uploadResponse->json('data');
        
        // Then crop and save it
        $response = $this->actingAs($this->user)
            ->post('/profile/photo/crop', [
                'temp_path' => $uploadData['temp_path'],
                'x' => 100,
                'y' => 100,
                'width' => 300,
                'height' => 300,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // Check if user's profile photo was updated
        $this->user->refresh();
        $this->assertNotNull($this->user->profile_photo);
        $this->assertTrue($this->user->hasProfilePhoto());
    }

    /** @test */
    public function user_can_delete_profile_photo()
    {
        // Set a profile photo for the user
        $this->user->update(['profile_photo' => 'profile_photos/test.jpg']);
        
        $response = $this->actingAs($this->user)
            ->delete('/profile/photo');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // Check if user's profile photo was removed
        $this->user->refresh();
        $this->assertNull($this->user->profile_photo);
        $this->assertFalse($this->user->hasProfilePhoto());
    }

    /** @test */
    public function upload_validates_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($this->user)
            ->post('/profile/photo/upload', [
                'photo' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /** @test */
    public function upload_validates_file_size()
    {
        $file = UploadedFile::fake()->image('large.jpg')->size(6000); // 6MB

        $response = $this->actingAs($this->user)
            ->post('/profile/photo/upload', [
                'photo' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /** @test */
    public function guest_cannot_upload_photos()
    {
        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->post('/profile/photo/upload', [
            'photo' => $file,
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_model_helper_methods_work_correctly()
    {
        // Test when no profile photo
        $this->assertFalse($this->user->hasProfilePhoto());
        $this->assertNull($this->user->getProfilePhotoUrlAttribute());
        $this->assertStringContainsString('ui-avatars.com', $this->user->getAvatarUrl());

        // Test when profile photo exists
        $this->user->update(['profile_photo' => 'profile_photos/test.jpg']);
        $this->assertTrue($this->user->hasProfilePhoto());
        $this->assertStringContainsString('storage/profile_photos/test.jpg', $this->user->getProfilePhotoUrlAttribute());
        $this->assertStringContainsString('storage/profile_photos/test.jpg', $this->user->getAvatarUrl());
    }
}