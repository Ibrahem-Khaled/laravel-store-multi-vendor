<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات المدن
        $citiesCount = City::count();

        // البحث
        $search = $request->input('search');

        $cities = City::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })
            ->orderBy('name')
            ->paginate(10);

        return view('dashboard.cities.index', compact(
            'cities',
            'citiesCount',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cities',
        ]);

        City::create($request->all());

        return redirect()->route('cities.index')->with('success', 'تم إضافة المدينة بنجاح');
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
        ]);

        $city->update($request->all());

        return redirect()->route('cities.index')->with('success', 'تم تحديث المدينة بنجاح');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('cities.index')->with('success', 'تم حذف المدينة بنجاح');
    }
}
