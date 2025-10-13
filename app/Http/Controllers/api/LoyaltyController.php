<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoints;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoyaltyController extends Controller
{
    /**
     * الحصول على نقاط الولاء للمستخدم الحالي
     */
    public function getLoyaltyPoints(Request $request)
    {
        try {
            $user = Auth::user();

            // إنشاء سجل نقاط الولاء إذا لم يكن موجوداً
            $loyaltyPoints = $user->loyaltyPoints ?? LoyaltyPoints::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
                'platform_contribution' => 0,
                'customer_contribution' => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_id' => $user->id,
                    'total_points' => $loyaltyPoints->total_points,
                    'available_points' => $loyaltyPoints->available_points,
                    'used_points' => $loyaltyPoints->used_points,
                    'expired_points' => $loyaltyPoints->expired_points,
                    'platform_contribution' => $loyaltyPoints->platform_contribution,
                    'customer_contribution' => $loyaltyPoints->customer_contribution,
                    'total_contribution' => $loyaltyPoints->total_contribution,
                    'last_earned_at' => $loyaltyPoints->last_earned_at,
                    'last_used_at' => $loyaltyPoints->last_used_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في الحصول على نقاط الولاء',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على تاريخ معاملات نقاط الولاء
     */
    public function getLoyaltyTransactions(Request $request)
    {
        try {
            $user = Auth::user();

            $perPage = $request->get('per_page', 15);
            $type = $request->get('type'); // earned, used, expired, refunded

            $query = $user->loyaltyTransactions()->with(['order', 'processedBy']);

            if ($type) {
                $query->where('type', $type);
            }

            $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'type' => $transaction->type,
                        'points' => $transaction->points,
                        'amount' => $transaction->amount,
                        'source' => $transaction->source,
                        'description' => $transaction->description,
                        'status' => $transaction->status,
                        'order_id' => $transaction->order_id,
                        'order_number' => $transaction->order?->order_number,
                        'processed_by' => $transaction->processedBy?->name,
                        'expires_at' => $transaction->expires_at,
                        'created_at' => $transaction->created_at,
                        'metadata' => $transaction->metadata,
                    ];
                }),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'last_page' => $transactions->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في الحصول على تاريخ المعاملات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * استخدام نقاط الولاء في الطلب
     */
    public function useLoyaltyPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:1',
            'order_id' => 'required|exists:orders,id',
        ], [
            'points.required' => 'عدد النقاط مطلوب',
            'points.integer' => 'عدد النقاط يجب أن يكون رقماً صحيحاً',
            'points.min' => 'عدد النقاط يجب أن يكون أكبر من صفر',
            'order_id.required' => 'رقم الطلب مطلوب',
            'order_id.exists' => 'الطلب غير موجود',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $points = $request->points;
            $orderId = $request->order_id;

            // التحقق من أن الطلب يخص المستخدم
            $order = Order::where('id', $orderId)
                ->where('user_id', $user->id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'الطلب غير موجود أو لا يخصك'
                ], 404);
            }

            // التحقق من حالة الطلب
            if ($order->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'لا يمكن استخدام النقاط في هذا الطلب'
                ], 400);
            }

            $loyaltyPoints = $user->loyaltyPoints;

            if (!$loyaltyPoints || $loyaltyPoints->available_points < $points) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'النقاط المتاحة غير كافية',
                    'available_points' => $loyaltyPoints?->available_points ?? 0
                ], 400);
            }

            DB::beginTransaction();

            try {
                // استخدام النقاط
                $loyaltyPoints->usePoints($points);

                // إنشاء معاملة استخدام النقاط
                LoyaltyTransaction::create([
                    'user_id' => $user->id,
                    'loyalty_points_id' => $loyaltyPoints->id,
                    'type' => LoyaltyTransaction::TYPE_USED,
                    'points' => $points,
                    'amount' => $points * 0.01, // كل نقطة = 0.01 ريال
                    'source' => LoyaltyTransaction::SOURCE_ORDER,
                    'description' => "استخدام {$points} نقطة في الطلب رقم {$order->order_number}",
                    'order_id' => $orderId,
                    'metadata' => [
                        'order_number' => $order->order_number,
                        'points_value' => $points * 0.01,
                        'used_at' => now()->toISOString(),
                    ]
                ]);

                // تحديث الطلب لخصم قيمة النقاط
                $pointsValue = $points * 0.01; // كل نقطة = 0.01 ريال
                $order->update([
                    'grand_total' => max(0, $order->grand_total - $pointsValue),
                    'loyalty_points_used' => $points,
                    'loyalty_discount' => $pointsValue,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم استخدام النقاط بنجاح',
                    'data' => [
                        'points_used' => $points,
                        'points_value' => $pointsValue,
                        'remaining_points' => $loyaltyPoints->available_points,
                        'order_total' => $order->grand_total,
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في استخدام النقاط',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة نقاط الولاء يدوياً (للمشرفين)
     */
    public function addLoyaltyPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = Auth::user();
            $userId = $request->user_id;
            $points = $request->points;
            $platformContribution = $request->platform_contribution;
            $customerContribution = $request->customer_contribution;
            $description = $request->description;

            // التحقق من صلاحيات المشرف
            if (!in_array($admin->role, ['admin', 'super_admin'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ليس لديك صلاحية لإضافة نقاط الولاء'
                ], 403);
            }

            $user = \App\Models\User::find($userId);

            // إنشاء أو الحصول على سجل نقاط الولاء
            $loyaltyPoints = $user->loyaltyPoints ?? LoyaltyPoints::create([
                'user_id' => $userId,
                'total_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
                'platform_contribution' => 0,
                'customer_contribution' => 0,
            ]);

            DB::beginTransaction();

            try {
                // إضافة النقاط
                $loyaltyPoints->addPoints($points, $platformContribution, $customerContribution);

                // إنشاء معاملة إضافة النقاط
                LoyaltyTransaction::create([
                    'user_id' => $userId,
                    'loyalty_points_id' => $loyaltyPoints->id,
                    'type' => LoyaltyTransaction::TYPE_EARNED,
                    'points' => $points,
                    'amount' => $points * 0.01,
                    'source' => LoyaltyTransaction::SOURCE_MANUAL,
                    'description' => $description,
                    'processed_by' => $admin->id,
                    'expires_at' => now()->addYear(), // انتهاء صلاحية بعد سنة
                    'metadata' => [
                        'platform_contribution' => $platformContribution,
                        'customer_contribution' => $customerContribution,
                        'added_by' => $admin->name,
                        'added_at' => now()->toISOString(),
                    ]
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم إضافة النقاط بنجاح',
                    'data' => [
                        'user_id' => $userId,
                        'user_name' => $user->name,
                        'points_added' => $points,
                        'platform_contribution' => $platformContribution,
                        'customer_contribution' => $customerContribution,
                        'total_points' => $loyaltyPoints->total_points,
                        'available_points' => $loyaltyPoints->available_points,
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في إضافة النقاط',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حساب نقاط الولاء عند إتمام الطلب
     */
    public static function calculateLoyaltyPoints(Order $order)
    {
        try {
            $user = $order->user;
            $orderTotal = $order->grand_total;

            // معدل النقاط: 1 نقطة لكل ريال
            $points = (int) $orderTotal;

            // توزيع المساهمة: 70% منصة، 30% عميل
            $platformContribution = $orderTotal * 0.7;
            $customerContribution = $orderTotal * 0.3;

            // إنشاء أو الحصول على سجل نقاط الولاء
            $loyaltyPoints = $user->loyaltyPoints ?? LoyaltyPoints::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
                'platform_contribution' => 0,
                'customer_contribution' => 0,
            ]);

            // إضافة النقاط
            $loyaltyPoints->addPoints($points, $platformContribution, $customerContribution);

            // إنشاء معاملة كسب النقاط
            LoyaltyTransaction::create([
                'user_id' => $user->id,
                'loyalty_points_id' => $loyaltyPoints->id,
                'type' => LoyaltyTransaction::TYPE_EARNED,
                'points' => $points,
                'amount' => $orderTotal,
                'source' => LoyaltyTransaction::SOURCE_ORDER,
                'description' => "كسب {$points} نقطة من الطلب رقم {$order->order_number}",
                'order_id' => $order->id,
                'expires_at' => now()->addYear(), // انتهاء صلاحية بعد سنة
                'metadata' => [
                    'order_number' => $order->order_number,
                    'order_total' => $orderTotal,
                    'platform_contribution' => $platformContribution,
                    'customer_contribution' => $customerContribution,
                    'earned_at' => now()->toISOString(),
                ]
            ]);

            return [
                'points' => $points,
                'platform_contribution' => $platformContribution,
                'customer_contribution' => $customerContribution,
            ];
        } catch (\Exception $e) {
            \Log::error('خطأ في حساب نقاط الولاء: ' . $e->getMessage());
            return null;
        }
    }
}
