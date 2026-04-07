<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント一覧</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="layout category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">アカウントを管理</h1>
                    <p class="search-sub">
                        編集・削除などの操作を、カテゴリ一覧と同じデザインルールで統一しています。
                    </p>
                </div>
            </div>

            <div class="stats-bar">
                <div class="stats-item">
                    <span class="stats-label">総アカウント数</span>
                    <span class="stats-inline-value">{{ $users->count() }}</span>
                </div>
            </div> --}}

            @if ($users->isEmpty())
                <div class="empty">
                    アカウントはまだ登録されていません。
                </div>
            @else
                <div class="faq-list">
                    @foreach ($users as $user)
                        <article class="faq-card account-card">
                            <div class="category-card-head">
                                <div class="category-card-main">
                                    <div class="faq-meta">
                                        <span class="tag">アカウント</span>

                                        @if($user->role)
                                            <span class="tag">{{ $user->role }}</span>
                                        @endif

                                        @if($user->is_active)
                                            <span class="tag account-status-tag is-active">有効</span>
                                        @else
                                            <span class="tag account-status-tag is-inactive">停止中</span>
                                        @endif
                                    </div>

                                    <h2 class="faq-question">
                                        {{ $user->name }}
                                    </h2>

                                    <div class="account-info-list">
                                        <div class="account-info-row">
                                            <span class="account-info-label">メールアドレス</span>
                                            <span class="account-info-value">{{ $user->email }}</span>
                                        </div>

                                        @if(!empty($user->department))
                                            <div class="account-info-row">
                                                <span class="account-info-label">部署</span>
                                                <span class="account-info-value">{{ $user->department }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($user->last_login_at))
                                            <div class="account-info-row">
                                                <span class="account-info-label">最終ログイン</span>
                                                <span class="account-info-value">
                                                    {{ \Carbon\Carbon::parse($user->last_login_at)->format('Y.m.d H:i') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="faq-head-side">
                                    <div class="faq-action-group">
                                        <a
                                            href="{{ route('users.edit', $user->id) }}"
                                            class="row-action-button"
                                        >
                                            編集
                                        </a>

                                        <form
                                            action="{{ route('users.toggle', $user->id) }}"
                                            method="POST"
                                            class="inline-form"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            @if($user->is_active)
                                                <button type="submit" class="row-action-button">
                                                    停止
                                                </button>
                                            @else
                                                <button type="submit" class="row-action-button">
                                                    有効化
                                                </button>
                                            @endif
                                        </form>

                                        <button
                                            type="button"
                                            class="row-action-button delete js-delete-open"
                                            data-id="{{ $users->id }}"
                                            data-name="{{ $users->name }}"
                                        >
                                            削除
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <div id="deleteModal" class="delete-modal hidden">
        <div class="delete-modal-backdrop"></div>
        <div class="delete-modal-card">
            <p class="delete-modal-title">アカウントを削除しますか？</p>
            <p class="delete-modal-text">
                「<span id="deleteItemName"></span>」を削除します。<br>
                この操作は取り消せません。
            </p>

            <div class="delete-modal-actions">
                <button type="button" id="deleteModalClose" class="modal-cancel-button">キャンセル</button>

                <form id="deleteForm" method="POST" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-delete-button">削除する</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>