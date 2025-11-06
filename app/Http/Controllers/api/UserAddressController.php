<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    /**
     * جلب جميع عناوين المستخدم
     */
    public function index()
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $addresses = $user->addresses()->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العناوين بنجاح',
                'data' => $addresses
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العناوين',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة عنوان جديد
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:home,work,other',
                'address_line_1' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'neighborhood' => 'nullable|string|max:100',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'postal_code' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();
            $validatedData['user_id'] = $user->id;

            $address = UserAddress::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العنوان بنجاح',
                'data' => $address
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة العنوان',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث عنوان موجود
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $address = $user->addresses()->find($id);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنوان غير موجود'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'type' => 'sometimes|required|string|in:home,work,other',
                'address_line_1' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'neighborhood' => 'nullable|string|max:100',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'postal_code' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $address->update($validator->validated());
            $address->refresh();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث العنوان بنجاح',
                'data' => $address
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث العنوان',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف عنوان
     */
    public function destroy($id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $address = $user->addresses()->find($id);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنوان غير موجود'
                ], 404);
            }

            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العنوان بنجاح'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العنوان',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب عنوان محدد
     */
    public function show($id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مصادق عليه'
                ], 401);
            }

            $address = $user->addresses()->find($id);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنوان غير موجود'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العنوان بنجاح',
                'data' => $address
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العنوان',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
