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
        $loginField = $request->input('login_field'); // يمكن أن يكون email أو phone
        $password = $request->input('password');

        // التحقق من وجود البيانات المطلوبة
        if (!$loginField || !$password) {
            return response()->json([
                'error' => 'يرجى إدخال معرف المستخدم وكلمة المرور'
            ], 400);
        }

        // تحديد نوع المعرف (email أو phone)
        $fieldType = $this->determineFieldType($loginField);

        if (!$fieldType) {
            return response()->json([
                'error' => 'يرجى إدخال بريد إلكتروني صالح أو رقم هاتف صالح'
            ], 400);
        }

        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make([
            'login_field' => $loginField,
            'password' => $password
        ], [
            'login_field' => $fieldType === 'email'
                ? 'required|email|exists:users,email'
                : 'required|string|exists:users,phone',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'بيانات الاعتماد غير صحيحة',
                'details' => $validator->errors()
            ], 401);
        }

        // إعداد بيانات الاعتماد للمصادقة
        $credentials = [
            $fieldType => $loginField,
            'password' => $password
        ];

        // محاولة تسجيل الدخول والتحقق من صحة كلمة المرور
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'بيانات الاعتماد غير صحيحة'
            ], 401);
        }

        // التحقق من حالة المستخدم
        $user = Auth::user();
        if ($user->status !== 'active') {
            // إبطال التوكن إذا كان المستخدم غير نشط
            try {
                JWTAuth::invalidate($token);
            } catch (\Exception $e) {
                // تجاهل خطأ إبطال التوكن إذا لم يكن صالحاً
            }
            return response()->json([
                'error' => 'حسابك غير نشط. يرجى التواصل مع الإدارة'
            ], 403);
        }

        // إرجاع التوكن وبيانات المستخدم
        return response()->json([
            'status' => 'success',
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * تحديد نوع المعرف المدخل (email أو phone)
     */
    private function determineFieldType($field)
    {
        // التحقق من أن الحقل يحتوي على بريد إلكتروني صالح
        if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // التحقق من أن الحقل يحتوي على رقم هاتف صالح
        // يمكن تخصيص هذا التحقق حسب متطلباتك
        if (preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $field)) {
            return 'phone';
        }

        return null;
    }

    public function register(Request $request)
    {
        // قواعد التحقق مع رسائل خطأ واضحة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users',
            'gender' => 'nullable|string|in:male,female',
            'role' => 'nullable|string|in:user,trader',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نص',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'رقم الهاتف يجب أن يكون نص',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'gender.in' => 'الجنس يجب أن يكون ذكر أو أنثى',
            'role.in' => 'الدور يجب أن يكون مستخدم أو تاجر',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'كلمة المرور يجب أن تكون نص',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في إنشاء الحساب',
                'errors' => $validator->errors(),
                'error_summary' => $this->getErrorSummary($validator->errors())
            ], 422);
        }

        try {
            $username = Str::slug($request->name) . '-' . Str::random(5);
            $user = User::where('username', $username)->first();
            while ($user) {
                $username = Str::slug($request->name) . '-' . Str::random(5);
                $user = User::where('username', $username)->first();
            }

            // إنشاء المستخدم الجديد
            $user = User::create([
                'uuid' => Str::uuid(),
                'username' => $username,
                'name' => $request->name,
                'email' => $request->email, // يمكن أن يكون null
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'status' => 'active', // تفعيل الحساب تلقائياً
                'role' => $request->role ?? 'user', // قيمة افتراضية
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الحساب بنجاح',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إنشاء الحساب',
                'error_details' => 'يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني',
                'error_code' => 'REGISTRATION_FAILED'
            ], 500);
        }
    }

    /**
     * إنشاء ملخص للأخطاء بطريقة واضحة
     */
    private function getErrorSummary($errors)
    {
        $summary = [];

        foreach ($errors->toArray() as $field => $messages) {
            switch ($field) {
                case 'name':
                    $summary[] = 'مشكلة في الاسم: ' . implode(', ', $messages);
                    break;
                case 'email':
                    $summary[] = 'مشكلة في البريد الإلكتروني: ' . implode(', ', $messages);
                    break;
                case 'phone':
                    $summary[] = 'مشكلة في رقم الهاتف: ' . implode(', ', $messages);
                    break;
                case 'password':
                    $summary[] = 'مشكلة في كلمة المرور: ' . implode(', ', $messages);
                    break;
                case 'gender':
                    $summary[] = 'مشكلة في الجنس: ' . implode(', ', $messages);
                    break;
                case 'role':
                    $summary[] = 'مشكلة في الدور: ' . implode(', ', $messages);
                    break;
                default:
                    $summary[] = implode(', ', $messages);
            }
        }

        return $summary;
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
