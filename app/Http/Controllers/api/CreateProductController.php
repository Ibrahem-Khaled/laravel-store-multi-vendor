<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource; // لتنسيق الاستجابة
use App\Models\Brand;
use App\Models\SubCategory; // افترض أن لديك موديل للفئات الفرعية
use App\Models\Product;
use App\Models\User;

class CreateProductController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required', // سيتم التحقق منه يدوياً
            'brand_image' => 'required_if:brand_id,is_string|image|mimes:jpg,png,jpeg|max:2048', // مطلوب فقط عند إنشاء براند جديد
            'sub_category_id' => 'required',
            'category_id' => 'required_if:sub_category_id,is_string|integer|exists:categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'video_url' => 'nullable|url',
        ]);

        // استخدام Transaction لضمان سلامة البيانات
        $product = DB::transaction(function () use ($request, $validated, $user) {

            // الخطوة 1: تحديد أو إنشاء البراند
            $brandId = $this->resolveBrand($request, $user);

            // الخطوة 2: تحديد أو إنشاء الفئة الفرعية
            $subCategoryId = $this->resolveSubCategory($request);

            // الخطوة 3: إنشاء المنتج
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'brand_id' => $brandId,
                'sub_category_id' => $subCategoryId,
            ]);

            // الخطوة 4: رفع وتخزين صور المنتج
            foreach ($validated['images'] as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }

            return $product;
        });

        return new ProductResource($product->load('brand', 'subCategory.category', 'images'));
    }

    /**
     * Helper method to resolve or create a brand.
     */
    private function resolveBrand(Request $request, User $user): int
    {
        $brandInput = $request->input('brand_id');

        if (is_numeric($brandInput)) {
            // تأكد من أن البراند موجود ويخص المستخدم الحالي
            if (Brand::where('id', $brandInput)->where('user_id', $user->id)->exists()) {
                return (int) $brandInput;
            }
            abort(403, 'This brand does not belong to you.');
        }

        if (is_string($brandInput)) {
            $brandImage = $request->file('brand_image')->store('brands', 'public');

            $brand = Brand::firstOrCreate(
                ['name' => $brandInput, 'user_id' => $user->id],
                ['image' => $brandImage] // سيتم ملء حقل الصورة فقط عند الإنشاء لأول مرة
            );
            return $brand->id;
        }

        abort(422, 'Invalid brand_id format.');
    }

    /**
     * Helper method to resolve or create a sub-category.
     */
    private function resolveSubCategory(Request $request): int
    {
        $subCategoryInput = $request->input('sub_category_id');

        if (is_numeric($subCategoryInput)) {
            return (int) $subCategoryInput;
        }

        if (is_string($subCategoryInput)) {
            $subCategory = SubCategory::firstOrCreate(
                ['name' => $subCategoryInput, 'category_id' => $request->input('category_id')]
            );
            return $subCategory->id;
        }

        abort(422, 'Invalid sub_category_id format.');
    }
}
