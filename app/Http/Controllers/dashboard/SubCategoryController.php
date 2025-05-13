<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات الأقسام الفرعية
        $subCategoriesCount = SubCategory::count();
        $subCategoriesWithImages = SubCategory::whereNotNull('image')->count();
        $categoriesCount = Category::count();
        $categories = Category::all();
        // البحث
        $search = $request->input('search');
        $subCategories = SubCategory::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('dashboard.sub-categories.index', compact(
            'subCategories',
            'subCategoriesCount',
            'subCategoriesWithImages',
            'categoriesCount',
            'categories'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.sub-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:sub_categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subCategoryData = $request->except('image');

        if ($request->hasFile('image')) {
            $subCategoryData['image'] = $request->file('image')->store('sub-categories', 'public');
        }

        SubCategory::create($subCategoryData);

        return redirect()->route('sub-categories.index')->with('success', 'تم إنشاء القسم الفرعي بنجاح');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('dashboard.sub-categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subCategoryData = $request->except('image');

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($subCategory->image) {
                Storage::disk('public')->delete($subCategory->image);
            }
            $subCategoryData['image'] = $request->file('image')->store('sub-categories', 'public');
        }

        $subCategory->update($subCategoryData);

        return redirect()->route('sub-categories.index')->with('success', 'تم تحديث القسم الفرعي بنجاح');
    }

    public function destroy(SubCategory $subCategory)
    {
        // حذف الصورة إذا كانت موجودة
        if ($subCategory->image) {
            Storage::disk('public')->delete($subCategory->image);
        }

        $subCategory->delete();

        return redirect()->route('sub-categories.index')->with('success', 'تم حذف القسم الفرعي بنجاح');
    }
}
