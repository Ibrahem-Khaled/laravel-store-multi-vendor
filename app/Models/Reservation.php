<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'type',
        'reservation_date',
        'status',
        'total_price',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    // العَلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
