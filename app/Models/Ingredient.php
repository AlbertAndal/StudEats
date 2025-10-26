<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'bantay_presyo_name',
        'unit',
        'common_unit',
        'category',
        'bantay_presyo_commodity_id',
        'current_price',
        'price_source',
        'price_updated_at',
        'is_active',
        'alternative_names',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'alternative_names' => 'array',
            'current_price' => 'decimal:2',
            'price_updated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Available category values (matches database enum).
     */
    public const CATEGORIES = [
        'rice',
        'meat',
        'vegetables',
        'fish',
        'fruits',
        'others',
    ];

    /**
     * Get the price history for this ingredient.
     */
    public function priceHistory(): HasMany
    {
        return $this->hasMany(IngredientPriceHistory::class);
    }

    /**
     * Get the recipes that use this ingredient.
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
            ->withPivot('quantity', 'unit', 'estimated_cost', 'notes')
            ->withTimestamps();
    }

    /**
     * Get the latest price history record.
     */
    public function getLatestPriceAttribute(): ?IngredientPriceHistory
    {
        return $this->priceHistory()->latest('recorded_at')->first();
    }

    /**
     * Get the price for a specific region (fallback to current_price).
     */
    public function getPriceForRegion(string $regionCode = 'NCR'): ?float
    {
        $regionalPrice = $this->priceHistory()
            ->where('region_code', $regionCode)
            ->latest('recorded_at')
            ->first();

        return $regionalPrice?->price ?? $this->current_price;
    }

    /**
     * Check if price data is stale (older than specified days).
     */
    public function isPriceStale(int $days = 7): bool
    {
        if (!$this->price_updated_at) {
            return true;
        }

        return $this->price_updated_at->diffInDays(now()) > $days;
    }

    /**
     * Scope to get only active ingredients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get ingredients by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get ingredients with stale prices.
     */
    public function scopeWithStalePrices($query, int $days = 7)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('price_updated_at')
                ->orWhere('price_updated_at', '<', now()->subDays($days));
        });
    }
}
