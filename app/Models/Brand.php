<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Auditable;
    protected $fillable = ['user_id', 'name', 'description', 'image', 'link', 'order', 'latitude', 'longitude', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
