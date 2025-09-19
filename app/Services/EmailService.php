<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AccountConfirmationNotification;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

class EmailService
{
    /**
     * Send account confirmation email with error handling.
     */
    public function sendAccountConfirmation(User $user): bool
    {
        try {
            $user->notify(new AccountConfirmationNotification($user));

            Log::info('Account confirmation email queued successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue account confirmation email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Notify admins of failed email delivery
            $this->notifyAdminsOfFailure('account_confirmation', $user->email, $e->getMessage());

            return false;
        }
    }

    /**
     * Send form submission confirmation email.
     */
    public function sendFormSubmissionConfirmation(
        User $user,
        string $formType,
        array $submissionData,
        ?string $submissionId = null,
        array $nextSteps = []
    ): bool {
        try {
            $user->notify(new FormSubmissionNotification(
                $formType,
                $submissionData,
                $submissionId,
                $nextSteps
            ));

            Log::info('Form submission confirmation email queued successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'form_type' => $formType,
                'submission_id' => $submissionId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue form submission confirmation email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'form_type' => $formType,
                'submission_id' => $submissionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Notify admins of failed email delivery
            $this->notifyAdminsOfFailure('form_submission', $user->email, $e->getMessage(), [
                'form_type' => $formType,
                'submission_id' => $submissionId,
            ]);

            return false;
        }
    }

    /**
     * Send bulk email notifications with retry logic.
     */
    public function sendBulkNotifications(array $notifications): array
    {
        $results = [
            'successful' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($notifications as $notification) {
            try {
                $this->sendNotificationWithRetry($notification);
                $results['successful']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'notification' => get_class($notification['notification']),
                    'recipient' => $notification['recipient']->email ?? 'unknown',
                    'error' => $e->getMessage(),
                ];

                Log::error('Bulk notification failed', [
                    'notification' => get_class($notification['notification']),
                    'recipient' => $notification['recipient']->email ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Bulk email notifications completed', $results);

        return $results;
    }

    /**
     * Send a notification with retry logic.
     */
    public function sendNotificationWithRetry(array $notification, int $maxRetries = 3): void
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $maxRetries) {
            try {
                $notification['recipient']->notify($notification['notification']);

                return; // Success, exit retry loop
            } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;

                if ($attempts < $maxRetries) {
                    // Wait before retrying (exponential backoff)
                    $delay = pow(2, $attempts) * 5; // 10s, 20s, 40s
                    sleep($delay);
                }
            }
        }

        // All retries failed, throw the last exception
        throw $lastException;
    }

    /**
     * Check email queue health and notify if issues are detected.
     */
    public function checkEmailQueueHealth(): array
    {
        try {
            $queueSize = Queue::size('emails');
            $defaultQueueSize = Queue::size();

            $healthStatus = [
                'email_queue_size' => $queueSize,
                'default_queue_size' => $defaultQueueSize,
                'status' => 'healthy',
                'issues' => [],
            ];

            // Check for large queue sizes (potential backup)
            if ($queueSize > 100) {
                $healthStatus['status'] = 'warning';
                $healthStatus['issues'][] = "Email queue has {$queueSize} jobs pending";
            }

            if ($queueSize > 500) {
                $healthStatus['status'] = 'critical';
                $healthStatus['issues'][] = "Email queue severely backed up with {$queueSize} jobs";

                // Notify admins of critical queue backup
                $this->notifyAdminsOfQueueBackup($queueSize);
            }

            Log::info('Email queue health check completed', $healthStatus);

            return $healthStatus;
        } catch (\Exception $e) {
            Log::error('Failed to check email queue health', [
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Notify administrators of email delivery failures.
     */
    protected function notifyAdminsOfFailure(
        string $emailType,
        string $recipientEmail,
        string $errorMessage,
        array $additionalData = []
    ): void {
        try {
            $adminUsers = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();

            if ($adminUsers->isEmpty()) {
                Log::warning('No admin users found to notify of email failure');

                return;
            }

            $notificationData = array_merge([
                'email_type' => $emailType,
                'recipient_email' => $recipientEmail,
                'error_message' => $errorMessage,
                'occurred_at' => now()->toISOString(),
            ], $additionalData);

            Notification::send(
                $adminUsers,
                new FormSubmissionNotification(
                    'email_failure_alert',
                    $notificationData,
                    null,
                    ['Check email queue and system health', 'Review error logs for more details']
                )
            );
        } catch (\Exception $e) {
            Log::critical('Failed to notify admins of email failure', [
                'original_error' => $errorMessage,
                'notification_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify administrators of critical queue backup.
     */
    protected function notifyAdminsOfQueueBackup(int $queueSize): void
    {
        try {
            $adminUsers = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();

            if ($adminUsers->isEmpty()) {
                return;
            }

            Notification::send(
                $adminUsers,
                new FormSubmissionNotification(
                    'queue_backup_alert',
                    [
                        'queue_size' => $queueSize,
                        'queue_name' => 'emails',
                        'severity' => 'critical',
                        'detected_at' => now()->toISOString(),
                    ],
                    null,
                    [
                        'Check queue worker status',
                        'Review queue processing capacity',
                        'Consider scaling queue workers',
                    ]
                )
            );
        } catch (\Exception $e) {
            Log::critical('Failed to notify admins of queue backup', [
                'queue_size' => $queueSize,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get email delivery statistics.
     */
    public function getEmailStats(int $days = 7): array
    {
        // This would typically query a dedicated email tracking table
        // For now, we'll return basic queue information
        return [
            'period_days' => $days,
            'current_queue_size' => Queue::size('emails'),
            'queue_status' => $this->checkEmailQueueHealth(),
            'note' => 'Email tracking requires a dedicated tracking system for detailed statistics',
        ];
    }
}
