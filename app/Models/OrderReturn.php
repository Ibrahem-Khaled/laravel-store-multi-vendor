<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory, Auditable;

    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'type',
        'status',
        'reason',
        'customer_notes',
        'admin_notes',
        'refund_amount',
        'refund_method',
        'replacement_order_id',
        'images',
        'processed_by',
        'processed_at',
        'approved_at',
        'rejected_at',
        'completed_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'images' => 'array',
        'processed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the order that this return belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item that this return belongs to
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the user who requested the return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the return
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the replacement order if this is a replacement
     */
    public function replacementOrder()
    {
        return $this->belongsTo(Order::class, 'replacement_order_id');
    }

    /**
     * Scope for returns by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending returns
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved returns
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for completed returns
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Approve the return
     */
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $adminId,
            'approved_at' => now(),
            'processed_at' => now(),
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);
    }

    /**
     * Reject the return
     */
    public function reject($adminId, $notes)
    {
        $this->update([
            'status' => 'rejected',
            'processed_by' => $adminId,
            'rejected_at' => now(),
            'processed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing($adminId, $notes = null)
    {
        $this->update([
            'status' => 'processing',
            'processed_by' => $adminId,
            'processed_at' => now(),
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);
    }

    /**
     * Complete the return
     */
    public function complete($adminId, $notes = null)
    {
        $this->update([
            'status' => 'completed',
            'processed_by' => $adminId,
            'completed_at' => now(),
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);
    }

    /**
     * Cancel the return
     */
    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }
}
