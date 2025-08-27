<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RoleChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleChangeRequestController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // ابحث عن آخر طلب قدمه المستخدم
        $latestRequest = $user->roleChangeRequests()->latest()->first();

        // إذا كان لدى المستخدم طلب، قم بإرجاعه
        if ($latestRequest) {
            return response()->json([
                'has_request' => true,
                'request' => $latestRequest,
            ]);
        }

        // إذا لم يكن لديه أي طلبات، أرجع استجابة تدل على ذلك
        return response()->json([
            'has_request' => false,
            'request' => null,
            'message' => 'يمكنك تقديم طلب جديد.'
        ]);
    }

    /**
     * تخزين طلب جديد لتغيير الدور.
     * (هذه الدالة تبقى كما هي من المرة السابقة)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        // 1. التحقق من عدم وجود طلب سابق "قيد الانتظار"
        $existingRequest = RoleChangeRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved']) // نمنع إنشاء طلب جديد إذا كان لديه طلب مقبول أو قيد الانتظar
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status == 'pending') {
                return response()->json(['message' => 'لديك طلب قائم بالفعل قيد المراجعة.'], 422);
            }
            if ($existingRequest->status == 'approved') {
                return response()->json(['message' => 'لقد تمت الموافقة على طلبك بالفعل.'], 422);
            }
        }

        // 2. التحقق من المدخلات (Validation)
        $validated = $request->validate([
            'requested_role' => ['required', Rule::in(['trader'])],
            'reason' => 'nullable|string|max:1000',
            'full_name' => 'required_if:requested_role,trader|string|max:255',
            'national_id_number' => 'required_if:requested_role,trader|string|max:50',
            'national_id_image' => 'required_if:requested_role,trader|image|mimes:jpeg,png,jpg|max:2048',
            'store_name' => 'required_if:requested_role,trader|string|max:255',
            'store_description' => 'nullable|string|max:2000',
            'commercial_registration_number' => 'required_if:requested_role,trader|string|max:50',
            'commercial_registration_image' => 'required_if:requested_role,trader|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'bank_account_number' => 'required_if:requested_role,trader|string|max:100',
            'bank_name' => 'required_if:requested_role,trader|string|max:100',
        ]);

        $data = $validated;
        $data['user_id'] = $user->id;

        // 3. التعامل مع رفع الملفات
        if ($request->hasFile('national_id_image')) {
            $data['national_id_image_path'] = $request->file('national_id_image')->store('role_requests/ids', 'public');
        }

        if ($request->hasFile('commercial_registration_image')) {
            $data['commercial_registration_image_path'] = $request->file('commercial_registration_image')->store('role_requests/commercial', 'public');
        }

        // 4. إنشاء الطلب في قاعدة البيانات
        $roleRequest = RoleChangeRequest::create($data);

        return response()->json([
            'message' => 'تم استلام طلبك بنجاح، ستتم مراجعته من قبل الإدارة.',
            'request' => $roleRequest
        ], 201);
    }
}
