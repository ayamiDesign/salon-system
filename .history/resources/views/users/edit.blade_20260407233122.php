<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント編集</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">アカウントを編集</h1>
                    <p class="search-sub">
                        登録済みアカウントの内容を変更します。
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
                        <h2 class="form-card-title">編集フォーム</h2>
                        <p class="form-card-sub">変更内容を入力し、確認画面へ進んでください。</p>
                    </div>
                </div>

                <form action="{{ route('users.confirmEdit', $id) }}" method="post" class="form-body">
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
                                value="{{ old('name', $sessionInput['name'] ?? $user->name) }}"
                                class="text-input"
                                placeholder="例：山田 花子"
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
                                value="{{ old('email', $sessionInput['email'] ?? $user->email) }}"
                                class="text-input"
                                placeholder="例：sample@example.com"
                                required
                            >
                        </div>

                        <div class="account-form-field">
                            <label for="password" class="faq-label">
                                新しいパスワード
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="text-input"
                                placeholder="変更する場合のみ入力"
                            >
                            <p class="faq-help-text">変更しない場合は空欄のままで問題ありません。</p>
                        </div>

                        <div class="account-form-field">
                            <label for="password_confirmation" class="faq-label">
                                新しいパスワード確認
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="text-input"
                                placeholder="確認用パスワードを入力"
                            >
                        </div>

                        <div class="account-form-grid">
                            <div class="account-form-field">
                                <label for="role" class="faq-label">
                                    権限 <span class="required-badge">必須</span>
                                </label>
                                <select id="role" name="role" class="text-select" required>
                                    <option value="">選択してください</option>
                                    <option value="admin" {{ old('role', $sessionInput['role'] ?? $user->role) === 'admin' ? 'selected' : '' }}>管理者</option>
                                    <option value="manager" {{ old('role', $sessionInput['role'] ?? $user->role) === 'manager' ? 'selected' : '' }}>店長</option>
                                    <option value="staff" {{ old('role', $sessionInput['role'] ?? $user->role) === 'staff' ? 'selected' : '' }}>スタッフ</option>
                                </select>
                            </div>

                            <div class="account-form-field">
                                <label for="is_active" class="faq-label">
                                    利用状態 <span class="required-badge">必須</span>
                                </label>
                                <select id="is_active" name="is_active" class="text-select" required>
                                    <option value="1" {{ (string) old('is_active', $sessionInput['is_active'] ?? $user->is_active) === '1' ? 'selected' : '' }}>有効</option>
                                    <option value="0" {{ (string) old('is_active', $sessionInput['is_active'] ?? $user->is_active) === '0' ? 'selected' : '' }}>停止</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="confirm-note faq-confirm-note">
                        <p class="confirm-note-title">編集時のご注意</p>
                        <p class="block-text">
                            メールアドレス・権限・利用状態の変更は運用に影響するため、内容を確認してから更新してください。
                        </p>
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