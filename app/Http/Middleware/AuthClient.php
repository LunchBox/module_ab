<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Token;

use Illuminate\Support\Facades\Log;

class AuthClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 從 URL 查詢參數中獲取 token
        $token = $request->query('token');

        Log::info("==================");
        Log::info($token);

        // 驗證 token 是否存在於 tokens 表中
        $tokenRecord = Token::where('token', $token)->first();
        Log::info($tokenRecord);

        if (!$tokenRecord) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 根據 tokenable_type 獲取用戶
        $user = $tokenRecord->tokenable;

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 設置認證用戶
        // Auth::login($user);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
