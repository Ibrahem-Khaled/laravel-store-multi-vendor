<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoints;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyManagementController extends Controller
{
    /**
     * لوحة التحكم الرئيسية لنقاط الولاء
     */
    public function dashboard()
    {
        try {
            // إحصائيات عامة
            $stats = [
                'total_users_with_points' => LoyaltyPoints::count(),
                'total_points_distributed' => LoyaltyPoints::sum('total_points'),
                'total_points_used' => LoyaltyPoints::sum('used_points'),
                'total_points_expired' => LoyaltyPoints::sum('expired_points'),
                'total_platform_contribution' => LoyaltyPoints::sum('platform_contribution'),
                'total_customer_contribution' => LoyaltyPoints::sum('customer_contribution'),
                'active_points' => LoyaltyPoints::sum('total_points') - LoyaltyPoints::sum('used_points') - LoyaltyPoints::sum('expired_points'),
            ];

            // إحصائيات المعاملات
            $transactionStats = [
                'total_transactions' => LoyaltyTransaction::count(),
                'earned_transactions' => LoyaltyTransaction::where('type', 'earned')->count(),
                'used_transactions' => LoyaltyTransaction::where('type', 'used')->count(),
                'expired_transactions' => LoyaltyTransaction::where('type', 'expired')->count(),
                'refunded_transactions' => LoyaltyTransaction::where('type', 'refunded')->count(),
            ];

            // أفضل المستخدمين بالنقاط
            $topUsers = LoyaltyPoints::with('user')
                ->orderBy('total_points', 'desc')
                ->limit(10)
                ->get();

            // المعاملات الأخيرة
            $recentTransactions = LoyaltyTransaction::with(['user', 'order', 'processedBy'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // إحصائيات شهرية (آخر 12 شهر)
            $monthlyStats = LoyaltyTransaction::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as transactions_count'),
                DB::raw('SUM(CASE WHEN type = "earned" THEN points ELSE 0 END) as points_earned'),
                DB::raw('SUM(CASE WHEN type = "used" THEN points ELSE 0 END) as points_used'),
                DB::raw('SUM(CASE WHEN type = "earned" THEN amount ELSE 0 END) as amount_earned'),
                DB::raw('SUM(CASE WHEN type = "used" THEN amount ELSE 0 END) as amount_used')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            return view('dashboard.loyalty-management.dashboard', compact(
                'stats',
                'transactionStats',
                'topUsers',
                'recentTransactions',
                'monthlyStats'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل لوحة التحكم: ' . $e->getMessage());
        }
    }

    /**
     * قائمة المستخدمين مع نقاط الولاء
     */
    public function users(Request $request)
    {
        try {
            $query = LoyaltyPoints::with('user');

            // فلترة حسب البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // فلترة حسب النقاط
            if ($request->filled('min_points')) {
                $query->where('total_points', '>=', $request->min_points);
            }
            if ($request->filled('max_points')) {
                $query->where('total_points', '<=', $request->max_points);
            }

            // ترتيب
            $sortBy = $request->get('sort', 'total_points');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $users = $query->paginate(15);

            return view('dashboard.loyalty-management.users', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل قائمة المستخدمين: ' . $e->getMessage());
        }
    }

    /**
     * تفاصيل مستخدم معين
     */
    public function userDetails($userId)
    {
        try {
            $user = User::with('loyaltyPoints')->findOrFail($userId);

            if (!$user->loyaltyPoints) {
                // إنشاء سجل نقاط الولاء إذا لم يكن موجوداً
                $user->loyaltyPoints = LoyaltyPoints::create([
                    'user_id' => $user->id,
                    'total_points' => 0,
                    'used_points' => 0,
                    'expired_points' => 0,
                    'platform_contribution' => 0,
                    'customer_contribution' => 0,
                ]);
            }

            // إحصائيات المستخدم
            $userStats = [
                'total_transactions' => $user->loyaltyTransactions()->count(),
                'earned_transactions' => $user->loyaltyTransactions()->where('type', 'earned')->count(),
                'used_transactions' => $user->loyaltyTransactions()->where('type', 'used')->count(),
                'expired_transactions' => $user->loyaltyTransactions()->where('type', 'expired')->count(),
                'total_amount_earned' => $user->loyaltyTransactions()->where('type', 'earned')->sum('amount'),
                'total_amount_used' => $user->loyaltyTransactions()->where('type', 'used')->sum('amount'),
            ];

            // المعاملات الأخيرة للمستخدم
            $recentTransactions = $user->loyaltyTransactions()
                ->with(['order', 'processedBy'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            // إحصائيات شهرية للمستخدم
            $monthlyStats = $user->loyaltyTransactions()
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as transactions_count'),
                    DB::raw('SUM(CASE WHEN type = "earned" THEN points ELSE 0 END) as points_earned'),
                    DB::raw('SUM(CASE WHEN type = "used" THEN points ELSE 0 END) as points_used')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return view('dashboard.loyalty-management.user-details', compact(
                'user',
                'userStats',
                'recentTransactions',
                'monthlyStats'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل تفاصيل المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * قائمة المعاملات
     */
    public function transactions(Request $request)
    {
        try {
            $query = LoyaltyTransaction::with(['user', 'order', 'processedBy']);

            // فلترة حسب البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('order', function ($orderQuery) use ($search) {
                          $orderQuery->where('order_number', 'like', "%{$search}%");
                      });
                });
            }

            // فلترة حسب النوع
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // فلترة حسب المصدر
            if ($request->filled('source')) {
                $query->where('source', $request->source);
            }

            // فلترة حسب التاريخ
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // ترتيب
            $sortBy = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $transactions = $query->paginate(20);

            return view('dashboard.loyalty-management.transactions', compact('transactions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل قائمة المعاملات: ' . $e->getMessage());
        }
    }

    /**
     * إضافة نقاط الولاء
     */
    public function addPoints(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'platform_contribution' => 'required|numeric|min:0',
            'customer_contribution' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ], [
            'user_id.required' => 'معرف المستخدم مطلوب',
            'user_id.exists' => 'المستخدم غير موجود',
            'points.required' => 'عدد النقاط مطلوب',
            'points.integer' => 'عدد النقاط يجب أن يكون رقماً صحيحاً',
            'points.min' => 'عدد النقاط يجب أن يكون أكبر من صفر',
            'platform_contribution.required' => 'مساهمة المنصة مطلوبة',
            'platform_contribution.numeric' => 'مساهمة المنصة يجب أن تكون رقماً',
            'platform_contribution.min' => 'مساهمة المنصة يجب أن تكون أكبر من أو تساوي صفر',
            'customer_contribution.required' => 'مساهمة العميل مطلوبة',
            'customer_contribution.numeric' => 'مساهمة العميل يجب أن تكون رقماً',
            'customer_contribution.min' => 'مساهمة العميل يجب أن تكون أكبر من أو تساوي صفر',
            'description.required' => 'وصف المعاملة مطلوب',
            'description.string' => 'وصف المعاملة يجب أن يكون نص',
            'description.max' => 'وصف المعاملة لا يجب أن يتجاوز 255 حرف',
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            // إنشاء أو الحصول على سجل نقاط الولاء
            $loyaltyPoints = $user->loyaltyPoints ?? LoyaltyPoints::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
                'platform_contribution' => 0,
                'customer_contribution' => 0,
            ]);

            DB::beginTransaction();

            try {
                // إضافة النقاط
                $loyaltyPoints->addPoints(
                    $request->points,
                    $request->platform_contribution,
                    $request->customer_contribution
                );

                // إنشاء معاملة إضافة النقاط
                LoyaltyTransaction::create([
                    'user_id' => $user->id,
                    'loyalty_points_id' => $loyaltyPoints->id,
                    'type' => LoyaltyTransaction::TYPE_EARNED,
                    'points' => $request->points,
                    'amount' => $request->points * 0.01,
                    'source' => LoyaltyTransaction::SOURCE_MANUAL,
                    'description' => $request->description,
                    'processed_by' => Auth::id(),
                    'expires_at' => now()->addYear(),
                    'metadata' => [
                        'platform_contribution' => $request->platform_contribution,
                        'customer_contribution' => $request->customer_contribution,
                        'added_by' => Auth::user()->name,
                        'added_at' => now()->toISOString(),
                    ]
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'تم إضافة النقاط بنجاح');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إضافة النقاط: ' . $e->getMessage());
        }
    }

    /**
     * حذف معاملة نقاط الولاء
     */
    public function deleteTransaction($transactionId)
    {
        try {
            $transaction = LoyaltyTransaction::findOrFail($transactionId);

            // يمكن حذف المعاملات اليدوية فقط
            if ($transaction->source !== LoyaltyTransaction::SOURCE_MANUAL) {
                return redirect()->back()->with('error', 'لا يمكن حذف هذه المعاملة');
            }

            DB::beginTransaction();

            try {
                // استرداد النقاط
                $loyaltyPoints = $transaction->loyaltyPoints;
                $loyaltyPoints->refundPoints($transaction->points);

                // حذف المعاملة
                $transaction->delete();

                DB::commit();

                return redirect()->back()->with('success', 'تم حذف المعاملة واسترداد النقاط بنجاح');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في حذف المعاملة: ' . $e->getMessage());
        }
    }

    /**
     * تصدير تقرير نقاط الولاء
     */
    public function exportReport(Request $request)
    {
        try {
            $query = LoyaltyTransaction::with(['user', 'order', 'processedBy']);

            // تطبيق نفس الفلاتر من صفحة المعاملات
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            if ($request->filled('source')) {
                $query->where('source', $request->source);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            // إنشاء ملف CSV
            $filename = 'loyalty_points_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($transactions) {
                $file = fopen('php://output', 'w');

                // إضافة BOM للدعم العربي
                fwrite($file, "\xEF\xBB\xBF");

                // رؤوس الأعمدة
                fputcsv($file, [
                    'ID',
                    'المستخدم',
                    'البريد الإلكتروني',
                    'النوع',
                    'النقاط',
                    'المبلغ',
                    'المصدر',
                    'الوصف',
                    'رقم الطلب',
                    'المعالج',
                    'تاريخ الانتهاء',
                    'تاريخ الإنشاء'
                ]);

                // البيانات
                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction->id,
                        $transaction->user->name ?? 'غير محدد',
                        $transaction->user->email ?? 'غير محدد',
                        $transaction->type,
                        $transaction->points,
                        $transaction->amount,
                        $transaction->source,
                        $transaction->description,
                        $transaction->order->order_number ?? 'غير محدد',
                        $transaction->processedBy->name ?? 'غير محدد',
                        $transaction->expires_at ? $transaction->expires_at->format('Y-m-d H:i:s') : 'غير محدد',
                        $transaction->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تصدير التقرير: ' . $e->getMessage());
        }
    }
}
