<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['reservation_type', 'cover', 'is_from_favorite'];
    protected $with = ['images']; // <--- أضف هذا السطر

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

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'product_favorites', 'product_id', 'user_id');
    }

    public function getReservationTypeAttribute()
    {
        return $this->subCategory->type;
    }

    public function getCoverAttribute()
    {
        // أولاً، تحقق مما إذا كانت مجموعة الصور (العلاقة) ليست فارغة
        if ($this->images->isNotEmpty()) {

            // إذا كانت هناك صور، اختر واحدة عشوائياً وأرجع مسارها (path)
            return $this->images->random()->path; // غيّر 'path' إلى اسم حقل الصورة لديك
        }

        // في حال لم يكن للمنتج أي صور، أرجع صورة افتراضية
        return url('assets/img/logo-ct.png'); // ضع هنا مسار صورتك الافتراضية
    }

    public function getIsFromFavoriteAttribute()
    {
        $user = auth()->guard('api')->user();
        if ($user) {
            return $user->productsFavorites()->where('product_id', $this->id)->exists();
        }
        return false;
    }
}
