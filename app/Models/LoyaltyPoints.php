<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyPoints extends Model
{
    protected $fillable = [
        'user_id',
        'total_points',
        'used_points',
        'expired_points',
        'platform_contribution',
        'customer_contribution',
        'last_earned_at',
        'last_used_at',
    ];

    protected $casts = [
        'platform_contribution' => 'decimal:2',
        'customer_contribution' => 'decimal:2',
        'last_earned_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع معاملات الولاء
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class, 'loyalty_points_id');
    }

    /**
     * حساب النقاط المتاحة
     */
    public function getAvailablePointsAttribute(): int
    {
        return $this->total_points - $this->used_points - $this->expired_points;
    }

    /**
     * حساب إجمالي المساهمة
     */
    public function getTotalContributionAttribute(): float
    {
        return $this->platform_contribution + $this->customer_contribution;
    }

    /**
     * إضافة نقاط جديدة
     */
    public function addPoints(int $points, float $platformContribution = 0, float $customerContribution = 0): void
    {
        $this->increment('total_points', $points);
        $this->increment('platform_contribution', $platformContribution);
        $this->increment('customer_contribution', $customerContribution);
        $this->update(['last_earned_at' => now()]);
    }

    /**
     * استخدام نقاط
     */
    public function usePoints(int $points): bool
    {
        if ($this->available_points >= $points) {
            $this->increment('used_points', $points);
            $this->update(['last_used_at' => now()]);
            return true;
        }
        return false;
    }

    /**
     * إضافة نقاط منتهية الصلاحية
     */
    public function addExpiredPoints(int $points): void
    {
        $this->increment('expired_points', $points);
    }

    /**
     * استرداد نقاط مستخدمة
     */
    public function refundPoints(int $points): void
    {
        $this->decrement('used_points', $points);
        $this->increment('total_points', $points);
    }
}
