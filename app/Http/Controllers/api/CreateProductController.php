<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateProductController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        // ✅ التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',

            // --- Validation for brand ---
            'brand_id'     => 'required|integer|exists:brands,id',
            'brand_image'  => 'required_if:brand_id,is_string|image|mimes:jpg,png,jpeg|max:2048',

            // --- Validation for category ---
            'sub_category_id' => 'required',
            'category_id'     => 'required_if:sub_category_id,is_string|integer|exists:categories,id',

            // --- Validation for images ---
            'images'   => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048',

            'video_url' => 'nullable|url',

            // --- Validation for features ---
            'features'   => 'nullable|array',
            'features.*' => 'string|distinct'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل التحقق من البيانات',
                'errors' => $validator->errors(),
                'solution' => 'راجع البيانات المرسلة وتأكد من صحة القيم (انظر errors).'
            ], 422);
        }

        try {
            // ✅ التحقق من ملكية البراند
            if (!$user->brands()->where('id', $request->brand_id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'هذا البراند لا يتبعك',
                    'solution' => 'تأكد أنك تستخدم brand_id خاص بك فقط.'
                ], 403);
            }

            $product = DB::transaction(function () use ($request, $validator, $user) {
                // Step 1: Resolve or create the brand
                $brandId = $this->resolveBrand($request, $user);

                // Step 2: Resolve or create the sub-category
                $subCategoryId = $this->resolveSubCategory($request);

                // Step 3: Create the product
                $product = Product::create([
                    'name'             => $validator->validated()['name'],
                    'description'      => $validator->validated()['description'] ?? null,
                    'price'            => $validator->validated()['price'],
                    'discount_percent' => $validator->validated()['discount_percent'] ?? 0,
                    'brand_id'         => $brandId,
                    'sub_category_id'  => $subCategoryId,
                ]);

                // Step 4: Upload and store product images
                foreach ($validator->validated()['images'] as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create(['path' => $path]);
                }

                // Step 5: Features (resolve or create)
                if ($request->filled('features')) {
                    foreach ($request->features as $feature) {
                        if (is_numeric($feature)) {
                            $exists = Feature::find($feature);
                            if ($exists) {
                                $product->features()->attach($exists->id);
                            }
                        } else {
                            $newFeature = Feature::firstOrCreate(['name' => $feature]);
                            $product->features()->attach($newFeature->id);
                        }
                    }
                }

                return $product;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء المنتج بنجاح',
                'data' => new ProductResource(
                    $product->load('brand', 'subCategory.category', 'images', 'features')
                )
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ غير متوقع أثناء إنشاء المنتج',
                'error_details' => $e->getMessage(),
                'solution' => 'تأكد من صحة البيانات أو راجع المسؤول.'
            ], 500);
        }
    }

    /**
     * ✅ دالة التحقق أو إنشاء البراند
     */
    private function resolveBrand(Request $request, $user)
    {
        if (is_numeric($request->brand_id)) {
            return $request->brand_id;
        }

        if (!$request->hasFile('brand_image')) {
            throw new \Exception('يلزم رفع صورة للبراند الجديد.');
        }

        $path = $request->file('brand_image')->store('brands', 'public');

        $brand = Brand::create([
            'name'   => $request->brand_id,
            'image'  => $path,
            'user_id'=> $user->id,
        ]);

        return $brand->id;
    }

    /**
     * ✅ دالة التحقق أو إنشاء الـ SubCategory
     */
    private function resolveSubCategory(Request $request)
    {
        if (is_numeric($request->sub_category_id)) {
            return $request->sub_category_id;
        }

        if (!$request->filled('category_id')) {
            throw new \Exception('يلزم تحديد category_id عند إنشاء sub_category جديد.');
        }

        $subCategory = SubCategory::create([
            'name'        => $request->sub_category_id,
            'category_id' => $request->category_id,
        ]);

        return $subCategory->id;
    }
}
