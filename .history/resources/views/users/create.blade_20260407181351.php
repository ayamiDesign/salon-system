@php
    $oldFaqs = old('user', $sessionInput['user_input'] ?? [['']]);
@endphp
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">アカウントを登録</h1>
                    <p class="search-sub">
                        管理画面で利用するアカウントを登録します。
                    </p>
                </div>
            </div>

            @if ($errors->any())
                <div class="form-alert">
                    <p class="form-alert-title">入力内容を確認してください</p>
                    <ul class="form-alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="faq-card form-card account-form-card">
                <div class="form-card-header">
                    <div>
                        <h2 class="form-card-title">登録フォーム</h2>
                        <p class="form-card-sub">必要事項を入力し、確認画面へ進んでください。</p>
                    </div>
                </div>

                <form action="{{ route('users.confirm') }}" method="post" class="form-body">
                    @csrf

                    <div class="account-form-stack">
                        <div class="account-form-field">
                            <label for="name" class="faq-label">
                                名前 <span class="required-badge">必須</span>
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $sessionInput['name'] ?? '') }}"
                                placeholder="例：山田 花子"
                                class="text-input"
                                required
                            >
                        </div>

                        <div class="account-form-field">
                            <label for="email" class="faq-label">
                                メールアドレス <span class="required-badge">必須</span>
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $sessionInput['email'] ?? '') }}"
                                placeholder="例：sample@example.com"
                                class="text-input"
                                required
                            >
                        </div>

                        <div class="account-form-field">
                            <label for="password" class="faq-label">
                                パスワード <span class="required-badge">必須</span>
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="text-input"
                                placeholder="8文字以上で入力"
                                required
                            >
                        </div>

                        <div class="account-form-field">
                            <label for="password_confirmation" class="faq-label">
                                パスワード確認 <span class="required-badge">必須</span>
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="text-input"
                                placeholder="確認用パスワードを入力"
                                required
                            >
                        </div>

                        <div class="account-form-grid">
                            <div class="account-form-field">
                                <label for="role" class="faq-label">
                                    権限 <span class="required-badge">必須</span>
                                </label>
                                <select id="role" name="role" class="text-select" required>
                                    <option value="">選択してください</option>
                                    <option value="admin" {{ old('role', $sessionInput['role'] ?? '') === 'admin' ? 'selected' : '' }}>管理者</option>
                                    <option value="manager" {{ old('role', $sessionInput['role'] ?? '') === 'manager' ? 'selected' : '' }}>店長</option>
                                    <option value="staff" {{ old('role', $sessionInput['role'] ?? '') === 'staff' ? 'selected' : '' }}>スタッフ</option>
                                </select>
                            </div>

                            <div class="account-form-field">
                                <label for="is_active" class="faq-label">
                                    利用状態 <span class="required-badge">必須</span>
                                </label>
                                <select id="is_active" name="is_active" class="text-select" required>
                                    <option value="1" {{ (string) old('is_active', $sessionInput['is_active'] ?? '1') === '1' ? 'selected' : '' }}>有効</option>
                                    <option value="0" {{ (string) old('is_active', $sessionInput['is_active'] ?? '') === '0' ? 'selected' : '' }}>停止</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="sort-guide form-guide">
                        <p class="form-guide-title">入力ルール</p>
                        <ul class="form-guide-list">
                            <li>アカウントは1件ずつ登録します</li>
                            <li>メールアドレスは他のアカウントと重複しない値を入力してください</li>
                            <li>パスワードは8文字以上で設定してください</li>
                            <li>権限は運用ルールに沿って選択してください</li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('users.index') }}" class="header-sub-button form-back-button">
                            キャンセル
                        </a>

                        <button type="submit" class="header-main-button form-submit-button">
                            確認する
                        </button>
                    </div>
                </form>
            </section>
        </section>
    </main>
</div>
</body>
</html>