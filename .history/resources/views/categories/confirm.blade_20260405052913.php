<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ登録確認</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">
                        {{ $mode === 'edit' ? 'カテゴリ編集確認' : 'カテゴリ登録確認' }}
                    </p>
                    <p class="brand-sub">一覧・新規登録・編集と同じデザインルールで統一した確認画面</p>
                </div>
            </div>

            <div class="topbar-actions topbar-actions-sort">
                <a
                    href="{{ route('categories.index') }}"
                    class="header-sub-button"
                >
                    一覧へ戻る
                </a>
            </div>
        </div>
    </header>

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">入力内容をご確認ください</h1>
                    <p class="search-sub">
                        内容に問題がなければ、そのまま登録してください。修正が必要な場合は入力画面へ戻れます。
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

            @if($mode === 'create')
                <section class="faq-card form-card">
                    <div class="form-card-header">
                        <div>
                            <h2 class="form-card-title">確認一覧</h2>
                            <p class="form-card-sub">以下の内容でカテゴリを登録します。</p>
                        </div>

                        <span class="confirm-status-badge">確認画面</span>
                    </div>

                    <form action="{{ route('categories.store') }}" method="post" class="form-body">
                        @csrf

                        <div class="input-list-card">
                            <div class="input-list-head input-list-head-confirm">
                                <div class="input-list-col-no">No.</div>
                                <div class="input-list-col-name">カテゴリ名</div>
                            </div>

                            <div class="input-list-body">
                                @foreach ($categoryNames as $index => $categoryName)
                                    <div class="input-row">
                                        <div class="input-row-grid input-row-grid-confirm">
                                            <div class="input-cell input-cell-no">
                                                <div class="mobile-label">No.</div>
                                                <div class="row-number">{{ $index + 1 }}</div>
                                            </div>

                                            <div class="input-cell input-cell-name">
                                                <div class="mobile-label">カテゴリ名</div>
                                                <div class="confirm-value-box">{{ $categoryName }}</div>
                                                <input type="hidden" name="name[]" value="{{ $categoryName }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="confirm-note">
                            <p class="confirm-note-title">ご確認ください</p>
                            <ul class="form-guide-list">
                                <li>カテゴリ名に誤字脱字がないか確認してください</li>
                                <li>表示順は登録後に一覧画面から変更できます</li>
                                <li>修正する場合は「入力画面へ戻る」から戻ってください</li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <a
                                href="{{ route('categories.create') }}"
                                class="header-sub-button form-back-button"
                            >
                                入力画面へ戻る
                            </a>

                            <button
                                type="submit"
                                class="header-main-button form-submit-button"
                            >
                                登録する
                            </button>
                        </div>
                    </form>
                </section>
            @endif

            @if($mode === 'edit')
                <section class="faq-card form-card">
                    <div class="form-card-header">
                        <div>
                            <h2 class="form-card-title">確認内容</h2>
                            <p class="form-card-sub">以下の内容でカテゴリを更新します。</p>
                        </div>

                        <span class="confirm-status-badge">確認画面</span>
                    </div>

                    <form action="{{ route('categories.update', $id) }}" method="post" class="form-body">
                        @csrf
                        @method('PUT')

                        <div class="input-list-card">
                            <div class="input-list-head input-list-head-confirm">
                                <div class="input-list-col-no">No.</div>
                                <div class="input-list-col-name">カテゴリ名</div>
                            </div>

                            <div class="input-list-body">
                                <div class="input-row">
                                    <div class="input-row-grid input-row-grid-confirm">
                                        <div class="input-cell input-cell-no">
                                            <div class="mobile-label">No.</div>
                                            <div class="row-number">1</div>
                                        </div>

                                        <div class="input-cell input-cell-name">
                                            <div class="mobile-label">カテゴリ名</div>
                                            <div class="confirm-value-box">{{ $requestData['name'] }}</div>
                                            <input type="hidden" name="name" value="{{ $requestData['name'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="confirm-note">
                            <p class="confirm-note-title">ご確認ください</p>
                            <ul class="form-guide-list">
                                <li>カテゴリ名に誤字脱字がないか確認してください</li>
                                <li>表示順は登録後に一覧画面から変更できます</li>
                                <li>修正する場合は「入力画面へ戻る」から戻ってください</li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <a
                                href="{{ route('categories.edit', $id) }}"
                                class="header-sub-button form-back-button"
                            >
                                入力画面へ戻る
                            </a>

                            <button
                                type="submit"
                                class="header-main-button form-submit-button"
                            >
                                登録する
                            </button>
                        </div>
                    </form>
                </section>
            @endif

        </section>
    </main>
</div>
</body>
</html>