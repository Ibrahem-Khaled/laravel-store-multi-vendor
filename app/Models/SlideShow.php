<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SlideShow extends Model
{
    use Auditable;
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
