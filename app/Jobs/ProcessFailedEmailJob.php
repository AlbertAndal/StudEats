<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessFailedEmailJob implements ShouldQueue
{
    use Queueable;

    public string $failedJobId;

    public string $failureReason;

    public array $originalPayload;

    public string $recipientEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $failedJobId,
        string $failureReason,
        array $originalPayload,
        string $recipientEmail
    ) {
        $this->failedJobId = $failedJobId;
        $this->failureReason = $failureReason;
        $this->originalPayload = $originalPayload;
        $this->recipientEmail = $recipientEmail;

        // Process failed emails with lower priority
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing failed email job', [
            'failed_job_id' => $this->failedJobId,
            'recipient_email' => $this->recipientEmail,
            'failure_reason' => $this->failureReason,
        ]);

        // Log the failure for monitoring
        $this->logEmailFailure();

        // Notify administrators
        $this->notifyAdministrators();

        // Attempt to create a simplified notification if original was complex
        $this->attemptSimplifiedNotification();
    }

    /**
     * Log detailed information about the email failure.
     */
    protected function logEmailFailure(): void
    {
        Log::error('Email delivery failed - detailed log', [
            'failed_job_id' => $this->failedJobId,
            'recipient_email' => $this->recipientEmail,
            'failure_reason' => $this->failureReason,
            'original_payload' => $this->originalPayload,
            'failure_time' => now()->toISOString(),
            'job_queue' => $this->queue ?? 'default',
        ]);
    }

    /**
     * Notify administrators about the failed email.
     */
    protected function notifyAdministrators(): void
    {
        try {
            $adminUsers = User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->get();

            if ($adminUsers->isEmpty()) {
                Log::warning('No admin users found to notify of email failure');

                return;
            }

            $notificationData = [
                'failed_job_id' => $this->failedJobId,
                'recipient_email' => $this->recipientEmail,
                'failure_reason' => $this->failureReason,
                'failure_time' => now()->toISOString(),
                'original_notification_type' => $this->extractNotificationType(),
            ];

            Notification::send(
                $adminUsers,
                new FormSubmissionNotification(
                    'email_failure_alert',
                    $notificationData,
                    $this->failedJobId,
                    [
                        'Review the failed email details in the admin dashboard',
                        'Check email service configuration and connectivity',
                        'Monitor for additional failures that might indicate a systemic issue',
                        'Consider contacting the affected user through alternative means if critical',
                    ]
                )
            );

            Log::info('Admin notification sent for failed email', [
                'failed_job_id' => $this->failedJobId,
                'notified_admins' => $adminUsers->count(),
            ]);
        } catch (\Exception $e) {
            Log::critical('Failed to notify admins about email failure', [
                'failed_job_id' => $this->failedJobId,
                'admin_notification_error' => $e->getMessage(),
                'original_failure_reason' => $this->failureReason,
            ]);
        }
    }

    /**
     * Attempt to send a simplified notification if the original was complex.
     */
    protected function attemptSimplifiedNotification(): void
    {
        try {
            // Only attempt simplified notification for critical emails like account confirmation
            $notificationType = $this->extractNotificationType();

            if (! in_array($notificationType, ['account_confirmation', 'password_reset', 'email_verification'])) {
                Log::info('Skipping simplified notification for non-critical email type', [
                    'notification_type' => $notificationType,
                    'failed_job_id' => $this->failedJobId,
                ]);

                return;
            }

            $user = User::where('email', $this->recipientEmail)->first();

            if (! $user) {
                Log::warning('Cannot send simplified notification - user not found', [
                    'recipient_email' => $this->recipientEmail,
                    'failed_job_id' => $this->failedJobId,
                ]);

                return;
            }

            // Create a very simple, text-only notification
            $simplifiedData = [
                'message' => $this->getSimplifiedMessage($notificationType),
                'original_failure' => true,
                'support_contact' => 'support@studeats.com',
            ];

            // Send with minimal formatting to reduce chance of failure
            $user->notify(new FormSubmissionNotification(
                'simplified_notification',
                $simplifiedData,
                null,
                ['Contact support if you have any questions']
            ));

            Log::info('Simplified notification sent successfully', [
                'failed_job_id' => $this->failedJobId,
                'recipient_email' => $this->recipientEmail,
                'notification_type' => $notificationType,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send simplified notification', [
                'failed_job_id' => $this->failedJobId,
                'simplified_notification_error' => $e->getMessage(),
                'original_failure_reason' => $this->failureReason,
            ]);
        }
    }

    /**
     * Extract the notification type from the original payload.
     */
    protected function extractNotificationType(): string
    {
        $payload = $this->originalPayload;

        // Try to determine notification type from various payload structures
        if (isset($payload['data']['notification_type'])) {
            return $payload['data']['notification_type'];
        }

        if (isset($payload['displayName'])) {
            if (str_contains($payload['displayName'], 'AccountConfirmation')) {
                return 'account_confirmation';
            }
            if (str_contains($payload['displayName'], 'FormSubmission')) {
                return 'form_submission';
            }
        }

        return 'unknown';
    }

    /**
     * Get a simplified message based on notification type.
     */
    protected function getSimplifiedMessage(string $notificationType): string
    {
        return match ($notificationType) {
            'account_confirmation' => 'Welcome to StudEats! Your account has been created successfully. Please log in to start planning your meals.',
            'password_reset' => 'Your password reset request has been processed. Please check your email for further instructions.',
            'email_verification' => 'Please verify your email address to complete your account setup.',
            default => 'Thank you for using StudEats. If you have any questions, please contact our support team.',
        };
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical('ProcessFailedEmailJob itself failed', [
            'failed_job_id' => $this->failedJobId,
            'processing_error' => $exception->getMessage(),
            'original_failure_reason' => $this->failureReason,
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
