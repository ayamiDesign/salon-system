<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app login-page">

    <main class="login-layout">
        <section class="login-card">

            <div class="login-header">
                <h1 class="login-title">ログイン</h1>
                <p class="login-sub">
                    管理画面にログインしてください
                </p>
            </div>

            @if ($errors->any())
                <div class="form-alert">
                    <ul class="form-alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('login.store') }}" class="login-form">
                @csrf

                <div class="account-form-stack">

                    <div class="account-form-field">
                        <label class="faq-label">
                            メールアドレス
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="text-input"
                            placeholder="sample@example.com"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="account-form-field">
                        <label class="faq-label">
                            パスワード
                        </label>
                        <input
                            type="password"
                            name="password"
                            class="text-input"
                            placeholder="パスワードを入力"
                            required
                        >
                    </div>

                </div>

                <button type="submit" class="login-button">
                    ログイン
                </button>

            </form>
            <div class="demo-account-box">
                <h3 class="demo-title">テスト用アカウント</h3>

                <div class="demo-account">
                    <p><strong>管理者</strong></p>
                    <p>Email: admin@example.com</p>
                    <p>Password: password</p>
                </div>

                <div class="demo-account">
                    <p><strong>店長</strong></p>
                    <p>Email: manager@example.com</p>
                    <p>Password: password</p>
                </div>

                <div class="demo-account">
                    <p><strong>スタッフ</strong></p>
                    <p>Email: staff@example.com</p>
                    <p>Password: password</p>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>