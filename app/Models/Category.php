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
        'commission_rate',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:4',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
