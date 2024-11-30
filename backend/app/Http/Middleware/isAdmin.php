<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class isAdmin
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
                        if (! $user || $user->role_id !== 1) {
                            return response()->json(['error' => 'Unauthorized'], 403);
                        }
            
                        return $next($request);
                    }
    }
}