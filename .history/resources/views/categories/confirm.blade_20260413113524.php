<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>カテゴリ登録確認</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">入力内容をご確認ください</h1>
                    <p class="search-sub">
                        入力内容を確認し、問題がなければ登録してください。
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
                            <p class="form-card-sub">登録前にカテゴリ名を確認してください。</p>
                        </div>

                        <span class="confirm-status-badge">確認画面</span>
                    </div>

                    <div class="form-body">
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
                            <form action="{{ route('categories.create.back') }}" method="post" class="form-action-form">
                                @csrf
                                @foreach ($categoryNames as $categoryName)
                                    <input type="hidden" name="name[]" value="{{ $categoryName }}">
                                @endforeach

                                <button
                                    type="submit"
                                    class="header-sub-button form-back-button"
                                >
                                    入力画面へ戻る
                                </button>
                            </form>

                            <form action="{{ route('categories.store') }}" method="post" class="form-action-form">
                                @csrf
                                @foreach ($categoryNames as $categoryName)
                                    <input type="hidden" name="name[]" value="{{ $categoryName }}">
                                @endforeach

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
                <section class="faq-card form-card">
                    <div class="form-card-header">
                        <div>
                            <h2 class="form-card-title">確認内容</h2>
                            <p class="form-card-sub">更新内容を確認し、問題がなければ反映してください。</p>
                        </div>

                        <span class="confirm-status-badge">確認画面</span>
                    </div>
                    <div class="form-body">
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
                            <form action="{{ route('categories.edit.back', $id) }}" method="post" class="form-action-form">
                                @csrf
                                <input type="hidden" name="name" value="{{ $requestData['name'] }}">
                                <button
                                    type="submit"
                                    class="header-sub-button form-back-button"
                                >
                                    入力画面へ戻る
                                </button>
                            </form>

                            <form action="{{ route('categories.update', $id) }}" method="post" class="form-action-form">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $requestData['name'] }}">

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