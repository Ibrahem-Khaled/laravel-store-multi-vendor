<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ShippingProof;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ShippingProofController extends Controller
{
    /**
     * جلب جميع طلبات الشحن للمستخدم الحالي
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $status = $request->input('status'); // pending, approved, rejected

            $query = ShippingProof::where('user_id', $user->id)
                ->with(['admin:id,name,username'])
                ->latest();

            if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $status);
            }

            $proofs = $query->get()->map(function ($proof) {
                return [
                    'id' => $proof->id,
                    'amount' => (float) $proof->amount,
                    'proof_image' => $proof->proof_image_url,
                    'status' => $proof->status,
                    'admin_notes' => $proof->admin_notes,
                    'coins_added' => $proof->coins_added,
                    'admin' => $proof->admin ? [
                        'id' => $proof->admin->id,
                        'name' => $proof->admin->name,
                        'username' => $proof->admin->username,
                    ] : null,
                    'approved_at' => $proof->approved_at ? $proof->approved_at->format('Y-m-d H:i:s') : null,
                    'rejected_at' => $proof->rejected_at ? $proof->rejected_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $proof->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $proof->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب طلبات الشحن بنجاح',
                'data' => $proofs
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب طلبات الشحن',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء طلب شحن جديد
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:0.01',
                'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // رفع صورة الأصل
            $proofImagePath = $request->file('proof_image')->store('shipping_proofs', 'public');

            $shippingProof = ShippingProof::create([
                'user_id' => $user->id,
                'amount' => $validator->validated()['amount'],
                'proof_image' => $proofImagePath,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء طلب الشحن بنجاح، سيتم مراجعته من قبل الإدارة',
                'data' => [
                    'id' => $shippingProof->id,
                    'amount' => (float) $shippingProof->amount,
                    'proof_image' => $shippingProof->proof_image_url,
                    'status' => $shippingProof->status,
                    'created_at' => $shippingProof->created_at->format('Y-m-d H:i:s'),
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء طلب الشحن',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض طلب شحن معين
     */
    public function show($id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $proof = ShippingProof::with(['user:id,name,username', 'admin:id,name,username'])
                ->findOrFail($id);

            // التحقق من أن المستخدم هو صاحب الطلب أو أدمن
            if ($proof->user_id !== $user->id && $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لعرض هذا الطلب'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب طلب الشحن بنجاح',
                'data' => [
                    'id' => $proof->id,
                    'user' => [
                        'id' => $proof->user->id,
                        'name' => $proof->user->name,
                        'username' => $proof->user->username,
                    ],
                    'amount' => (float) $proof->amount,
                    'proof_image' => $proof->proof_image_url,
                    'status' => $proof->status,
                    'admin_notes' => $proof->admin_notes,
                    'coins_added' => $proof->coins_added,
                    'admin' => $proof->admin ? [
                        'id' => $proof->admin->id,
                        'name' => $proof->admin->name,
                        'username' => $proof->admin->username,
                    ] : null,
                    'approved_at' => $proof->approved_at ? $proof->approved_at->format('Y-m-d H:i:s') : null,
                    'rejected_at' => $proof->rejected_at ? $proof->rejected_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $proof->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $proof->updated_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'طلب الشحن غير موجود'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب طلب الشحن',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الموافقة على طلب شحن (للأدمن فقط)
     */
    public function approve(Request $request, $id)
    {
        try {
            $admin = auth()->guard('api')->user();

            if (!$admin || $admin->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للموافقة على الطلبات'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'coins_amount' => 'required|integer|min:1',
                'admin_notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $proof = ShippingProof::findOrFail($id);

            if ($proof->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن الموافقة على طلب تم معالجته مسبقاً'
                ], 400);
            }

            DB::beginTransaction();

            try {
                // تحديث حالة الطلب
                $proof->update([
                    'status' => 'approved',
                    'admin_id' => $admin->id,
                    'admin_notes' => $validator->validated()['admin_notes'],
                    'coins_added' => $validator->validated()['coins_amount'],
                    'approved_at' => now(),
                ]);

                // إضافة العملات إلى حساب المستخدم
                $user = User::findOrFail($proof->user_id);
                $user->increment('coins', $validator->validated()['coins_amount']);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'تم الموافقة على الطلب وإضافة العملات بنجاح',
                    'data' => [
                        'id' => $proof->id,
                        'status' => $proof->status,
                        'coins_added' => $proof->coins_added,
                        'user_new_balance' => $user->coins,
                        'approved_at' => $proof->approved_at->format('Y-m-d H:i:s'),
                    ]
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'طلب الشحن غير موجود'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الموافقة على الطلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفض طلب شحن (للأدمن فقط)
     */
    public function reject(Request $request, $id)
    {
        try {
            $admin = auth()->guard('api')->user();

            if (!$admin || $admin->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لرفض الطلبات'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'admin_notes' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $proof = ShippingProof::findOrFail($id);

            if ($proof->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن رفض طلب تم معالجته مسبقاً'
                ], 400);
            }

            $proof->update([
                'status' => 'rejected',
                'admin_id' => $admin->id,
                'admin_notes' => $validator->validated()['admin_notes'],
                'rejected_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم رفض الطلب بنجاح',
                'data' => [
                    'id' => $proof->id,
                    'status' => $proof->status,
                    'admin_notes' => $proof->admin_notes,
                    'rejected_at' => $proof->rejected_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'طلب الشحن غير موجود'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفض الطلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب جميع طلبات الشحن (للأدمن فقط)
     */
    public function adminIndex(Request $request)
    {
        try {
            $admin = auth()->guard('api')->user();

            if (!$admin || $admin->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لعرض جميع الطلبات'
                ], 403);
            }

            $status = $request->input('status'); // pending, approved, rejected
            $userId = $request->input('user_id');

            $query = ShippingProof::with(['user:id,name,username,email', 'admin:id,name,username'])
                ->latest();

            if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $status);
            }

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $proofs = $query->paginate($request->input('per_page', 15));

            $formattedProofs = $proofs->map(function ($proof) {
                return [
                    'id' => $proof->id,
                    'user' => [
                        'id' => $proof->user->id,
                        'name' => $proof->user->name,
                        'username' => $proof->user->username,
                        'email' => $proof->user->email,
                    ],
                    'amount' => (float) $proof->amount,
                    'proof_image' => $proof->proof_image_url,
                    'status' => $proof->status,
                    'admin_notes' => $proof->admin_notes,
                    'coins_added' => $proof->coins_added,
                    'admin' => $proof->admin ? [
                        'id' => $proof->admin->id,
                        'name' => $proof->admin->name,
                        'username' => $proof->admin->username,
                    ] : null,
                    'approved_at' => $proof->approved_at ? $proof->approved_at->format('Y-m-d H:i:s') : null,
                    'rejected_at' => $proof->rejected_at ? $proof->rejected_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $proof->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $proof->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب طلبات الشحن بنجاح',
                'data' => $formattedProofs,
                'meta' => [
                    'current_page' => $proofs->currentPage(),
                    'last_page' => $proofs->lastPage(),
                    'per_page' => $proofs->perPage(),
                    'total' => $proofs->total(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب طلبات الشحن',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
