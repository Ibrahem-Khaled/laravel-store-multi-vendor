<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'address_line_1',
        'city',
        'neighborhood',
        'address',
        'latitude',
        'longitude',
        'postal_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
