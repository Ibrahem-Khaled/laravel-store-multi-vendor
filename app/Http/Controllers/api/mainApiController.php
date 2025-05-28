<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class mainApiController extends Controller
{
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

    public function Products(Request $request)
    {
        // 1. بناء الاستعلام الأساسي
        $query = Product::query();

        // 2. فلترة على فئة فرعية مفردة أو مصفوفة فئات
        if ($request->filled('sub_category_id')) {
            $subs = $request->input('sub_category_id');
            if (is_array($subs)) {
                // عدة فئات
                $query->whereIn('sub_category_id', $subs);
            } else {
                // فئة واحدة
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

        // 7. ترتيب اختياري (مثلاً sort_by=name.asc أو price.desc)
        if ($request->filled('sort_by')) {
            [$column, $direction] = explode('.', $request->input('sort_by'));
            $query->orderBy($column, $direction);
        }

        // 8. تنفيذ الاستعلام وجلب النتائج
        $products = $query->get();

        // 9. إعادة النتائج كـ JSON
        return response()->json($products);
    }

    public function Product(Product $product)
    {
        $product->load('features', 'images', 'subCategory', 'brand', 'city', 'neighborhood');
        return response()->json($product);
    }
}
