<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::latest()->paginate(10);
        $applicableTypes = ['residency' => 'السكن', 'hall' => 'القاعة'];

        $selectedType = request('type', 'all');
        if ($selectedType !== 'all') {
            $features = Feature::where('applicable_to', $selectedType)->latest()->paginate(10);
        }

        return view('dashboard.features.index', compact('features', 'applicableTypes', 'selectedType'));
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:features',
            'applicable_to' => 'required|in:residency,hall',
        ]);

        Feature::create($request->all());

        return redirect()->route('features.index')
            ->with('success', 'تمت إضافة الميزة بنجاح');
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:features,name,' . $feature->id,
            'applicable_to' => 'required|in:residency,hall',
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
