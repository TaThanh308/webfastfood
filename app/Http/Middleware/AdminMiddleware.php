<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Tiếp tục nếu người dùng là admin
        }

        return redirect('/login')->withErrors('Bạn không có quyền truy cập.');
    }
}
