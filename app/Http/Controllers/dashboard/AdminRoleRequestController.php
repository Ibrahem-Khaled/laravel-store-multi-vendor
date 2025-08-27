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
    public function index(Request $request)
    {
        // الإحصائيات
        $stats = RoleChangeRequest::query()
            ->select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected")
            )
            ->first();

        // جلب الطلبات مع الفلترة والبحث
        $query = RoleChangeRequest::with('user')->latest();

        // الفلترة حسب الحالة (من التبويبات)
        $selectedStatus = $request->query('status', 'all');
        if (in_array($selectedStatus, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $selectedStatus);
        }

        // البحث
        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('dashboard.role_requests.index', compact('stats', 'requests', 'selectedStatus'));
    }

    /**
     * تحديث حالة الطلب (الموافقة أو الرفض).
     */
    public function update(Request $request, RoleChangeRequest $roleRequest)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        // ابدأ Transaction لضمان تنفيذ العمليتين معًا أو لا شيء
        DB::beginTransaction();
        try {
            // تحديث الطلب
            $roleRequest->update([
                'status' => $validated['status'],
                'admin_notes' => $validated['admin_notes'],
                'reviewed_by' => auth()->id(),
            ]);

            // إذا تمت الموافقة، قم بتغيير دور المستخدم
            if ($validated['status'] === 'approved') {
                $user = $roleRequest->user;
                $user->role = $roleRequest->requested_role;
                $user->save();

                // يمكنك هنا إضافة منطق إنشاء متجر للتاجر الجديد إذا لزم الأمر
                // if (!$user->store) {
                //     $user->store()->create(['name' => $roleRequest->store_name]);
                // }
            }

            DB::commit(); // تم كل شيء بنجاح

            return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack(); // حدث خطأ، تراجع عن كل شيء
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الطلب. يرجى المحاولة مرة أخرى.');
        }
    }
}
