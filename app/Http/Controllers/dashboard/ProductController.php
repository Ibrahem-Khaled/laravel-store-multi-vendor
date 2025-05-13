<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Images;
use App\Models\Product;
use App\Models\SubCategory;
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

        // البحث والتصفية
        $search = $request->input('search');
        $subCategoryId = $request->input('sub_category_id');

        $products = Product::with('subCategory')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%")
                    ->orWhere('neighborhood', 'like', "%$search%");
            })
            ->when($subCategoryId, function ($query) use ($subCategoryId) {
                $query->where('sub_category_id', $subCategoryId);
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
            'subCategories',
            'search',
            'subCategoryId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'required|integer|min:0|max:100',
            'video_url' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $productData = $request->except('images');

        // حساب السعر بعد الخصم
        $productData['price_after_discount'] = $request->price * (1 - ($request->discount_percent / 100));

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

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'required|integer|min:0|max:100',
            'video_url' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $productData = $request->except('images');

        // حساب السعر بعد الخصم
        $productData['price_after_discount'] = $request->price * (1 - ($request->discount_percent / 100));

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
}
