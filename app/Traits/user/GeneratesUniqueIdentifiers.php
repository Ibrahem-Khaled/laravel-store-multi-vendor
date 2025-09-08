<?php

namespace App\Traits\user;

use App\Models\User;
use Illuminate\Support\Str;

trait GeneratesUniqueIdentifiers
{
    /**
     * Boot the trait.
     * Laravel will automatically call this method on model booting.
     */
    protected static function bootGeneratesUniqueIdentifiers(): void
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }

            if (empty($user->username)) {
                $user->username = static::generateUniqueUsername($user->name ?? 'user');
            }
        });
    }

    public static function generateUniqueUsername(string $base): string
    {
        $slug = Str::of($base)->trim()->lower()->replace([' ', '—', '_'], '-')->slug('-');
        $slug = $slug ?: 'user';
        $username = $slug;
        $i = 0;

        while (static::where('username', $username)->exists()) {
            $i++;
            $username = $slug . '-' . $i;
        }

        return $username;
    }
}
