<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
    ];

    /**
     * الأدوار التي تحتوي على هذه الصلاحية
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    /**
     * المستخدمون الذين لديهم هذه الصلاحية (من خلال الأدوار)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'permission_id', 'user_id')
            ->using(Role::class);
    }

    /**
     * نطاق حسب المجموعة
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}

