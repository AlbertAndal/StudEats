<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string|null $timezone
 * @method \Illuminate\Database\Eloquent\Relations\HasMany mealPlans()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany mealPlansForDate(string $date)
 * @method \Illuminate\Database\Eloquent\Relations\HasMany weeklyMealPlans(\Carbon\Carbon $startDate)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dietary_preferences',
        'daily_budget',
        'age',
        'gender',
        'activity_level',
        'height',
        'height_unit',
        'weight',
        'weight_unit',
        'role',
        'is_active',
        'suspended_at',
        'suspended_reason',
    'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dietary_preferences' => 'array',
            'daily_budget' => 'decimal:2',
            'is_active' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }

    /**
     * Get the meal plans for the user.
     */
    public function mealPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    /**
     * Get the user's meal plans for a specific date.
     */
    public function mealPlansForDate(string $date): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->mealPlans()->where('scheduled_date', $date);
    }

    /**
     * Get the user's weekly meal plans.
     */
    public function weeklyMealPlans(\Carbon\Carbon $startDate): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->mealPlans()
            ->whereBetween('scheduled_date', [
                $startDate->format('Y-m-d'),
                $startDate->copy()->addDays(6)->format('Y-m-d')
            ])
            ->with(['meal.nutritionalInfo']);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Suspend the user account.
     */
    public function suspend(string $reason = null): void
    {
        $this->update([
            'is_active' => false,
            'suspended_at' => now(),
            'suspended_reason' => $reason,
        ]);
    }

    /**
     * Activate the user account.
     */
    public function activate(): void
    {
        $this->update([
            'is_active' => true,
            'suspended_at' => null,
            'suspended_reason' => null,
        ]);
    }

    /**
     * Get admin logs performed by this user.
     */
    public function adminLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminLog::class, 'admin_user_id');
    }
    
    /**
     * Get current time in user's timezone.
     */
    public function getCurrentTimeInTimezone(): \Carbon\Carbon
    {
        $timezone = $this->timezone ?? config('app.timezone');
        return now()->setTimezone($timezone);
    }
    
    /**
     * Get current date in user's timezone.
     */
    public function getCurrentDateInTimezone(): string
    {
        return $this->getCurrentTimeInTimezone()->toDateString();
    }
}
