<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EmailVerificationOtpNotification extends Notification
{
    use Queueable;

    protected string $otpCode;
    protected string $verificationToken;
    protected string $email;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otpCode, string $verificationToken, string $email)
    {
        $this->otpCode = $otpCode;
        $this->verificationToken = $verificationToken;
        $this->email = $email;

        Log::debug('EmailVerificationOtpNotification created', [
            'email' => $email,
            'otp_code' => $otpCode,
        ]);
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
        $verificationUrl = route('email.verify.token', [
            'token' => $this->verificationToken,
            'email' => $this->email
        ]);

        Log::info('Sending OTP verification email', [
            'email' => $this->email,
            'otp_code' => $this->otpCode,
        ]);

        return (new MailMessage)
            ->subject('Verify Your StudEats Account')
            ->greeting('Welcome to StudEats!')
            ->line('Thank you for registering with StudEats. To complete your registration, please verify your email address.')
            ->line('**Your verification code is:**')
            ->line("# {$this->otpCode}")
            ->line('This code will expire in 5 minutes.')
            ->line('You can also click the button below to verify your email automatically:')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.')
            ->line('For your security, please do not share this code with anyone.')
            ->salutation('Happy meal planning!
The StudEats Team');
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
            'email' => $this->email,
            'verification_token' => $this->verificationToken,
        ];
    }
}