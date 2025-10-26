<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientPriceHistory extends Model
{
    protected $table = 'ingredient_price_history';

    protected $fillable = [
        'ingredient_id',
        'price',
        'price_source',
        'region_code',
        'recorded_at',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'recorded_at' => 'datetime',
            'raw_data' => 'array',
        ];
    }

    /**
     * Get the ingredient that owns this price history.
     */
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Scope to get price history for a specific region.
     */
    public function scopeForRegion($query, string $regionCode)
    {
        return $query->where('region_code', $regionCode);
    }

    /**
     * Scope to get price history from a specific source.
     */
    public function scopeFromSource($query, string $source)
    {
        return $query->where('price_source', $source);
    }

    /**
     * Scope to get price history within a date range.
     */
    public function scopeWithinDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }
}
