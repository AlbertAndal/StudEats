<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $formType;

    public array $submissionData;

    public ?string $submissionId;

    public array $nextSteps;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $formType,
        array $submissionData,
        ?string $submissionId = null,
        array $nextSteps = []
    ) {
        $this->formType = $formType;
        $this->submissionData = $submissionData;
        $this->submissionId = $submissionId;
        $this->nextSteps = $nextSteps;

        // Set queue priorities for email delivery
        $this->onQueue('emails');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->view('emails.form-submission', [
                'user' => $notifiable,
                'formType' => $this->formType,
                'submissionData' => $this->submissionData,
                'submissionId' => $this->submissionId,
                'nextSteps' => $this->nextSteps,
            ]);
    }

    /**
     * Get the subject line based on form type.
     */
    protected function getSubject(): string
    {
        return match ($this->formType) {
            'meal_plan' => 'Meal Plan Created Successfully - StudEats',
            'recipe_submission' => 'Recipe Submitted Successfully - StudEats',
            'profile_update' => 'Profile Updated Successfully - StudEats',
            'contact_form' => 'Message Received - StudEats Support',
            'feedback' => 'Thank You for Your Feedback - StudEats',
            default => 'Form Submitted Successfully - StudEats',
        };
    }

    /**
     * Add form-specific content to the message.
     */
    protected function addFormSpecificContent(MailMessage $message): MailMessage
    {
        return match ($this->formType) {
            'meal_plan' => $message->line('Your meal plan has been created successfully!')
                ->line('You can now view your personalized meal recommendations and start cooking.'),

            'recipe_submission' => $message->line('Thank you for submitting your recipe!')
                ->line('Our team will review it and notify you once it\'s approved.'),

            'profile_update' => $message->line('Your profile has been updated successfully!')
                ->line('Your meal recommendations will now be more personalized based on your updated preferences.'),

            'contact_form' => $message->line('We have received your message and will respond within 24 hours.')
                ->line('Thank you for contacting StudEats support.'),

            'feedback' => $message->line('Thank you for taking the time to provide feedback!')
                ->line('Your input helps us improve StudEats for all users.'),

            default => $message->line('Your form has been submitted successfully!')
                ->line('We have received your information and will process it accordingly.'),
        };
    }

    /**
     * Add submission summary to the message.
     */
    protected function addSubmissionSummary(MailMessage $message): MailMessage
    {
        if (empty($this->submissionData)) {
            return $message;
        }

        $message->line('')
            ->line('**Submission Summary:**');

        foreach ($this->submissionData as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $label = ucwords(str_replace(['_', '-'], ' ', $key));
            $message->line("• **{$label}:** {$value}");
        }

        return $message;
    }

    /**
     * Add action button based on form type.
     */
    protected function addActionButton(MailMessage $message): MailMessage
    {
        return match ($this->formType) {
            'meal_plan' => $message->action('View Your Meal Plan', route('meal-plans.index')),
            'recipe_submission' => $message->action('Browse Recipes', route('recipes.index')),
            'profile_update' => $message->action('View Your Profile', route('profile.show')),
            default => $message->action('Go to Dashboard', route('dashboard')),
        };
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'emails',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'form_type' => $this->formType,
            'submission_data' => $this->submissionData,
            'submission_id' => $this->submissionId,
            'next_steps' => $this->nextSteps,
            'notification_type' => 'form_submission',
        ];
    }
}
