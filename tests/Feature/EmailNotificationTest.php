<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AccountConfirmationNotification;
use App\Notifications\FormSubmissionNotification;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake notifications and mail for testing
        Notification::fake();
        Mail::fake();
        Queue::fake();
    }

    /** @test */
    public function user_receives_account_confirmation_email_upon_registration(): void
    {
        // Arrange
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'daily_budget' => 200,
            'dietary_preferences' => ['vegetarian'],
        ];

        // Act
        $response = $this->post(route('register'), $userData);

        // Assert
        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', $userData['email'])->first();
        $this->assertNotNull($user);

        Notification::assertSentTo(
            $user,
            AccountConfirmationNotification::class,
            function ($notification) use ($user) {
                return $notification->user->id === $user->id;
            }
        );
    }

    /** @test */
    public function account_confirmation_notification_contains_correct_data(): void
    {
        // Arrange
        $user = User::factory()->create([
            'daily_budget' => 250,
            'dietary_preferences' => ['vegetarian', 'gluten_free'],
        ]);

        // Act
        $notification = new AccountConfirmationNotification($user);
        $mailMessage = $notification->toMail($user);

        // Assert
        $this->assertEquals('Welcome to StudEats - Account Created Successfully!', $mailMessage->subject);
        $this->assertEquals('emails.account-confirmation', $mailMessage->view);
        $this->assertArrayHasKey('user', $mailMessage->viewData);
        $this->assertEquals($user->id, $mailMessage->viewData['user']->id);
    }

    /** @test */
    public function form_submission_notification_works_for_meal_plan(): void
    {
        // Arrange
        $user = User::factory()->create();
        $submissionData = [
            'meal_plan_name' => 'Weekly Budget Plan',
            'budget' => 1400,
            'meals_count' => 21,
        ];
        $nextSteps = ['Review your meal plan', 'Generate shopping list'];

        // Act
        $notification = new FormSubmissionNotification(
            'meal_plan',
            $submissionData,
            'MP-'.time(),
            $nextSteps
        );

        $user->notify($notification);

        // Assert
        Notification::assertSentTo(
            $user,
            FormSubmissionNotification::class,
            function ($notification) {
                return $notification->formType === 'meal_plan';
            }
        );
    }

    /** @test */
    public function form_submission_notification_contains_correct_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        $submissionData = ['recipe_name' => 'Adobo Chicken'];
        $submissionId = 'REC-12345';
        $nextSteps = ['Wait for approval', 'Check email for updates'];

        // Act
        $notification = new FormSubmissionNotification(
            'recipe_submission',
            $submissionData,
            $submissionId,
            $nextSteps
        );

        $mailMessage = $notification->toMail($user);

        // Assert
        $this->assertEquals('Recipe Submitted Successfully - StudEats', $mailMessage->subject);
        $this->assertEquals('emails.form-submission', $mailMessage->view);
        $this->assertEquals($user->id, $mailMessage->viewData['user']->id);
        $this->assertEquals('recipe_submission', $mailMessage->viewData['formType']);
        $this->assertEquals($submissionData, $mailMessage->viewData['submissionData']);
        $this->assertEquals($submissionId, $mailMessage->viewData['submissionId']);
        $this->assertEquals($nextSteps, $mailMessage->viewData['nextSteps']);
    }

    /** @test */
    public function email_service_sends_account_confirmation_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $emailService = new EmailService;

        // Act
        $result = $emailService->sendAccountConfirmation($user);

        // Assert
        $this->assertTrue($result);

        Notification::assertSentTo(
            $user,
            AccountConfirmationNotification::class
        );
    }

    /** @test */
    public function email_service_sends_form_submission_confirmation_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $emailService = new EmailService;
        $submissionData = ['form_field' => 'test_value'];

        // Act
        $result = $emailService->sendFormSubmissionConfirmation(
            $user,
            'contact_form',
            $submissionData,
            'CF-12345',
            ['We will respond within 24 hours']
        );

        // Assert
        $this->assertTrue($result);

        Notification::assertSentTo(
            $user,
            FormSubmissionNotification::class,
            function ($notification) {
                return $notification->formType === 'contact_form' &&
                       $notification->submissionId === 'CF-12345';
            }
        );
    }

    /** @test */
    public function email_service_handles_bulk_notifications(): void
    {
        // Arrange
        $users = User::factory(3)->create();
        $emailService = new EmailService;

        $notifications = $users->map(function ($user) {
            return [
                'recipient' => $user,
                'notification' => new AccountConfirmationNotification($user),
            ];
        })->toArray();

        // Act
        $results = $emailService->sendBulkNotifications($notifications);

        // Assert
        $this->assertEquals(3, $results['successful']);
        $this->assertEquals(0, $results['failed']);
        $this->assertEmpty($results['errors']);
    }

    /** @test */
    public function email_queue_health_check_returns_status(): void
    {
        // Arrange
        $emailService = new EmailService;

        // Act
        $healthStatus = $emailService->checkEmailQueueHealth();

        // Assert
        $this->assertArrayHasKey('status', $healthStatus);
        $this->assertArrayHasKey('email_queue_size', $healthStatus);
        $this->assertArrayHasKey('default_queue_size', $healthStatus);
        $this->assertArrayHasKey('issues', $healthStatus);
    }

    /** @test */
    public function registration_continues_even_if_email_fails(): void
    {
        // Arrange
        Notification::shouldReceive('send')
            ->once()
            ->andThrow(new \Exception('Email service unavailable'));

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Act
        $response = $this->post(route('register'), $userData);

        // Assert - Registration should still succeed
        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', $userData['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals($userData['name'], $user->name);
    }

    /** @test */
    public function form_submission_notification_handles_different_form_types(): void
    {
        // Arrange
        $user = User::factory()->create();
        $formTypes = ['meal_plan', 'recipe_submission', 'profile_update', 'contact_form', 'feedback'];

        foreach ($formTypes as $formType) {
            // Act
            $notification = new FormSubmissionNotification(
                $formType,
                ['test' => 'data'],
                'TEST-123',
                ['Test step']
            );

            $mailMessage = $notification->toMail($user);

            // Assert
            $this->assertNotEmpty($mailMessage->subject);
            $this->assertStringContainsString('StudEats', $mailMessage->subject);
            $this->assertEquals('emails.form-submission', $mailMessage->view);
        }
    }

    /** @test */
    public function notifications_are_queued_properly(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $user->notify(new AccountConfirmationNotification($user));

        // Assert - Check that a notification was queued
        Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class);
    }

    /** @test */
    public function email_service_gets_basic_stats(): void
    {
        // Arrange
        $emailService = new EmailService;

        // Act
        $stats = $emailService->getEmailStats(7);

        // Assert
        $this->assertArrayHasKey('period_days', $stats);
        $this->assertArrayHasKey('current_queue_size', $stats);
        $this->assertArrayHasKey('queue_status', $stats);
        $this->assertEquals(7, $stats['period_days']);
    }
}
