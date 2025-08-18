<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Notification;
use App\Models\Product;
use App\Models\SlideShow;
use App\Models\SubCategory;
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

    public function allSubCategories()
    {
        $subCategories = SubCategory::with('category')->get();
        if ($subCategories->isEmpty()) {
            return response()->json(['message' => 'No subcategories found'], 404);
        }
        return response()->json($subCategories);
    }

    public function SubCategories(Category $category)
    {
        $subCategories = $category->subCategories;
        return response()->json($subCategories);
    }


    public function searchProducts(Request $request)
    {
        // الآن، الحقل الوحيد المطلوب هو 'query'
        $validated = Validator::make($request->all(), [
            'query' => 'required|string|max:255|min:2',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        $searchQuery = $validated['query'];

        // ابدأ ببناء الاستعلام الأساسي
        $query = Product::query()
            ->where('is_active', true)
            ->where('is_approved', true);

        // --- البحث الذكي في الاسم والوصف والسعر ---
        $query->where(function (Builder $q) use ($searchQuery) {
            // 1. ابحث في الاسم والوصف
            $q->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('description', 'LIKE', "%{$searchQuery}%");

            // 2. إذا كان النص المدخل رقماً، ابحث في السعر أيضاً
            if (is_numeric($searchQuery)) {
                $q->orWhere('price', '=', (float)$searchQuery);
            }
        });

        // --- جلب النتائج مع العلاقات الأساسية وتقسيمها ---
        // الترتيب الافتراضي حسب الأحدث
        $products = $query->with(['brand', 'images'])->latest()->paginate(15);

        return response()->json($products);
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
