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
        // 後程、403エラー画面を作成する
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('faqs.index');
        }

        return $next($request);
    }
}