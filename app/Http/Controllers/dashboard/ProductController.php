<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Feature;
use App\Models\Images;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات المنتجات
        $productsCount = Product::count();
        $productsWithDiscount = Product::where('discount_percent', '>', 0)->count();
        $productsWithVideo = Product::whereNotNull('video_url')->count();
        $subCategoriesCount = SubCategory::count();
        $pendingApprovalCount = Product::where('is_approved', false)->count();
        $brands = Brand::all();
        
        // البحث والتصفية
        $search = $request->input('search');
        $subCategoryId = $request->input('sub_category_id');
        $approvalStatus = $request->input('approval_status'); // 'pending', 'approved', null

        $products = Product::with('subCategory')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            })
            ->when($subCategoryId, function ($query) use ($subCategoryId) {
                $query->where('sub_category_id', $subCategoryId);
            })
            ->when($approvalStatus === 'pending', function ($query) {
                $query->where('is_approved', false);
            })
            ->when($approvalStatus === 'approved', function ($query) {
                $query->where('is_approved', true);
            })
            ->latest()
            ->paginate(10);

        $subCategories = SubCategory::all();

        return view('dashboard.products.index', compact(
            'products',
            'productsCount',
            'productsWithDiscount',
            'productsWithVideo',
            'subCategoriesCount',
            'pendingApprovalCount',
            'subCategories',
            'search',
            'subCategoryId',
            'approvalStatus',
            'brands',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'quantity' => 'nullable|integer|min:0',
            'video_url' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $productData = $request->except('images');
        $productData['discount_percent'] = $productData['discount_percent'] ?? 0;
        $productData['quantity'] = $productData['quantity'] ?? 0;
        $productData['is_approved'] = false; // المنتجات الجديدة تحتاج موافقة

        $product = Product::create($productData);

        // تحميل الصور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function showFeatures(Product $product)
    {
        $product->load('features');
        $availableFeatures = Feature::whereNotIn('id', $product->features->pluck('id'))->get();

        return view('dashboard.products.show_features', compact('product', 'availableFeatures'));
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'quantity' => 'nullable|integer|min:0',
            'video_url' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $productData = $request->except('images');
        $productData['discount_percent'] = $productData['discount_percent'] ?? 0;
        $productData['quantity'] = $productData['quantity'] ?? 0;

        $product->update($productData);

        // تحميل الصور الجديدة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // حذف الصور المرتبطة
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    public function destroyImage($imageId)
    {
        $image = Images::findOrFail($imageId);
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }

    /**
     * Toggle product approval status
     */
    public function toggleApproval(Product $product)
    {
        $product->update([
            'is_approved' => !$product->is_approved
        ]);

        $status = $product->is_approved ? 'مقبول' : 'غير مقبول';
        return back()->with('success', "تم تغيير حالة المنتج إلى: {$status}");
    }
}
