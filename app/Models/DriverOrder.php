<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'driver_id',
        'assigned_by',
        'status',
        'assignment_type',
        'assigned_at',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'delivery_fee',
        'delivery_notes',
        'confirmation_data',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'delivery_fee' => 'decimal:2',
        'delivery_notes' => 'array',
        'confirmation_data' => 'array',
    ];

    /**
     * Get the order that this driver order belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the driver assigned to this order
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the user who assigned this order
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope for orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for active orders
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'accepted', 'picked_up']);
    }

    /**
     * Scope for completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for auto assigned orders
     */
    public function scopeAutoAssigned($query)
    {
        return $query->where('assignment_type', 'auto');
    }

    /**
     * Scope for manually assigned orders
     */
    public function scopeManuallyAssigned($query)
    {
        return $query->where('assignment_type', 'manual');
    }

    /**
     * Mark order as accepted by driver
     */
    public function accept()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Update driver's orders count
        $this->driver->updateOrdersCount();
    }

    /**
     * Mark order as picked up
     */
    public function markAsPickedUp()
    {
        $this->update([
            'status' => 'picked_up',
            'picked_up_at' => now(),
        ]);
    }

    /**
     * Mark order as delivered
     */
    public function markAsDelivered($confirmationData = null)
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'confirmation_data' => $confirmationData,
        ]);

        // Update driver's orders count and total deliveries
        $this->driver->updateOrdersCount();
        $this->driver->increment('total_deliveries');
    }

    /**
     * Cancel the order
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        // Update driver's orders count
        $this->driver->updateOrdersCount();
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['assigned', 'accepted']);
    }

    /**
     * Check if order can be marked as delivered
     */
    public function canBeDelivered()
    {
        return $this->status === 'picked_up';
    }

    /**
     * Get order duration in minutes
     */
    public function getDurationInMinutes()
    {
        if (!$this->delivered_at) {
            return null;
        }

        return $this->assigned_at->diffInMinutes($this->delivered_at);
    }

    /**
     * Get confirmation status
     */
    public function getConfirmationStatus()
    {
        $confirmationData = $this->confirmation_data ?? [];

        return [
            'driver_confirmed' => $confirmationData['driver_confirmed'] ?? false,
            'customer_confirmed' => $confirmationData['customer_confirmed'] ?? false,
            'admin_confirmed' => $confirmationData['admin_confirmed'] ?? false,
            'is_fully_confirmed' => ($confirmationData['driver_confirmed'] ?? false) &&
                                   (($confirmationData['customer_confirmed'] ?? false) ||
                                    ($confirmationData['admin_confirmed'] ?? false)),
        ];
    }
}
