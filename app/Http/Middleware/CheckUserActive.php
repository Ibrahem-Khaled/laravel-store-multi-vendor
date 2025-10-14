<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من وجود المستخدم المسجل دخوله
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
                'code' => 'UNAUTHENTICATED'
            ], 401);
        }

        // التحقق من حالة المستخدم (نشط/غير نشط/محظور)
        if ($user->status !== 'active') {
            $message = match($user->status) {
                'inactive' => 'Your account is deactivated. Please contact support.',
                'banned' => 'Your account has been banned. Please contact support.',
                default => 'Your account is not active. Please contact support.'
            };

            $code = match($user->status) {
                'inactive' => 'ACCOUNT_DEACTIVATED',
                'banned' => 'ACCOUNT_BANNED',
                default => 'ACCOUNT_INACTIVE'
            };

            return response()->json([
                'status' => false,
                'message' => $message,
                'code' => $code
            ], 403);
        }

        // إذا نجح التحقق، اسمح للطلب بالمرور
        return $next($request);
    }
}
