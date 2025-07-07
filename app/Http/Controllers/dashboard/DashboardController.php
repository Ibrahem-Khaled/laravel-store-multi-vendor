<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // التحقق من دور المستخدم وتجهيز البيانات بناء عليه
        if ($user->role === 'admin') {
            $data = $this->getAdminData();
        } elseif ($user->role === 'trader') {
            $data = $this->getTraderData($user);
        }

        // إرجاع الواجهة مع البيانات
        return view('dashboard.index', compact('data'));
    }

    /**
     * جلب بيانات لوحة تحكم الأدمن.
     *
     * @return array
     */
    private function getAdminData(): array
    {
        // ملاحظة: حساب الأرباح هنا افتراضي لأنه لا يوجد جدول للطلبات
        // يجب إنشاء جدول `orders` لحساب الأرباح الفعلية
        $total_profit = 0; // Order::sum('profit');

        return [
            'total_profit' => $total_profit,
            'total_products' => Product::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_traders' => User::where('role', 'trader')->count(),
            'recent_products' => Product::with('brand')->latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
        ];
    }

    /**
     * جلب بيانات لوحة تحكم التاجر.
     *
     * @param \App\Models\User $user
     * @return array
     */
    private function getTraderData(User $user): array
    {
        // جلب معرفات العلامات التجارية التابعة للتاجر
        $brandIds = $user->brands()->pluck('id');

        // ملاحظة: حساب الأرباح هنا افتراضي
        $my_profit = 0; // Order::whereIn('product_id', Product::whereIn('brand_id', $brandIds)->pluck('id'))->sum('profit');

        return [
            'my_profit' => $my_profit,
            'my_products_count' => Product::whereIn('brand_id', $brandIds)->count(),
            'my_reviews_count' => Review::whereHas('product', function ($query) use ($brandIds) {
                $query->whereIn('brand_id', $brandIds);
            })->count(),
            'my_recent_products' => Product::whereIn('brand_id', $brandIds)->latest()->take(5)->get(),
        ];
    }
}
