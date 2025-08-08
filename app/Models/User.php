<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $guarded = ['id'];
    protected $appends = [
        'avatar_url'
    ];
    protected $hidden = [
        'password',
        'remember_token',
        'avatar'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $user) {
            // توليد UUID فريد من نوع v4
            $user->uuid = (string) Str::uuid();
        });
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function productsFavorites()
    {
        return $this->belongsToMany(Product::class, 'product_favorites', 'user_id', 'product_id');
    }

    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : 'https://cdn-icons-png.flaticon.com/128/2202/2202112.png';
    }
}
