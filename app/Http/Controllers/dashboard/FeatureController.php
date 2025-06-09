<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $features = Feature::with('category')
            ->when(request('category_id'), function ($query) {
                $query->where('category_id', request('category_id'));
            })
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(10);

        $totalFeatures = Feature::count();
        $featuresWithoutCategory = Feature::whereNull('category_id')->count();
        $categoriesCount = $categories->count();
        $featuresThisMonth = Feature::whereMonth('created_at', now()->month)->count();
        return view('dashboard.features.index', compact(
            'features',
            'categories',
            'totalFeatures',
            'featuresWithoutCategory',
            'categoriesCount',
            'featuresThisMonth'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Feature::create($request->all());

        return redirect()->route('features.index')
            ->with('success', 'تم إضافة الميزة بنجاح');
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $feature->update($request->all());

        return redirect()->route('features.index')
            ->with('success', 'تم تحديث الميزة بنجاح');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('features.index')
            ->with('success', 'تم حذف الميزة بنجاح');
    }

    public function statistics()
    {
        $totalFeatures = Feature::count();
        $residencyFeatures = Feature::where('applicable_to', 'residency')->count();
        $hallFeatures = Feature::where('applicable_to', 'hall')->count();

        return response()->json([
            'totalFeatures' => $totalFeatures,
            'residencyFeatures' => $residencyFeatures,
            'hallFeatures' => $hallFeatures,
        ]);
    }

    public function addFeatureToProduct(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'feature_ids' => 'required|array',
            'feature_ids.*' => 'exists:features,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $product->features()->attach($validated['feature_ids']);

        return redirect()->back()
            ->with('success', 'تمت إضافة الميزات بنجاح');
    }

    public function removeFeatureFromProduct(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'feature_id' => 'required|exists:features,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $product->features()->detach($validated['feature_id']);

        return redirect()->back()
            ->with('success', 'تم حذف الميزة بنجاح');
    }
}
