<?php

namespace App\Traits\user;

trait UserAttributes
{
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://cdn-icons-png.flaticon.com/128/2202/2202112.png';
    }

    public function getIsFollowedAttribute(): bool
    {
        if (!auth()->guard('api')->check()) {
            return false;
        }

        // ملاحظة هامة حول الأداء سيتم ذكرها لاحقاً
        return $this->followers()->where('follower_id', auth()->guard('api')->id())->exists();
    }

    public function getFollowersCountAttribute(): int
    {
        // ملاحظة هامة حول الأداء سيتم ذكرها لاحقاً
        // الأفضل استخدام withCount بدلاً من هذا
        return $this->followers()->count();
    }

    public function getFollowingCountAttribute(): int
    {
        // ملاحظة هامة حول الأداء سيتم ذكرها لاحقاً
        // الأفضل استخدام withCount بدلاً من هذا
        return $this->following()->count();
    }
}
