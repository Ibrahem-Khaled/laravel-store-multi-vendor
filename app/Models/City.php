<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use Auditable;
    protected $fillable = ['name'];

    public function neighborhoods()
    {
        return $this->hasMany(Neighborhood::class);
    }
}
