<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && (int) $user->is_active !== 1) {
            return back()->withErrors([
                'email' => 'このアカウントは現在利用できません。',
            ])->onlyInput('email');
        }

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();

            return redirect()->route('faqs.index');
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}