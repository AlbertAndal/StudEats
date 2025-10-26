<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'verification_token',
        'expires_at',
        'is_used',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'is_used' => 'boolean',
        ];
    }

    /**
     * Check if the OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the OTP is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    /**
     * Scope for finding valid OTPs.
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope for finding OTPs by email.
     */
    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Find an OTP by email and verification token.
     */
    public static function findByVerificationToken(string $email, string $token): ?self
    {
        return static::where('email', $email)
                    ->where('verification_token', $token)
                    ->first();
    }

    /**
     * Clean up expired OTPs.
     */
    public static function cleanupExpired(): int
    {
        return static::where('expires_at', '<', now())->delete();
    }

    /**
     * Get remaining time before expiry in seconds.
     */
    public function remainingSeconds(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return $this->expires_at->diffInSeconds(now());
    }

    /**
     * Get remaining time before expiry in minutes (rounded up).
     */
    public function remainingMinutes(): int
    {
        return (int) ceil($this->remainingSeconds() / 60);
    }
}
