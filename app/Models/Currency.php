<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'symbol',
        'symbol_ar',
        'exchange_rate',
        'is_active',
        'is_base_currency',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'is_active' => 'boolean',
        'is_base_currency' => 'boolean',
    ];

    /**
     * Get the exchange rate history for this currency.
     */
    public function exchangeRateHistory(): HasMany
    {
        return $this->hasMany(CurrencyExchangeRateHistory::class);
    }

    /**
     * Scope a query to only include active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include base currency.
     */
    public function scopeBaseCurrency($query)
    {
        return $query->where('is_base_currency', true);
    }
}
