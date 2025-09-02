<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'avatar_url',
        'is_followed',
        'followers_count',
        'following_count'
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

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }


    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    /**
     * The users that follow this user.
     * (المتابعون لهذا المستخدم)
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function roleChangeRequests()
    {
        return $this->hasMany(RoleChangeRequest::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    ////////////// jwt \\\\\\\\\\\\\\
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

    public function getIsFollowedAttribute(): bool
    {
        // 1. Get the authenticated user
        $authedUser = auth()->guard('api')->user();

        // 2. If there is no authenticated user (guest), return false
        if (!$authedUser) {
            return false;
        }

        // 3. Check if the authenticated user's ID exists in this user's followers list
        return $this->followers()->where('follower_id', $authedUser->id)->exists();
    }

    public function getFollowersCountAttribute(): int
    {
        // This will return the count of users who follow this user.
        // It's efficient because it uses the relationship's count query.
        return $this->followers()->count();
    }

    /**
     * Accessor for the number of users this user is following.
     *
     * @return int
     */
    public function getFollowingCountAttribute(): int
    {
        // This will return the count of users this user is following.
        return $this->following()->count();
    }
}
