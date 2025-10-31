<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use Auditable;
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
