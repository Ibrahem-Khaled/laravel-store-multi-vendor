<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        return response()->json($user->addresses);
    }

    // إضافة عنوان جديد
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:home,work,other',
            'address_line_1' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // هنا بترجع البيانات بعد الفاليديشن
        $validatedData = $validator->validated();

        $address = $user()->addresses()->create($validatedData);

        return response()->json($address, 201);
    }

    public function destroy($id)
    {
        $user = auth()->guard('api')->user();
        $address = $user->addresses()->find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
