{{-- resources/views/accounts/index.blade.php --}}
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
                    <span class="stats-inline-value">{{ $accounts->count() }}</span>
                </div>
            </div>

            @if ($accounts->isEmpty())
                <div class="empty">
                    アカウントはまだ登録されていません。
                </div>
            @else
                <div class="faq-list">
                    @foreach ($accounts as $account)
                        <article class="faq-card account-card">
                            <div class="category-card-head">
                                <div class="category-card-main">
                                    <div class="faq-meta">
                                        <span class="tag">アカウント</span>

                                        @if($account->role)
                                            <span class="tag">{{ $account->role }}</span>
                                        @endif

                                        @if($account->is_active)
                                            <span class="tag account-status-tag is-active">有効</span>
                                        @else
                                            <span class="tag account-status-tag is-inactive">停止中</span>
                                        @endif
                                    </div>

                                    <h2 class="faq-question">
                                        {{ $account->name }}
                                    </h2>

                                    <div class="account-info-list">
                                        <div class="account-info-row">
                                            <span class="account-info-label">メールアドレス</span>
                                            <span class="account-info-value">{{ $account->email }}</span>
                                        </div>

                                        @if(!empty($account->department))
                                            <div class="account-info-row">
                                                <span class="account-info-label">部署</span>
                                                <span class="account-info-value">{{ $account->department }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($account->last_login_at))
                                            <div class="account-info-row">
                                                <span class="account-info-label">最終ログイン</span>
                                                <span class="account-info-value">
                                                    {{ \Carbon\Carbon::parse($account->last_login_at)->format('Y.m.d H:i') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="faq-head-side">
                                    <div class="faq-action-group">
                                        <a
                                            href="{{ route('accounts.edit', $account->id) }}"
                                            class="row-action-button"
                                        >
                                            編集
                                        </a>

                                        <form
                                            action="{{ route('accounts.toggle', $account->id) }}"
                                            method="POST"
                                            class="inline-form"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            @if($account->is_active)
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
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
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
                <button type="button" id="deleteModalClose" class="modal-cancel-button">
                    キャンセル
                </button>

                <form id="deleteForm" method="POST" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-delete-button">
                        削除する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    const deleteItemName = document.getElementById('deleteItemName');
    const deleteForm = document.getElementById('deleteForm');
    const closeButton = document.getElementById('deleteModalClose');
    const backdrop = document.querySelector('.delete-modal-backdrop');

    document.querySelectorAll('.js-delete-open').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;

            deleteItemName.textContent = name;
            deleteForm.action = `/accounts/${id}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    closeButton?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);
});
</script>
</body>
</html>