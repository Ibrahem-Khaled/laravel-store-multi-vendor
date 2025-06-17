<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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

        // 2. فلترة على فئة فرعية مفردة أو مصفوفة فئات
        if ($request->filled('sub_category_id')) {
            $subs = $request->input('sub_category_id');
            if (is_array($subs)) {
                $query->whereIn('sub_category_id', $subs);
            } else {
                $query->where('sub_category_id', $subs);
            }
        }

        // 3. فلترة حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // 4. فلترة حسب الحي
        if ($request->filled('neighborhood')) {
            $query->where('neighborhood', $request->input('neighborhood'));
        }

        // 5. فلترة ضمن نطاق السعر
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // 6. فلترة حسب الحد الأدنى لنسبة الخصم
        if ($request->filled('discount_percent')) {
            $query->where('discount_percent', '>=', $request->input('discount_percent'));
        }

        // 7. فلترة حسب توفر المنتج في يوم معين
        if ($request->filled('day')) {
            $day = Carbon::parse($request->input('day'))->format('Y-m-d');

            $query->whereDoesntHave('reservations', function ($q) use ($day) {
                $q->whereDate('start_time', '<=', $day)
                    ->whereDate('end_time', '>=', $day)
                    ->where('status', 'active');
            });
        }

        // 8. فلترة حسب توفر المنتج في فترة زمنية معينة (تاريخ ووقت)
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $start = Carbon::parse($request->input('start_time'));
            $end = Carbon::parse($request->input('end_time'));

            $query->whereDoesntHave('reservations', function ($q) use ($start, $end) {
                $q->where(function ($q2) use ($start, $end) {
                    $q2->whereBetween('start_time', [$start, $end])
                        ->orWhereBetween('end_time', [$start, $end])
                        ->orWhere(function ($q3) use ($start, $end) {
                            $q3->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                        });
                })->where('status', 'active');
            });
        }

        // 9. ترتيب حسب عمود معين (مثلاً: sort_by=name.asc)
        if ($request->filled('sort_by')) {
            [$column, $direction] = explode('.', $request->input('sort_by'));
            $query->orderBy($column, $direction);
        }

        // 10. تنفيذ الاستعلام وجلب النتائج
        $products = $query->paginate(10);

        // 11. إعادة النتائج كـ JSON
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
            'reservations' => function ($query) {
                $query->where('status', 'active')
                    ->where('start_time', '>=', now());
            }
        ]);

        return response()->json($product);
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
}
