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

            @if ($users->isEmpty())
                <div class="empty">
                    アカウントはまだ登録されていません。
                </div>
            @else
                <div class="admin-entity-list">
                    @foreach ($users as $user)
                        <article class="admin-entity-card">
                            <div class="admin-entity-body">
                                <div class="admin-entity-main">
                                    <h2 class="admin-entity-title">
                                        {{ $user->name }}
                                    </h2>

                                    <p class="admin-entity-sub">
                                        {{ $user->email }}
                                    </p>

                                    <div class="admin-entity-meta">
                                        <span class="admin-entity-chip">
                                            {{ $user->role }}
                                        </span>

                                        @if($user->is_active)
                                            <span class="admin-entity-chip admin-entity-chip--success">
                                                有効
                                            </span>
                                        @else
                                            <span class="admin-entity-chip admin-entity-chip--danger">
                                                停止中
                                            </span>
                                        @endif
                                    </div>

                                    @if(!empty($user->last_login_at))
                                        <p class="admin-entity-note">
                                            最終ログイン：{{ \Carbon\Carbon::parse($user->last_login_at)->format('Y.m.d H:i') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="admin-entity-actions">
                                    <a
                                        href="{{ route('users.edit', $user->id) }}"
                                        class="admin-entity-action"
                                    >
                                        編集
                                    </a>

                                    <button
                                        type="button"
                                        class="admin-entity-action admin-entity-action--delete js-delete-open"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                    >
                                        削除
                                    </button>
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