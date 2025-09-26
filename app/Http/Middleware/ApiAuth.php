<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من وجود المستخدم المسجل دخوله عبر الـ api guard
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated. Please login first.',
                'code' => 'UNAUTHENTICATED'
            ], 401);
        }

        // التحقق من صحة الـ token
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid authentication token.',
                'code' => 'INVALID_TOKEN'
            ], 401);
        }

        // إذا نجح التحقق، اسمح للطلب بالمرور
        return $next($request);
    }
}
