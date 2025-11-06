<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * إنشاء تقييم جديد لمنتج
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'rate' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً',
                ], 401);
            }

            $productId = $request->input('product_id');
            $rate = $request->input('rate');
            $comment = $request->input('comment');

            // حساب عدد التقييمات السابقة للمستخدم لهذا المنتج
            // هذا سيحدد عدد المشتريات المطلوبة للتقييم الجديد
            $previousReviewsCount = Review::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->count();

            // حساب عدد المشتريات المكتملة للمستخدم لهذا المنتج
            // نعد عدد الطلبات المكتملة التي تحتوي على هذا المنتج
            $purchaseCount = OrderItem::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', 'completed'); // فقط الطلبات المكتملة
            })
            ->where('product_id', $productId)
            ->count(); // عدد المرات التي اشترى فيها المنتج

            // التحقق: التقييم يحتاج إلى عدد مشتريات يساوي (عدد التقييمات السابقة + 1)
            $requiredPurchases = $previousReviewsCount + 1;

            // تحديد حالة الموافقة بناءً على عدد المشتريات
            $isApproved = $purchaseCount >= $requiredPurchases;

            // إنشاء التقييم
            $review = Review::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'rate' => $rate,
                'comment' => $comment,
                'is_approved' => $isApproved,
            ]);

            $message = $isApproved
                ? 'تم إضافة التقييم بنجاح وتمت الموافقة عليه.'
                : 'تم إضافة التقييم بنجاح ولكنه في انتظار الموافقة. تحتاج إلى شراء المنتج ' . $requiredPurchases . ' مرة(مرات) للموافقة عليه.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'review' => [
                        'id' => $review->id,
                        'user_id' => $review->user_id,
                        'product_id' => $review->product_id,
                        'rate' => $review->rate,
                        'comment' => $review->comment,
                        'is_approved' => $review->is_approved,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    ],
                    'purchase_count' => $purchaseCount,
                    'required_purchases' => $requiredPurchases,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة التقييم',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على تقييمات منتج معين
     */
    public function getProductReviews(Request $request, $productId)
    {
        try {
            $validator = Validator::make(['product_id' => $productId], [
                'product_id' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود',
                ], 404);
            }

            $perPage = $request->input('per_page', 10);
            $onlyApproved = $request->input('approved_only', true);

            $query = Review::with(['user:id,name,username,avatar'])
                ->where('product_id', $productId);

            // إذا كان فقط الموافق عليها
            if ($onlyApproved) {
                $query->where('is_approved', true);
            }

            $reviews = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // حساب الإحصائيات
            $statistics = [
                'total_reviews' => Review::where('product_id', $productId)->count(),
                'approved_reviews' => Review::where('product_id', $productId)->where('is_approved', true)->count(),
                'average_rating' => Review::where('product_id', $productId)->where('is_approved', true)->avg('rate'),
                'rating_distribution' => Review::where('product_id', $productId)
                    ->where('is_approved', true)
                    ->selectRaw('rate, COUNT(*) as count')
                    ->groupBy('rate')
                    ->pluck('count', 'rate')
                    ->toArray(),
            ];

            $formattedReviews = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->name,
                        'username' => $review->user->username,
                        'avatar' => $review->user->avatar_url,
                    ],
                    'rate' => $review->rate,
                    'comment' => $review->comment,
                    'is_approved' => $review->is_approved,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب التقييمات بنجاح',
                'data' => $formattedReviews,
                'statistics' => $statistics,
                'meta' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التقييمات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تحديث تقييم موجود
     */
    public function update(Request $request, $reviewId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rate' => 'sometimes|required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً',
                ], 401);
            }

            $review = Review::where('id', $reviewId)
                ->where('user_id', $user->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'التقييم غير موجود أو ليس لديك صلاحية للتعديل',
                ], 404);
            }

            // تحديث البيانات
            if ($request->has('rate')) {
                $review->rate = $request->input('rate');
            }

            if ($request->has('comment')) {
                $review->comment = $request->input('comment');
            }

            // إعادة التحقق من الموافقة عند التحديث
            $previousReviewsCount = Review::where('user_id', $user->id)
                ->where('product_id', $review->product_id)
                ->where('id', '<', $review->id)
                ->count();

            $purchaseCount = OrderItem::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', 'completed');
            })
            ->where('product_id', $review->product_id)
            ->count(); // عدد المرات التي اشترى فيها المنتج

            $requiredPurchases = $previousReviewsCount + 1;
            $review->is_approved = $purchaseCount >= $requiredPurchases;

            $review->save();

            return response()->json([
                'success' => true,
                'message' => $review->is_approved
                    ? 'تم تحديث التقييم بنجاح وتمت الموافقة عليه.'
                    : 'تم تحديث التقييم بنجاح ولكنه في انتظار الموافقة.',
                'data' => [
                    'review' => [
                        'id' => $review->id,
                        'user_id' => $review->user_id,
                        'product_id' => $review->product_id,
                        'rate' => $review->rate,
                        'comment' => $review->comment,
                        'is_approved' => $review->is_approved,
                        'updated_at' => $review->updated_at->format('Y-m-d H:i:s'),
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث التقييم',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * حذف تقييم
     */
    public function destroy($reviewId)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً',
                ], 401);
            }

            $review = Review::where('id', $reviewId)
                ->where('user_id', $user->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'التقييم غير موجود أو ليس لديك صلاحية للحذف',
                ], 404);
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف التقييم بنجاح',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف التقييم',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على تقييمات المستخدم
     */
    public function getUserReviews(Request $request)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً',
                ], 401);
            }

            $perPage = $request->input('per_page', 10);

            $reviews = Review::with(['product:id,name', 'product.images:id,product_id,path'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $formattedReviews = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'product' => [
                        'id' => $review->product->id,
                        'name' => $review->product->name,
                        'images' => $review->product->images ? $review->product->images->pluck('path')->map(fn($path) => asset('storage/' . $path))->toArray() : [],
                    ],
                    'rate' => $review->rate,
                    'comment' => $review->comment,
                    'is_approved' => $review->is_approved,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب تقييماتك بنجاح',
                'data' => $formattedReviews,
                'meta' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التقييمات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

