<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\RoleChangeRequest;


class AdminRoleRequestController extends Controller
{
    /**
     * عرض صفحة إدارة طلبات تغيير الأدوار مع الإحصائيات والتصفية.
     */
    public function index(Request $request)
    {
        // التحقق من صحة قيمة الحالة المطلوبة
        $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'search' => 'nullable|string|max:255',
        ]);

        $selectedStatus = $request->query('status', 'pending'); // الحالة الافتراضية هي 'pending'
        $searchQuery = $request->query('search');

        // بناء استعلام الطلبات
        $query = RoleChangeRequest::with(['user', 'reviewer'])->latest();

        // تطبيق التصفية حسب الحالة
        if ($selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        // تطبيق البحث عن اسم المستخدم أو البريد الإلكتروني
        if ($searchQuery) {
            $query->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        // حساب الإحصائيات
        $stats = [
            'total' => RoleChangeRequest::count(),
            'pending' => RoleChangeRequest::where('status', 'pending')->count(),
            'approved' => RoleChangeRequest::where('status', 'approved')->count(),
            'rejected' => RoleChangeRequest::where('status', 'rejected')->count(),
        ];

        // مصفوفة لترجمة أسماء الأدوار والحالات
        $roleNames = $this->getRoleTranslations();
        $statusNames = $this->getStatusTranslations();


        return view('dashboard.role_requests.index', compact('requests', 'stats', 'selectedStatus', 'roleNames', 'statusNames'));
    }

    /**
     * الموافقة على الطلب وتغيير دور المستخدم.
     */
    public function approve(RoleChangeRequest $request)
    {
        // لا يمكن الموافقة إلا على الطلبات المعلقة
        if ($request->status !== 'pending') {
            return back()->with('error', 'لا يمكن التعامل مع هذا الطلب.');
        }

        try {
            DB::transaction(function () use ($request) {
                // 1. تحديث دور المستخدم
                $request->user->update(['role' => $request->requested_role]);

                // 2. تحديث حالة الطلب
                $request->update([
                    'status' => 'approved',
                    'reviewed_by' => Auth::id(),
                    'admin_notes' => 'تمت الموافقة على الطلب.'
                ]);
            });

            // هنا يمكنك إرسال إشعار أو إيميل للمستخدم لإبلاغه بالموافقة
            return back()->with('success', 'تمت الموافقة على الطلب وتحديث دور المستخدم بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الموافقة على الطلب. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * رفض الطلب مع تسجيل السبب.
     */
    public function reject(Request $requestData, RoleChangeRequest $request)
    {
        // لا يمكن رفض إلا الطلبات المعلقة
        if ($request->status !== 'pending') {
            return back()->with('error', 'لا يمكن التعامل مع هذا الطلب.');
        }

        $requestData->validate(['admin_notes' => 'required|string|min:10|max:1000']);

        $request->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'admin_notes' => $requestData->admin_notes,
        ]);

        // هنا يمكنك إرسال إشعار أو إيميل للمستخدم لإبلاغه بالرفض والسبب
        return back()->with('success', 'تم رفض الطلب بنجاح.');
    }

    /**
     * حذف سجل الطلب من قاعدة البيانات.
     */
    public function destroy(RoleChangeRequest $request)
    {
        $request->delete();
        return back()->with('success', 'تم حذف سجل الطلب بنجاح.');
    }


    /**
     * دالة مساعدة لترجمة الأدوار للعربية.
     */
    private function getRoleTranslations(): array
    {
        return [
            'admin' => 'مدير',
            'moderator' => 'مشرف',
            'user' => 'مستخدم',
            'trader' => 'تاجر',
        ];
    }

    /**
     * دالة مساعدة لترجمة حالات الطلب للعربية.
     */
    private function getStatusTranslations(): array
    {
        return [
            'pending' => 'معلق',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
        ];
    }
}
