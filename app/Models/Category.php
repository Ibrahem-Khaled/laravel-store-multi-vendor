<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Auditable;
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
