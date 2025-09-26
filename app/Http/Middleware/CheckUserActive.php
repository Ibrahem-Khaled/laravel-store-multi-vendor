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

        // التحقق من حالة المستخدم (نشط/غير نشط)
        if (!$user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is deactivated. Please contact support.',
                'code' => 'ACCOUNT_DEACTIVATED'
            ], 403);
        }

        // التحقق من حالة المستخدم (محظور/غير محظور)
        if ($user->is_banned) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been banned. Please contact support.',
                'code' => 'ACCOUNT_BANNED'
            ], 403);
        }

        // التحقق من حالة المستخدم (محذوف/غير محذوف)
        if ($user->deleted_at) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been deleted.',
                'code' => 'ACCOUNT_DELETED'
            ], 403);
        }

        // إذا نجح التحقق، اسمح للطلب بالمرور
        return $next($request);
    }
}
