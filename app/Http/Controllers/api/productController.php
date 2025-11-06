<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class productController extends Controller
{
    public function Products(Request $request)
    {
        // 1. بناء الاستعلام الأساسي
        $query = Product::query();

        // 2. البحث بالاسم والوصف
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. فلترة على فئة فرعية مفردة أو مصفوفة فئات
        if ($request->filled('sub_category_id')) {
            $subs = $request->input('sub_category_id');
            if (is_array($subs)) {
                $query->whereIn('sub_category_id', $subs);
            } else {
                $query->where('sub_category_id', $subs);
            }
        }

        // 4. فلترة حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // 5. فلترة حسب الحي
        if ($request->filled('neighborhood')) {
            $query->where('neighborhood', $request->input('neighborhood'));
        }

        // 6. فلترة ضمن نطاق السعر
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // 7. فلترة حسب الحد الأدنى لنسبة الخصم
        if ($request->filled('discount_percent')) {
            $query->where('discount_percent', '>=', $request->input('discount_percent'));
        }

        // 9. فلترة حسب توفر المنتج في فترة زمنية معينة (تاريخ ووقت)
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $start = Carbon::parse($request->input('start_time'));
            $end = Carbon::parse($request->input('end_time'));

        }

        // 10. ترتيب حسب عمود معين (مثلاً: sort_by=name.asc)
        if ($request->filled('sort_by')) {
            // تأكد من أن الإدخال يحتوي على نقطة قبل تقسيمه لتجنب الأخطاء
            if (strpos($request->input('sort_by'), '.') !== false) {
                list($column, $direction) = explode('.', $request->input('sort_by'));
                if (in_array(strtolower($direction), ['asc', 'desc'])) {
                    $query->orderBy($column, $direction);
                }
            }
        }

        // 11. تنفيذ الاستعلام وجلب النتائج
        $products = $query->paginate(10);

        // 12. إعادة النتائج كـ JSON
        return response()->json($products);
    }

    public function similarsProducts(Product $product)
    {
        $subCategory = $product->subCategory;
        $products = Product::where('sub_category_id', $subCategory->id)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get();
        return response()->json($products);
    }

    public function Product(Product $product)
    {
        $product->load([
            'features',
            'images',
            'subCategory',
            'brand',
            'city',
            'neighborhood',
            'reviews',
            // الحجوزات المستقبلية فقط
        ]);

        return response()->json($product);
    }

    public function brands()
    {
        try {
            $user = auth()->guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'المستخدم غير مصادق عليه',
                    'code' => 'UNAUTHENTICATED'
                ], 401);
            }

            $brands = Brand::where('user_id', $user->id)->get();
            
            return response()->json([
                'status' => true,
                'message' => 'تم جلب البراندات بنجاح',
                'data' => $brands
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب البراندات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function featuredProducts()
    {
        $products = Product::where('is_featured', true)->get();
        return response()->json($products);
    }

    public function addReview(Request $request, Product $product)
    {
        $user = auth()->guard('api')->user();
        $validation = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
        ]);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $product->reviews()->create([
            'user_id' => $user->id,
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);
        return response()->json(['message' => 'Review added successfully']);
    }

    public function deleteReview(Product $product)
    {
        $user = auth()->guard('api')->user();
        $review = $product->reviews()->where('user_id', $user->id)->first();
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to delete this review'], 403);
        }
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }

    public function userFavorites()
    {
        $user = auth()->guard('api')->user();
        $products = $user->productsFavorites()->paginate(10);
        return response()->json($products);
    }

    public function addToFavorites(Product $product)
    {
        $user = auth()->guard('api')->user();
        $user->productsFavorites()->attach($product);
        //check if the product is already in favorites
        if ($user->productsFavorites()->where('product_id', $product->id)->exists()) {
            return response()->json(['message' => 'Product already added to favorites']);
        }
        return response()->json(['message' => 'Product added to favorites successfully']);
    }

    public function removeFromFavorites(Product $product)
    {
        $user = auth()->guard('api')->user();
        $user->productsFavorites()->detach($product);
        return response()->json(['message' => 'Product removed from favorites successfully']);
    }
}
