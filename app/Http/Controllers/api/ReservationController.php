<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:daily,morning,evening',
            'reservation_date' => 'required|date|after_or_equal:today',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        // التحقق من التداخل مع حجوزات أخرى
        $overlap = Reservation::where('product_id', $request->product_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('status', 'active')
            ->where(function ($query) use ($request) {
                if ($request->type === 'daily') {
                    // لا يمكن وجود أي حجز في نفس اليوم
                    $query->whereIn('type', ['daily', 'morning', 'evening']);
                } else {
                    // لا يمكن وجود حجز يوم كامل أو نفس الفترة
                    $query->whereIn('type', ['daily', $request->type]);
                }
            })
            ->exists();

        if ($overlap) {
            return response()->json(['message' => 'المنتج محجوز بالفعل في هذا التاريخ أو الفترة.'], 409);
        }

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'type' => $request->type,
            'reservation_date' => $request->reservation_date,
            'total_price' => $request->total_price,
        ]);

        return response()->json(['message' => 'تم إنشاء الحجز بنجاح', 'reservation' => $reservation], 201);
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
