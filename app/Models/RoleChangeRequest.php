<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleChangeRequest extends Model
{
    protected $guarded = ['id'];

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
