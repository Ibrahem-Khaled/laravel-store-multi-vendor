<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'is_read',
        'related_id',
        'related_type',
    ];
    protected $casts = [
        'is_read' => 'boolean',
    ];

    // علاقة مع الكائن المرتبط (مثل المنتج أو الخدمة)
    public function related()
    {
        return $this->morphTo();
    }
    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
