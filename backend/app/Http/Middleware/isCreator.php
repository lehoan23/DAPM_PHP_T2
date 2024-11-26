<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        {
            $user = auth('api')->user();

            // Kiểm tra nếu chưa đăng nhập hoặc role không khớp
            if (! $user || $user->role_id !== 2) {
                return response()->json(['error' => 'Unauthorized'], 403); 
            }

            return $next($request);
        }
    }
}