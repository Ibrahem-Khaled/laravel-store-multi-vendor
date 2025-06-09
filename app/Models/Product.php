<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function images()
    {
        return $this->hasMany(Images::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_products', 'product_id', 'feature_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
