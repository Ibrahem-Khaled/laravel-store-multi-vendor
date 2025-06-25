<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Notification;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

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

    public function Notifications($type = 'all')
    {
        $user = auth()->guard('api')->user();

        $query = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhereNull('user_id');
        });

        // إذا لم يكن النوع "all"، نفلتر فقط الإشعارات غير المقروءة
        if ($type !== 'all') {
            $query->where('is_read', false);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

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
}
