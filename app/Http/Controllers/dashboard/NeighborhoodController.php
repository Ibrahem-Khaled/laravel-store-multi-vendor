<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Http\Request;

class NeighborhoodController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cityId = $request->input('city_id');

        $neighborhoods = Neighborhood::with('city')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($cityId, function ($query) use ($cityId) {
                $query->where('city_id', $cityId);
            })
            ->latest()
            ->paginate(10);

        $cities = City::all();
        $neighborhoodsCount = Neighborhood::count();
        $activeNeighborhoodsCount = Neighborhood::where('active', true)->count();
        $citiesWithNeighborhoodsCount = City::has('neighborhoods')->count();

        return view('dashboard.neighborhoods.index', compact(
            'neighborhoods',
            'cities',
            'neighborhoodsCount',
            'activeNeighborhoodsCount',
            'citiesWithNeighborhoodsCount',
            'search',
            'cityId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'active' => 'boolean',
        ]);

        Neighborhood::create($validated);

        return redirect()->route('neighborhoods.index')
            ->with('success', 'تمت إضافة الحي بنجاح');
    }

    public function update(Request $request, Neighborhood $neighborhood)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'active' => 'boolean',
        ]);

        $neighborhood->update($validated);

        return redirect()->route('neighborhoods.index')
            ->with('success', 'تم تحديث بيانات الحي بنجاح');
    }

    public function destroy(Neighborhood $neighborhood)
    {
        $neighborhood->delete();

        return redirect()->route('neighborhoods.index')
            ->with('success', 'تم حذف الحي بنجاح');
    }
}
