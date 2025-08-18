<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource; // لتنسيق الاستجابة
use App\Models\Brand;
use App\Models\Feature;
use App\Models\SubCategory; // افترض أن لديك موديل للفئات الفرعية
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateProductController extends Controller
{
    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ProductResource
     */
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',

            // --- Validation for brand ---
            'brand_id' => 'required_if:brand_id,is_string|integer|exists:users,id',
            'brand_image' => 'required_if:brand_id,is_string|image|mimes:jpg,png,jpeg|max:2048',
            'sub_category_id' => 'required',
            'category_id' => 'required_if:sub_category_id,is_string|integer|exists:categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'video_url' => 'nullable|url',
            // --- Validation for features ---
            'features' => 'nullable|array',
            'features.*' => 'distinct',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $product = DB::transaction(function () use ($request, $validated, $user) {

            // Step 1: Resolve or create the brand
            $brandId = $this->resolveBrand($request, $user);

            // Step 2: Resolve or create the sub-category
            $subCategoryId = $this->resolveSubCategory($request);

            // Step 3: Create the product
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'brand_id' => $brandId,
                'sub_category_id' => $subCategoryId,
            ]);

            // Step 4: Upload and store product images
            foreach ($validated['images'] as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }

            // Step 5 (New): Resolve and attach features
            $this->resolveAndAttachFeatures($request, $product);

            return $product;
        });

        // Load all relationships for the final response
        return new ProductResource($product->load('brand', 'subCategory.category', 'images', 'features'));
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        // Step 1: Authorize the action (user must own the product's brand)
        $user = auth()->guard('api')->user();
        if ($product->brand->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. You do not own this product.'], 403);
        }

        // Step 2: Delete the product
        // Thanks to `onDelete('cascade')` in migrations, related images and feature_products will be deleted automatically.
        $product->delete();

        // Step 3: Return a success response
        return response()->json(['message' => 'Product deleted successfully.']);
    }

    /**
     * Helper method to resolve or create a brand.
     */
    private function resolveBrand(Request $request, User $user): int
    {
        $brandInput = $request->input('brand_id');

        if (is_numeric($brandInput)) {
            if (Brand::where('id', $brandInput)->where('user_id', $user->id)->exists()) {
                return (int) $brandInput;
            }
            abort(403, 'This brand does not belong to you.');
        }

        if (is_string($brandInput)) {
            $brandImage = $request->file('brand_image')->store('brands', 'public');
            $brand = Brand::firstOrCreate(
                ['name' => $brandInput, 'user_id' => $user->id],
                ['image' => $brandImage]
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
            if (SubCategory::where('id', $subCategoryInput)->exists()) {
                return (int) $subCategoryInput;
            }
            abort(422, 'Sub-category with the given ID does not exist.');
        }

        if (is_string($subCategoryInput)) {
            $subCategory = SubCategory::firstOrCreate(
                ['name' => $subCategoryInput, 'category_id' => $request->input('category_id')]
            );
            return $subCategory->id;
        }

        abort(422, 'Invalid sub_category_id format.');
    }

    /**
     * Helper method to resolve (find or create) and attach features to a product.
     * Features will be linked to the product's main category.
     */
    private function resolveAndAttachFeatures(Request $request, Product $product)
    {
        $featuresInput = $request->input('features', []);
        if (empty($featuresInput)) {
            return;
        }

        // Find the main category ID from the product's sub-category
        $mainCategoryId = $product->subCategory->category_id;
        if (!$mainCategoryId) {
            // This should not happen if data is consistent, but it's a good safeguard
            return;
        }

        $featureIds = [];
        foreach ($featuresInput as $featureItem) {
            if (is_numeric($featureItem)) {
                $featureIds[] = (int) $featureItem;
            } elseif (is_string($featureItem) && !empty($featureItem)) {
                // Create the new feature and link it to the MAIN category
                $newFeature = Feature::firstOrCreate(
                    [
                        'name' => trim($featureItem),
                        'category_id' => $mainCategoryId,
                    ]
                );
                $featureIds[] = $newFeature->id;
            }
        }

        // Use sync to attach all features to the product
        if (!empty($featureIds)) {
            $product->features()->sync($featureIds);
        }
    }
}
