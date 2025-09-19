<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'is_used',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
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
        return ! $this->is_used && ! $this->isExpired();
    }

    /**
     * Mark the OTP as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Scope to get valid OTPs only.
     */
    public function scopeValid($query): mixed
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired OTPs.
     */
    public function scopeExpired($query): mixed
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope to get OTPs for a specific email.
     */
    public function scopeForEmail($query, string $email): mixed
    {
        return $query->where('email', $email);
    }

    /**
     * Clean up expired OTPs.
     */
    public static function cleanupExpired(): int
    {
        return static::expired()->delete();
    }
}
