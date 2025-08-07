<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق إذا كان المستخدم غير مسجل دخوله عبر الـ api guard
        if (!Auth::guard('api')->check()) { // استخدام check() أفضل للأداء إذا كنت لا تحتاج لبيانات المستخدم
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // إذا نجح التحقق، اسمح للطلب بالمرور
        return $next($request);
    }
}
