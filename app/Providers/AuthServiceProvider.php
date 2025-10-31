<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تعريف Gate لإدارة المستخدمين (للتوافق مع النظام القديم)
        Gate::define('manage-users', function ($user) {
            return in_array($user->role, ['admin', 'moderator']) || $user->hasPermission('manage-users');
        });

        // تسجيل Gates ديناميكياً لكل الصلاحيات
        try {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission->name);
                });
            });
        } catch (\Exception $e) {
            // في حالة عدم وجود جدول permissions بعد (أثناء التثبيت)
        }
    }
}
