<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 未ログイン
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 権限チェック
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('faqs.index')->with('error', '管理者権限が必要です。');
        }

        return $next($request);
    }
}