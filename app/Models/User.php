<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use App\Traits\user\GeneratesUniqueIdentifiers;
use App\Traits\user\UserAttributes;
use App\Traits\user\UserRelations;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, UserRelations, UserAttributes, GeneratesUniqueIdentifiers, Auditable, HasRolesAndPermissions;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'bio',
        'address',
        'country',
        'gender',
        'birth_date',
        'password',
        'role',
        'status',
        'coins',
        'username',
        'uuid',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'avatar',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_verified' => 'bool',
        'coins' => 'integer',
        'password' => 'hashed',
    ];

    // تم إزالتها من هنا لأنها موجودة في UserAttributes.php
    // لكن الـ $appends يجب أن تبقى هنا لتعريف الـ model بما يجب إضافته عند التحويل إلى array/json
    protected $appends = [
        'avatar_url',
        'is_followed',
        'followers_count',
        'following_count'
    ];


    // JWT Implementation
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
