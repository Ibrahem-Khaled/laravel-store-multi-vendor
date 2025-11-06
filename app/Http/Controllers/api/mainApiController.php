<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Feature;
use App\Models\MerchantProfile;
use App\Models\Notification;
use App\Models\PopularSearch;
use App\Models\Product;
use App\Models\SearchLog;
use App\Models\SlideShow;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class mainApiController extends Controller
{

    public function cities()
    {
        $cities = City::with('neighborhoods')->get();
        if (!$cities) {
            return response()->json(['message' => 'No cities found'], 404);
        }
        return response()->json($cities);
    }


    public function Categories()
    {
        $categories = Category::all();
        if (!$categories) {
            return response()->json(['message' => 'No categories found'], 404);
        }
        return response()->json($categories);
    }

    public function SubCategories(Category $category)
    {
        $subCategories = $category->subCategories;
        return response()->json($subCategories);
    }

    public function getFeatures()
    {
        $features = Feature::all();
        return response()->json($features);
    }

    /**
     * البحث عن المنتجات (Legacy - للتوافق مع الإصدارات القديمة)
     */
    public function searchProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();
        $searchQuery = $validated['query'];

        $query = Product::query();
        // ->where('is_active', true)
        // ->where('is_approved', true);

        $query->where(function (Builder $q) use ($searchQuery) {
            $q->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('description', 'LIKE', "%{$searchQuery}%");

            if (is_numeric($searchQuery)) {
                $q->orWhere('price', '=', (float)$searchQuery);
            }
        });

        $products = $query->with(['brand', 'images'])->latest()->paginate(15);

        // تسجيل عملية البحث
        $userId = auth()->guard('api')->id();
        SearchLog::log($searchQuery, 'product', $products->total(), $userId);
        PopularSearch::updateOrCreatePopular($searchQuery, 'product', $products->total());

        return response()->json($products);
    }

    /**
     * البحث الشامل (منتجات + تجار)
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255|min:2',
            'type' => 'nullable|in:product,merchant,all',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $searchQuery = $validated['query'];
        $type = $validated['type'] ?? 'all';
        $limit = $validated['limit'] ?? 15;

        $userId = auth()->guard('api')->id();
        $results = [
            'products' => [],
            'merchants' => [],
            'total' => 0,
        ];

        // البحث عن المنتجات
        if ($type === 'all' || $type === 'product') {
            $productQuery = Product::query();

            $productQuery->where(function (Builder $q) use ($searchQuery) {
                $q->where('name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('description', 'LIKE', "%{$searchQuery}%");

                if (is_numeric($searchQuery)) {
                    $q->orWhere('price', '=', (float)$searchQuery);
                }
            });

            $products = $productQuery->with(['brand', 'images'])
                ->latest()
                ->limit($limit)
                ->get();

            $results['products'] = $products;
            $results['total'] += $products->count();
        }

        // البحث عن التجار
        if ($type === 'all' || $type === 'merchant') {
            $merchantQuery = MerchantProfile::query()
                ->with(['user']);

            $merchantQuery->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('username', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('email', 'LIKE', "%{$searchQuery}%");
            });

            $merchants = $merchantQuery->limit($limit)->get();

            // تنسيق بيانات التجار
            $results['merchants'] = $merchants->map(function ($merchant) {
                return [
                    'id' => $merchant->id,
                    'user_id' => $merchant->user_id,
                    'name' => $merchant->user->name ?? 'Unknown',
                    'username' => $merchant->user->username ?? null,
                    'email' => $merchant->user->email ?? null,
                    'avatar' => $merchant->user->avatar ? asset('storage/' . $merchant->user->avatar) : null,
                    'default_commission_rate' => $merchant->default_commission_rate,
                    'created_at' => $merchant->created_at->format('Y-m-d H:i:s'),
                ];
            });

            $results['total'] += $merchants->count();
        }

        // تسجيل عملية البحث
        SearchLog::log($searchQuery, $type, $results['total'], $userId);
        PopularSearch::updateOrCreatePopular($searchQuery, $type, $results['total']);

        return response()->json([
            'success' => true,
            'message' => 'تم البحث بنجاح',
            'data' => $results,
            'query' => $searchQuery,
            'type' => $type,
        ]);
    }

    /**
     * البحث عن التجار فقط
     */
    public function searchMerchants(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255|min:2',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $searchQuery = $validated['query'];
        $limit = $validated['limit'] ?? 15;

        $merchantQuery = MerchantProfile::query()
            ->with(['user']);

        $merchantQuery->whereHas('user', function ($q) use ($searchQuery) {
            $q->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('username', 'LIKE', "%{$searchQuery}%")
                ->orWhere('email', 'LIKE', "%{$searchQuery}%");
        });

        $merchants = $merchantQuery->limit($limit)->get();

        // تنسيق بيانات التجار
        $formattedMerchants = $merchants->map(function ($merchant) {
            return [
                'id' => $merchant->id,
                'user_id' => $merchant->user_id,
                'name' => $merchant->user->name ?? 'Unknown',
                'username' => $merchant->user->username ?? null,
                'email' => $merchant->user->email ?? null,
                'avatar' => $merchant->user->avatar ? asset('storage/' . $merchant->user->avatar) : null,
                'default_commission_rate' => $merchant->default_commission_rate,
                'created_at' => $merchant->created_at->format('Y-m-d H:i:s'),
            ];
        });

        // تسجيل عملية البحث
        $userId = auth()->guard('api')->id();
        SearchLog::log($searchQuery, 'merchant', $merchants->count(), $userId);
        PopularSearch::updateOrCreatePopular($searchQuery, 'merchant', $merchants->count());

        return response()->json([
            'success' => true,
            'message' => 'تم البحث عن التجار بنجاح',
            'data' => $formattedMerchants,
            'total' => $merchants->count(),
        ]);
    }

    /**
     * الحصول على عمليات البحث الأكثر شيوعاً
     */
    public function popularSearches(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|in:product,merchant,all',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $request->input('type', 'all');
        $limit = $request->input('limit', 10);

        $popularSearches = PopularSearch::mostPopular($type === 'all' ? null : $type, $limit)->get();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب عمليات البحث الأكثر شيوعاً بنجاح',
            'data' => $popularSearches->map(function ($search) {
                return [
                    'query' => $search->query,
                    'type' => $search->type,
                    'search_count' => $search->search_count,
                    'results_count' => $search->results_count,
                    'last_searched_at' => $search->last_searched_at ? $search->last_searched_at->format('Y-m-d H:i:s') : null,
                ];
            }),
        ]);
    }

    /**
     * الحصول على جميع التجار المفعلين
     */
    public function getMerchants(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'city' => 'nullable|string',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
                'search' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $limit = $request->input('limit', 20);
            $search = $request->input('search');
            $city = $request->input('city');

            $query = MerchantProfile::query()
                ->with(['user' => function ($q) {
                    $q->select('id', 'name', 'username', 'email', 'phone', 'avatar', 'status', 'bio', 'address', 'country');
                }, 'user.addresses' => function ($q) {
                    $q->select('id', 'user_id', 'city', 'neighborhood', 'address');
                }])
                ->whereHas('user', function ($q) {
                    $q->where('status', 'active'); // فقط المستخدمين المفعلين
                });

            // البحث في اسم المستخدم أو البريد الإلكتروني
            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            // فلترة حسب المدينة
            if ($city) {
                $query->whereHas('user.addresses', function ($q) use ($city) {
                    $q->where('city', 'LIKE', "%{$city}%");
                });
            }

            $merchants = $query->latest()->paginate($limit);

            $formattedMerchants = $merchants->map(function ($merchant) {
                // الحصول على المدينة والحي من العنوان الرئيسي
                $primaryAddress = $merchant->user->addresses->first();
                $cityName = $primaryAddress ? $primaryAddress->city : null;
                $neighborhoodName = $primaryAddress ? $primaryAddress->neighborhood : null;
                $fullAddress = $primaryAddress ? $primaryAddress->address : null;

                return [
                    'id' => $merchant->id,
                    'user_id' => $merchant->user_id,
                    'name' => $merchant->user->name ?? 'Unknown',
                    'username' => $merchant->user->username ?? null,
                    'email' => $merchant->user->email ?? null,
                    'phone' => $merchant->user->phone ?? null,
                    'avatar' => $merchant->user->avatar ? asset('storage/' . $merchant->user->avatar) : null,
                    'bio' => $merchant->user->bio ?? null,
                    'address' => $fullAddress ?? $merchant->user->address ?? null,
                    'country' => $merchant->user->country ?? null,
                    'city' => $cityName,
                    'neighborhood' => $neighborhoodName,
                    'default_commission_rate' => $merchant->default_commission_rate ?? 0,
                    'payout_bank_name' => $merchant->payout_bank_name ?? null,
                    'created_at' => $merchant->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $merchant->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب التجار بنجاح',
                'data' => $formattedMerchants,
                'meta' => [
                    'current_page' => $merchants->currentPage(),
                    'last_page' => $merchants->lastPage(),
                    'per_page' => $merchants->perPage(),
                    'total' => $merchants->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التجار',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على تاجر محدد
     */
    public function getMerchant($id)
    {
        try {
            $merchant = MerchantProfile::with([
                'user',
                'user.addresses' => function ($q) {
                    $q->select('id', 'user_id', 'city', 'neighborhood', 'address');
                }
            ])
                ->whereHas('user', function ($q) {
                    $q->where('status', 'active');
                })
                ->findOrFail($id);

            // الحصول على المدينة والحي من العنوان الرئيسي
            $primaryAddress = $merchant->user->addresses->first();
            $cityName = $primaryAddress ? $primaryAddress->city : null;
            $neighborhoodName = $primaryAddress ? $primaryAddress->neighborhood : null;
            $fullAddress = $primaryAddress ? $primaryAddress->address : null;

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات التاجر بنجاح',
                'data' => [
                    'id' => $merchant->id,
                    'user_id' => $merchant->user_id,
                    'name' => $merchant->user->name ?? 'Unknown',
                    'username' => $merchant->user->username ?? null,
                    'email' => $merchant->user->email ?? null,
                    'phone' => $merchant->user->phone ?? null,
                    'avatar' => $merchant->user->avatar ? asset('storage/' . $merchant->user->avatar) : null,
                    'bio' => $merchant->user->bio ?? null,
                    'address' => $fullAddress ?? $merchant->user->address ?? null,
                    'country' => $merchant->user->country ?? null,
                    'city' => $cityName,
                    'neighborhood' => $neighborhoodName,
                    'default_commission_rate' => $merchant->default_commission_rate ?? 0,
                    'payout_bank_name' => $merchant->payout_bank_name ?? null,
                    'payout_account_name' => $merchant->payout_account_name ?? null,
                    'payout_account_iban' => $merchant->payout_account_iban ?? null,
                    'created_at' => $merchant->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $merchant->updated_at->format('Y-m-d H:i:s'),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'التاجر غير موجود أو غير مفعل',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function Notifications($type = 'all')
    {
        // الحصول على المستخدم المصادق عليه
        $user = auth()->guard('api')->user();

        // بدء بناء الاستعلام للإشعارات
        // يتم تحميل العلاقة المورفيكية 'related' بشكل مسبق
        $query = Notification::with('related')->where(function ($q) use ($user) {
            // جلب الإشعارات الخاصة بالمستخدم أو الإشعارات العامة (حيث user_id يكون NULL)
            $q->where('user_id', $user->id)
                ->orWhereNull('user_id');
        });

        // إذا لم يكن النوع "all"، نفلتر فقط الإشعارات غير المقروءة
        if ($type !== 'all') {
            $query->where('is_read', false);
        }

        // ترتيب الإشعارات حسب تاريخ الإنشاء تنازليًا وتقسيمها على صفحات
        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

        // إرجاع الإشعارات كاستجابة JSON
        return response()->json($notifications);
    }

    public function unreadCountNotifications()
    {
        $user = auth()->guard('api')->user();

        $count = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhereNull('user_id');
        })
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    // تحديث حالة الإشعار كمقروء
    public function markNotificationAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'تم تحديث حالة الإشعار بنجاح']);
    }
    // حذف إشعار
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'تم حذف الإشعار']);
    }


    public function getSlider()
    {
        $slider = SlideShow::all();
        if (!$slider) {
            return response()->json(['message' => 'No slider found'], 404);
        }
        return response()->json($slider);
    }
}
