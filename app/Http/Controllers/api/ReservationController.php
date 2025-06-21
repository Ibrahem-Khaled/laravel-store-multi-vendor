<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReservationController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        $reservations = $user->reservations()->paginate(10);
        return response()->json($reservations);
    }

    // ✅ إنشاء حجز جديد
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        // ✅ التحقق من صحة البيانات
        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:daily,morning,evening',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        // ✅ إنشاء الفترة الزمنية من تاريخ البدء إلى تاريخ الانتهاء
        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        // ✅ التحقق من التداخل مع أي حجوزات أخرى خلال كل يوم في الفترة
        foreach ($period as $date) {
            $overlap = Reservation::where('product_id', $request->product_id)
                ->where('reservation_date', $date->toDateString())
                ->where('status', 'active')
                ->where(function ($query) use ($request) {
                    if ($request->type === 'daily') {
                        $query->whereIn('type', ['daily', 'morning', 'evening']);
                    } else {
                        $query->whereIn('type', ['daily', $request->type]);
                    }
                })
                ->exists();

            if ($overlap) {
                return response()->json([
                    'message' => 'المنتج محجوز بالفعل في التاريخ: ' . $date->format('Y-m-d')
                ], 409);
            }
        }

        // ✅ إنشاء الحجز لكل يوم في الفترة
        $reservations = [];
        foreach ($period as $date) {
            $reservations[] = Reservation::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'type' => $request->type,
                'reservation_date' => $date->toDateString(),
            ]);
        }

        return response()->json([
            'message' => 'تم إنشاء الحجوزات بنجاح',
            'reservations' => $reservations,
        ], 201);
    }
    // ✅ تعديل حجز
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = auth()->guard('api')->user();

        if ($reservation->user_id !== $user->id) {
            return response()->json(['message' => 'غير مصرح لك بتعديل هذا الحجز'], 403);
        }

        $validated = Validator::make($request->all(), [
            'type' => 'sometimes|in:daily,morning,evening',
            'reservation_date' => 'sometimes|date|after_or_equal:today',
            'status' => 'sometimes|in:active,returned,partial_refund',
            'total_price' => 'sometimes|numeric|min:0',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        // تحقق من التداخل في حالة تغيير النوع أو التاريخ
        if ($request->filled('type') || $request->filled('reservation_date')) {
            $newType = $request->type ?? $reservation->type;
            $newDate = $request->reservation_date ?? $reservation->reservation_date;

            $overlap = Reservation::where('product_id', $reservation->product_id)
                ->where('id', '!=', $reservation->id)
                ->where('reservation_date', $newDate)
                ->where('status', 'active')
                ->where(function ($query) use ($newType) {
                    if ($newType === 'daily') {
                        $query->whereIn('type', ['daily', 'morning', 'evening']);
                    } else {
                        $query->whereIn('type', ['daily', $newType]);
                    }
                })
                ->exists();

            if ($overlap) {
                return response()->json(['message' => 'لا يمكن تعديل الحجز لتداخل مع حجز آخر.'], 409);
            }
        }

        $reservation->update($request->only([
            'type',
            'reservation_date',
            'status',
            'total_price',
        ]));

        return response()->json(['message' => 'تم التحديث بنجاح', 'reservation' => $reservation]);
    }

    // ✅ حذف حجز
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = auth()->guard('api')->user();

        if ($reservation->user_id !== $user->id) {
            return response()->json(['message' => 'غير مصرح لك بحذف هذا الحجز'], 403);
        }

        $reservation->delete();

        return response()->json(['message' => 'تم حذف الحجز']);
    }
}
