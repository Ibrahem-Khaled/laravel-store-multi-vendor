<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'merchant_id',
        'quantity',
        'unit_price',
        'commission_rate',
        'commission_amount',
        'payout_amount'
    ];

    protected $casts = [
        'unit_price'        => 'decimal:2',
        'commission_rate'   => 'decimal:4',
        'commission_amount' => 'decimal:2',
        'payout_amount'     => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}
