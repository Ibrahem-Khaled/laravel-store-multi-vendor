<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $selectedType = $request->get('type', 'all');
        $search = $request->get('search');
        $users = User::all();
        $products = Product::all();
        $query = Reservation::with(['user', 'product'])
            ->when($selectedType !== 'all', function ($q) use ($selectedType) {
                return $q->where('type', $selectedType);
            })
            ->when($search, function ($q) use ($search) {
                return $q->where(function ($query) use ($search) {
                    $query->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%");
                    })
                        ->orWhereHas('product', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        });
                });
            });

        $reservations = $query->latest()->paginate(10);

        // إحصائيات الحجوزات
        $totalReservations = Reservation::count();
        $activeReservations = Reservation::where('status', 'active')->count();
        $dailyReservations = Reservation::where('type', 'daily')->count();
        $hourlyReservations = Reservation::where('type', 'hourly')->count();

        return view('dashboard.reservations.index', compact(
            'reservations',
            'selectedType',
            'totalReservations',
            'activeReservations',
            'dailyReservations',
            'hourlyReservations',
            'users',
            'products'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:daily,hourly',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'total_price' => 'required|numeric|min:0',
        ]);

        $validated['status'] = 'active';

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'تم إنشاء الحجز بنجاح');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:daily,hourly',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:active,returned,partial_refund',
            'total_price' => 'required|numeric|min:0',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'تم تحديث الحجز بنجاح');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'تم حذف الحجز بنجاح');
    }
}
