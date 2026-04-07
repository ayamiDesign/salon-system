<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 未ログイン
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 権限チェック
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}