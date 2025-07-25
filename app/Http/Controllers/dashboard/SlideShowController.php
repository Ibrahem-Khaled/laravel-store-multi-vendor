<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\SlideShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideShowController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات السلايد شو
        $slidesCount = SlideShow::count();
        $activeSlides = SlideShow::where('is_active', true)->count();
        $slidesWithLinks = SlideShow::whereNotNull('link')->count();

        // البحث والترتيب
        $search = $request->input('search');
        $slides = SlideShow::when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })
            ->orderBy('order')
            ->paginate(10);

        return view('dashboard.slide-shows.index', compact(
            'slides',
            'slidesCount',
            'activeSlides',
            'slidesWithLinks'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'link' => 'nullable|url',
            'order' => 'integer'
        ]);

        $slideData = $request->except('image');

        // --- ✨ السطر المُعدّل ---
        // إذا كان حقل is_active موجوداً (checkbox محدد)، تكون القيمة 1، وإلا تكون 0.
        $slideData['is_active'] = $request->has('is_active') ? 1 : 0;

        // تحميل الصورة
        if ($request->hasFile('image')) {
            $slideData['image'] = $request->file('image')->store('slide-shows', 'public');
        }

        SlideShow::create($slideData);

        return redirect()->route('slide-shows.index')->with('success', 'تم إضافة الشريحة بنجاح');
    }

    public function update(Request $request, SlideShow $slideShow)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'link' => 'nullable|url',
            'order' => 'integer'
        ]);

        $slideData = $request->except('image');

        // --- ✨ السطر المُعدّل ---
        // نفس المنطق: إذا كان الحقل موجوداً، تكون القيمة 1، وإلا تكون 0.
        $slideData['is_active'] = $request->has('is_active') ? 1 : 0;

        // تحديث الصورة إذا تم تحميل جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($slideShow->image) {
                Storage::disk('public')->delete($slideShow->image);
            }
            $slideData['image'] = $request->file('image')->store('slide-shows', 'public');
        }

        $slideShow->update($slideData);

        return redirect()->route('slide-shows.index')->with('success', 'تم تحديث الشريحة بنجاح');
    }

    public function destroy(SlideShow $slideShow)
    {
        // حذف الصورة إذا كانت موجودة
        if ($slideShow->image) {
            Storage::disk('public')->delete($slideShow->image);
        }

        $slideShow->delete();

        return redirect()->route('slide-shows.index')->with('success', 'تم حذف الشريحة بنجاح');
    }

    public function updateOrder(Request $request)
    {
        $slides = $request->input('slides');

        foreach ($slides as $slide) {
            SlideShow::where('id', $slide['id'])->update(['order' => $slide['order']]);
        }

        return response()->json(['success' => true]);
    }
}
