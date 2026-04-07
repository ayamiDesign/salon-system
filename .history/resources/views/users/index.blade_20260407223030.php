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
                <div class="admin-table-empty">
                    アカウントはまだ登録されていません。
                </div>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 18%;">名前</th>
                                <th style="width: 32%;">メールアドレス</th>
                                <th style="width: 14%;">権限</th>
                                <th style="width: 14%;">状態</th>
                                <th style="width: 22%;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <p class="admin-table-title">{{ $user->name }}</p>
                                    </td>
                                    <td>
                                        <p class="admin-table-title">{{ $user->email }}</p>
                                        @if(!empty($user->last_login_at))
                                            <p class="admin-table-sub">
                                                最終ログイン：{{ \Carbon\Carbon::parse($user->last_login_at)->format('Y.m.d H:i') }}
                                            </p>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="admin-role">
                                            @if($user->role === 'admin')
                                            <span class="admin-status admin-status--active">管理者</span>
                                            @elseif($user->role === 'manager')
                                                <span class="admin-status admin-status--inactive">店長</span>
                                            @elseif($user->role === 'staff')
                                                <span class="admin-status admin-status--inactive">スタッフ</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="admin-status admin-status--active">有効</span>
                                        @else
                                            <span class="admin-status admin-status--inactive">停止</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="admin-table-actions">
                                            <a
                                                href="{{ route('users.edit', $user->id) }}"
                                                class="admin-table-button"
                                            >
                                                編集
                                            </a>

                                            <button
                                                type="button"
                                                class="admin-table-button admin-table-button--delete js-delete-open"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                            >
                                                削除
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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