<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlideShow extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'is_active',
        'order',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
