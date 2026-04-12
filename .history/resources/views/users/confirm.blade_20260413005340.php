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

            @if($mode === 'create')
                <section class="faq-card form-card account-form-card">
                    <div class="form-card-header">
                        <div>
                            <h2 class="form-card-title">確認フォーム</h2>
                            <p class="form-card-sub">以下の内容で登録します。</p>
                        </div>
                    </div>

                    <div class="form-body">
                        <div class="account-form-stack">
                            <div class="account-form-field">
                                <p class="faq-label">
                                    名前 <span class="required-badge">必須</span>
                                </p>
                                <div class="confirm-value-box">
                                    {{ $userData['name'] }}
                                </div>
                            </div>

                            <div class="account-form-field">
                                <p class="faq-label">
                                    メールアドレス <span class="required-badge">必須</span>
                                </p>
                                <div class="confirm-value-box">
                                    {{ $userData['email'] }}
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
                                        @switch($userData['role'])
                                            @case('admin')
                                                管理者
                                                @break
                                            @case('manager')
                                                店長
                                                @break
                                            @case('staff')
                                                スタッフ
                                                @break
                                            @default
                                                {{ $userData['role'] }}
                                        @endswitch
                                    </div>
                                </div>

                                <div class="account-form-field">
                                    <p class="faq-label">
                                        利用状態 <span class="required-badge">必須</span>
                                    </p>
                                    <div class="confirm-value-box">
                                        {{ (string) $userData['is_active'] === '1' ? '有効' : '停止' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="confirm-note faq-confirm-note">
                            <p class="confirm-note-title">確認のお願い</p>
                            <p class="block-text">
                                登録後は、アカウント一覧から内容を確認できます。
                            </p>
                        </div>

                        <div class="form-actions">
                            <form action="{{ route('users.create.back') }}" method="post" class="form-action-form">
                                @csrf
                                <input type="hidden" name="name" value="{{ $userData['name'] }}">
                                <input type="hidden" name="email" value="{{ $userData['email'] }}">
                                <input type="hidden" name="password" value="{{ $userData['password'] }}">
                                <input type="hidden" name="role" value="{{ $userData['role'] }}">
                                <input type="hidden" name="is_active" value="{{ $userData['is_active'] }}">

                                <button
                                    type="submit"
                                    class="header-sub-button form-back-button"
                                >
                                    入力画面へ戻る
                                </button>
                            </form>

                            <form action="{{ route('users.store') }}" method="post" class="form-action-form">
                                @csrf
                                <input type="hidden" name="name" value="{{ $userData['name'] }}">
                                <input type="hidden" name="email" value="{{ $userData['email'] }}">
                                <input type="hidden" name="password" value="{{ $userData['password'] }}">
                                <input type="hidden" name="role" value="{{ $userData['role'] }}">
                                <input type="hidden" name="is_active" value="{{ $userData['is_active'] }}">

                                <button
                                    type="submit"
                                    class="header-main-button form-submit-button"
                                >
                                    登録する
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            @endif

            @if($mode === 'edit')
                <section class="faq-card form-card account-form-card">
                    <div class="form-card-header">
                        <div>
                            <h2 class="form-card-title">確認フォーム</h2>
                            <p class="form-card-sub">更新内容を確認し、問題がなければ反映してください。</p>
                        </div>
                    </div>

                    <div class="form-body">
                        <div class="account-form-stack">
                            <div class="account-form-field">
                                <p class="faq-label">
                                    名前 <span class="required-badge">必須</span>
                                </p>
                                <div class="confirm-value-box">
                                    {{ $userData['name'] }}
                                </div>
                            </div>

                            <div class="account-form-field">
                                <p class="faq-label">
                                    メールアドレス <span class="required-badge">必須</span>
                                </p>
                                <div class="confirm-value-box">
                                    {{ $userData['email'] }}
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
                                        @switch($userData['role'])
                                            @case('admin')
                                                管理者
                                                @break
                                            @case('manager')
                                                店長
                                                @break
                                            @case('staff')
                                                スタッフ
                                                @break
                                            @default
                                                {{ $userData['role'] }}
                                        @endswitch
                                    </div>
                                </div>

                                <div class="account-form-field">
                                    <p class="faq-label">
                                        利用状態 <span class="required-badge">必須</span>
                                    </p>
                                    <div class="confirm-value-box">
                                        {{ (string) $userData['is_active'] === '1' ? '有効' : '停止' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="confirm-note faq-confirm-note">
                            <p class="confirm-note-title">確認のお願い</p>
                            <p class="block-text">
                                登録後は、アカウント一覧から内容を確認できます。
                            </p>
                        </div>

                        <div class="form-actions">
                            <form action="{{ route('users.edit.back', $id) }}" method="post" class="form-action-form">
                                @csrf
                                <input type="hidden" name="name" value="{{ $userData['name'] }}">
                                <input type="hidden" name="email" value="{{ $userData['email'] }}">
                                <input type="hidden" name="password" value="{{ $userData['password'] }}">
                                <input type="hidden" name="role" value="{{ $userData['role'] }}">
                                <input type="hidden" name="is_active" value="{{ $userData['is_active'] }}">

                                <button
                                    type="submit"
                                    class="header-sub-button form-back-button"
                                >
                                    入力画面へ戻る
                                </button>
                            </form>

                            <form action="{{ route('users.update', $id) }}" method="post" class="form-action-form">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $userData['name'] }}">
                                <input type="hidden" name="email" value="{{ $userData['email'] }}">
                                <input type="hidden" name="password" value="{{ $userData['password'] }}">
                                <input type="hidden" name="role" value="{{ $userData['role'] }}">
                                <input type="hidden" name="is_active" value="{{ $userData['is_active'] }}">
                                <button
                                    type="submit"
                                    class="header-main-button form-submit-button"
                                >
                                    更新する
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>
</body>
</html>