<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_address_id',
        'status',
        'payment_method',
        'subtotal',
        'shipping_total',
        'discount_total',
        'grand_total'
    ];
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    public function scopeFilter($q, array $f)
    {
        return $q
            ->when($f['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($f['method'] ?? null, fn($q, $v) => $q->where('payment_method', $v))
            ->when($f['from'] ?? null,   fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($f['to'] ?? null,     fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($f['search'] ?? null, function ($q, $s) {
                $q->where(function ($qq) use ($s) {
                    $qq->where('id', $s)
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
                });
            });
    }

    public function scopeWithAdminSummaries($q)
    {
        return $q->withCount(['items as items_count'])
            ->withSum('items as commission_sum', 'commission_amount')
            ->withSum('items as payout_sum', 'payout_amount');
    }
}
