<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantLedgerEntry extends Model
{

    use HasFactory;
    protected $fillable = [
        'merchant_id',
        'order_id',
        'order_item_id',
        'direction',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_reference',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
