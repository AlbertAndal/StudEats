<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $otpCode;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otpCode)
    {
        $this->otpCode = $otpCode;
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
            ->subject('Email Verification Code - StudEats')
            ->greeting('Hello!')
            ->line('You have requested email verification for your StudEats account.')
            ->line('Your verification code is:')
            ->line("**{$this->otpCode}**")
            ->line('This code will expire in 5 minutes for security reasons.')
            ->line('If you did not request this verification, please ignore this email.')
            ->line('Thank you for using StudEats!')
            ->salutation('Best regards, The StudEats Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp_code' => $this->otpCode,
            'expires_in_seconds' => 300, // 5 minutes
        ];
    }
}
