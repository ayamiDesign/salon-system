<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

            {{-- エラー表示（後で使う） --}}
            @if ($errors->any())
                <div class="form-alert">
                    <ul class="form-alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="#" class="login-form">
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
        </section>
    </main>
</div>
</body>
</html>