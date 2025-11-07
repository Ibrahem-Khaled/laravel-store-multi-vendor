<?php

namespace App\Traits\user;

use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Conversation;
use App\Models\Driver;
use App\Models\LoyaltyPoints;
use App\Models\LoyaltyTransaction;
use App\Models\MerchantLedgerEntry;
use App\Models\MerchantPayment;
use App\Models\MerchantProfile;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Review;
use App\Models\Role;
use App\Models\RoleChangeRequest;
use App\Models\ShippingProof;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait UserRelations
{
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function productsFavorites()
    {
        return $this->belongsToMany(Product::class, 'product_favorites', 'user_id', 'product_id');
    }

    public function userNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function roleChangeRequests(): HasMany
    {
        return $this->hasMany(RoleChangeRequest::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function shippingProofs(): HasMany
    {
        return $this->hasMany(ShippingProof::class, 'user_id', 'id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }


    // Relations for Merchant
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Brand::class);
    }
    public function ledgerEntries()
    {
        return $this->hasMany(MerchantLedgerEntry::class, 'merchant_id');
    }
    public function merchantPayments()
    {
        return $this->hasMany(MerchantPayment::class, 'merchant_id');
    }
    public function merchantProfile()
    {
        return $this->hasOne(MerchantProfile::class, 'user_id');
    }

    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id');
    }

    // Relations for Loyalty Points
    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoints::class);
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * الأدوار المرتبطة بالمستخدم
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * الحصول على جميع الصلاحيات للمستخدم (من خلال الأدوار)
     * ملاحظة: هذه دالة helper وليست علاقة مباشرة
     */
    public function getPermissionsAttribute()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }
}
