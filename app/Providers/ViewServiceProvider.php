<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RoleChangeRequest;
use App\Models\Product;
use App\Models\ShippingProof;
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
                $pendingApprovalCount = Product::where('is_approved', false)->count();
                $pendingShippingProofsCount = ShippingProof::where('status', 'pending')->count();
                $view->with('pendingRequestsCount', $pendingRequestsCount);
                $view->with('pendingApprovalCount', $pendingApprovalCount);
                $view->with('pendingShippingProofsCount', $pendingShippingProofsCount);
            } else {
                $view->with('pendingRequestsCount', 0);
                $view->with('pendingApprovalCount', 0);
                $view->with('pendingShippingProofsCount', 0);
            }
        });
    }
}
