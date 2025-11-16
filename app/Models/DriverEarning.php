<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverEarning extends Model
{
    protected $fillable = [
        'driver_id',
        'driver_order_id',
        'order_id',
        'delivery_fee',
        'driver_commission_percentage',
        'driver_earned_amount',
        'platform_fee',
        'status',
        'is_invoiced',
        'earning_date',
        'processed_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'driver_commission_percentage' => 'decimal:2',
        'driver_earned_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'is_invoiced' => 'boolean',
        'earning_date' => 'date',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the driver that owns this earning
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the driver order associated with this earning
     */
    public function driverOrder(): BelongsTo
    {
        return $this->belongsTo(DriverOrder::class);
    }

    /**
     * Get the order associated with this earning
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for pending earnings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processed earnings
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Scope for paid earnings
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for invoiced earnings
     */
    public function scopeInvoiced($query)
    {
        return $query->where('is_invoiced', true);
    }

    /**
     * Scope for not invoiced earnings
     */
    public function scopeNotInvoiced($query)
    {
        return $query->where('is_invoiced', false);
    }
}
