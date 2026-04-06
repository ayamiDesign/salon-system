<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント登録確認</title>
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
                        入力内容を確認し、問題がなければ登録を完了してください。
                    </p>
                </div>
            </div>

            <section class="faq-card form-card account-form-card">
                <div class="form-card-header">
                    <div>
                        <h2 class="form-card-title">確認フォーム</h2>
                        <p class="form-card-sub">以下の内容で登録します。</p>
                    </div>
                </div>

                <form action="{{ route('accounts.store') }}" method="post" class="form-body">
                    @csrf

                    <input type="hidden" name="name" value="{{ $input['name'] }}">
                    <input type="hidden" name="email" value="{{ $input['email'] }}">
                    <input type="hidden" name="password" value="{{ $input['password'] }}">
                    <input type="hidden" name="password_confirmation" value="{{ $input['password_confirmation'] }}">
                    <input type="hidden" name="role" value="{{ $input['role'] }}">
                    <input type="hidden" name="is_active" value="{{ $input['is_active'] }}">

                    <div class="account-form-stack">
                        <div class="account-form-field">
                            <p class="faq-label">
                                名前 <span class="required-badge">必須</span>
                            </p>
                            <div class="confirm-value-box">
                                {{ $input['name'] }}
                            </div>
                        </div>

                        <div class="account-form-field">
                            <p class="faq-label">
                                メールアドレス <span class="required-badge">必須</span>
                            </p>
                            <div class="confirm-value-box">
                                {{ $input['email'] }}
                            </div>
                        </div>

                        <div class="account-form-field">
                            <p class="faq-label">
                                パスワード <span class="required-badge">必須</span>
                            </p>
                            <div class="confirm-value-box">
                                セキュリティのため非表示
                            </div>
                        </div>

                        <div class="account-form-grid">
                            <div class="account-form-field">
                                <p class="faq-label">
                                    権限 <span class="required-badge">必須</span>
                                </p>
                                <div class="confirm-value-box">
                                    @switch($input['role'])
                                        @case('admin')
                                            管理者
                                            @break
                                        @case('manager')
                                            マネージャー
                                            @break
                                        @case('staff')
                                            スタッフ
                                            @break
                                        @default
                                            {{ $input['role'] }}
                                    @endswitch
                                </div>
                            </div>

                            <div class="account-form-field">
                                <p class="faq-label">
                                    利用状態 <span class="required-badge">必須</span>
                                </p>
                                <div class="confirm-value-box">
                                    {{ (string) $input['is_active'] === '1' ? '有効' : '停止' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="confirm-note faq-confirm-note">
                        <p class="confirm-note-title">確認のお願い</p>
                        <p class="block-text">
                            登録後は、メールアドレス・権限・利用状態をアカウント一覧から確認できます。
                        </p>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            name="back"
                            value="1"
                            formaction="{{ route('accounts.create') }}"
                            formmethod="get"
                            class="header-sub-button form-back-button"
                        >
                            入力画面へ戻る
                        </button>

                        <button
                            type="submit"
                            class="header-main-button form-submit-button"
                        >
                            登録する
                        </button>
                    </div>
                </form>
            </section>
        </section>
    </main>
</div>
</body>
</html>