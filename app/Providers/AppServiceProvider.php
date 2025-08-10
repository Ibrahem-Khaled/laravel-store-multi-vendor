<?php

namespace App\Providers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::define('view-conversation', function (User $user, Conversation $conversation) {
            // سيتحقق إذا كان معرف المستخدم موجوداً في جدول المشاركين لهذه المحادثة
            return $conversation->participants->contains($user);
        });
    }
}
