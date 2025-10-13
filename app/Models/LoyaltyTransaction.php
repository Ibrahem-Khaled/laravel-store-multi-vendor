<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'loyalty_points_id',
        'type',
        'points',
        'amount',
        'source',
        'description',
        'metadata',
        'order_id',
        'processed_by',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * أنواع المعاملات
     */
    const TYPE_EARNED = 'earned';
    const TYPE_USED = 'used';
    const TYPE_EXPIRED = 'expired';
    const TYPE_REFUNDED = 'refunded';

    /**
     * مصادر النقاط
     */
    const SOURCE_ORDER = 'order';
    const SOURCE_MANUAL = 'manual';
    const SOURCE_REFUND = 'refund';
    const SOURCE_EXPIRY = 'expiry';

    /**
     * العلاقة مع المستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع نقاط الولاء
     */
    public function loyaltyPoints(): BelongsTo
    {
        return $this->belongsTo(LoyaltyPoints::class, 'loyalty_points_id');
    }

    /**
     * العلاقة مع الطلب
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * العلاقة مع المعالج
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * التحقق من انتهاء الصلاحية
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * الحصول على حالة المعاملة
     */
    public function getStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        return match($this->type) {
            self::TYPE_EARNED => 'active',
            self::TYPE_USED => 'used',
            self::TYPE_EXPIRED => 'expired',
            self::TYPE_REFUNDED => 'refunded',
            default => 'unknown'
        };
    }
}
