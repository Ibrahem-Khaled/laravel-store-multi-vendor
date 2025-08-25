<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleChangeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'requested_role',
        'status',
        'reason',
        'admin_notes',
        'reviewed_by',
    ];

    // علاقة: كل طلب ينتمي لمستخدم واحد
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة: كل طلب تمت مراجعته بواسطة مدير واحد (اختياري)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
