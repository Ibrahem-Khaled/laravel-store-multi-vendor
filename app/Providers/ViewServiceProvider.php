<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RoleChangeRequest; // <-- استدعاء الموديل
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
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
        // استخدم '*' لتطبيق هذا على كل الواجهات
        // أو حدد واجهة معينة مثل 'layouts.partials.sidebar'
        View::composer('*', function ($view) {
            // تحقق إذا كان المستخدم مسجلاً دخوله لتجنب الأخطاء
            if (auth()->check() && auth()->user()->role === 'admin') {
                $pendingRequestsCount = RoleChangeRequest::where('status', 'pending')->count();
                $view->with('pendingRequestsCount', $pendingRequestsCount);
            } else {
                $view->with('pendingRequestsCount', 0); // أو قيمة افتراضية
            }
        });
    }
}
