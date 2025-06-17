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
            'type' => 'required|in:daily,hourly',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $overlap = Reservation::where('product_id', $request->product_id)
            ->where('status', 'active')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json(['message' => 'المنتج محجوز بالفعل في هذه الفترة.'], 409);
        }

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'type' => $request->type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $request->total_price,
        ]);

        return response()->json(['message' => 'تم إنشاء الحجز بنجاح', 'reservation' => $reservation], 201);
    }

    // ✅ تعديل حجز
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = auth()->guard('api')->user();
        $validated = Validator::make($request->all(), [
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'type' => 'sometimes|in:daily,hourly',
            'status' => 'sometimes|in:active,returned,partial_refund',
            'total_price' => 'sometimes|numeric|min:0',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        if ($reservation->user_id !== $user->id) {
            return response()->json(['message' => 'غير مصرح لك بتعديل هذا الحجز'], 403);
        }

        // تحقق من التداخل فقط لو تم تعديل التواريخ
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $overlap = Reservation::where('product_id', $reservation->product_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'active')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->exists();

            if ($overlap) {
                return response()->json(['message' => 'لا يمكن تعديل الحجز لتداخل مع حجز آخر.'], 409);
            }
        }

        $reservation->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'total_price' => $request->total_price,
        ]);

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
