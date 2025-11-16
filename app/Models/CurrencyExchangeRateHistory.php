<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyExchangeRateHistory extends Model
{
    protected $table = 'currency_exchange_rate_history';

    public $timestamps = false;

    protected $fillable = [
        'currency_id',
        'exchange_rate',
        'previous_rate',
        'change_percentage',
        'updated_by',
        'notes',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'previous_rate' => 'decimal:4',
        'change_percentage' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the currency that owns this history record.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the user who updated the exchange rate.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
