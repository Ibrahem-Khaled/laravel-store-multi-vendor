<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class authController extends Controller
{
    public function login(Request $request)
    {
        // استخراج بيانات الاعتماد من الطلب
        $credentials = $request->only('email', 'password');

        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($credentials, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'بيانات الاعتماد غير صحيحة'], 401);
        }

        // محاولة تسجيل الدخول والتحقق من صحة كلمة المرور
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'بيانات الاعتماد غير صحيحة'], 401);
        }

        // إرجاع التوكن وبيانات المستخدم
        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users',
            'gender' => 'nullable|string|in:male,female',
            'role' => 'nullable|string|in:user,trader',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $username = Str::slug($request->name) . '-' . Str::random(5);
        $user = User::where('username', $username)->first();
        while ($user) {
            $username = Str::slug($request->name) . '-' . Str::random(5);
            $user = User::where('username', $username)->first();
        }
        // إنشاء المستخدم الجديد
        $user = User::create([
            'username' => $username,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function user(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['user' => $user]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'غير مصرح به'], 401);
        }
    }

    public function update(Request $request)
    {
        // الحصول على المستخدم الحالي من التوكن
        $user = JWTAuth::parseToken()->authenticate();

        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'gender' => 'required|string|in:male,female',
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // التحقق من وجود ملف صورة في الطلب
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            // توليد اسم فريد للصورة
            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

            // تخزين الصورة في مجلد "avatars" داخل القرص "public"
            $path = $avatar->storeAs('avatars', $filename, 'public');

            // توليد رابط الصورة العام باستخدام asset() أو Storage::url()
            $avatarUrl = asset('storage/' . $path);

            // تحديث رابط الصورة الخاصة بالمستخدم
            $user->avatar = $avatarUrl;
        }

        // تحديث باقي بيانات المستخدم
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
        $user->bio = $request->input('bio');
        $user->address = $request->input('address');
        $user->country = $request->input('country');
        $user->birth_date = $request->input('birth_date');
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'user' => $user,
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = JWTAuth::parseToken()->authenticate();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['error' => 'كلمة المرور الحالية غير صحيحة'], 422);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تغيير كلمة المرور بنجاح',
        ], 200);
    }

    public function getUser(User $user): JsonResponse
    {
        // تحميل العلاقات (Lazy Eager Loading)
        $user->load('products', 'brands');

        // إرجاع استجابة JSON منظمة
        return response()->json([
            'status' => true,
            'message' => 'User data retrieved successfully.',
            'data' => $user
        ]);
    }
    public function addExpoPushToken(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->expo_push_token = $request->input('expo_push_token');
        $user->save();
        return response()->json('success added token');
    }

    public function deleteAccount()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->delete();
        return response()->json('success deleted account');
    }
}
