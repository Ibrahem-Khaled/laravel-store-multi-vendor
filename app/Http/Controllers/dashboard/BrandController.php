<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات العلامات التجارية
        $brandsCount = Brand::count();
        $activeBrands = Brand::where('is_active', true)->count();
        $brandsWithLocations = Brand::whereNotNull('latitude')->whereNotNull('longitude')->count();

        // البحث والتصفية
        $search = $request->input('search');
        $userId = $request->input('user_id');
        $isActive = $request->input('is_active');

        $brands = Brand::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when(isset($isActive), function ($query) use ($isActive) {
                $query->where('is_active', $isActive);
            })
            ->orderBy('order')
            ->paginate(10);

        $users = User::where('role', 'trader')->get();

        return view('dashboard.brands.index', compact(
            'brands',
            'brandsCount',
            'activeBrands',
            'brandsWithLocations',
            'users',
            'search',
            'userId',
            'isActive'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'link' => 'nullable|url',
            'order' => 'integer',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            // 'is_active' => 'nullable|boolean',
        ]);

        $brandData = $request->except('image');

        // تحميل الصورة
        if ($request->hasFile('image')) {
            $brandData['image'] = $request->file('image')->store('brands', 'public');
        }

        Brand::create($brandData);

        return redirect()->route('brands.index')->with('success', 'تم إضافة العلامة التجارية بنجاح');
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'link' => 'nullable|url',
            'order' => 'integer',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            // 'is_active' => 'nullable|boolean',
        ]);

        $brandData = $request->except('image');

        // تحديث الصورة إذا تم تحميل جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $brandData['image'] = $request->file('image')->store('brands', 'public');
        }

        $brand->update($brandData);

        return redirect()->route('brands.index')->with('success', 'تم تحديث العلامة التجارية بنجاح');
    }

    public function destroy(Brand $brand)
    {
        // حذف الصورة إذا كانت موجودة
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'تم حذف العلامة التجارية بنجاح');
    }

    public function updateOrder(Request $request)
    {
        $brands = $request->input('brands');

        foreach ($brands as $brand) {
            Brand::where('id', $brand['id'])->update(['order' => $brand['order']]);
        }

        return response()->json(['success' => true]);
    }
}
