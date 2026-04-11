<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ編集</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')
    
    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">カテゴリを編集</h1>
                    <p class="search-sub">
                        既存カテゴリ名を修正して、確認画面へ進みます。
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

            <section class="faq-card form-card">
                <div class="form-card-header">
                    <div>
                        <h2 class="form-card-title">編集フォーム</h2>
                        <p class="form-card-sub">カテゴリ名を修正して保存内容を確認してください。</p>
                    </div>
                </div>

                <form action="{{ route('categories.confirmEdit', $category->id) }}" method="post" class="form-body">
                    @csrf

                    <div class="input-list-card">
                        <div class="input-list-head input-list-head-edit">
                            <div class="input-list-col-no">No.</div>
                            <div class="input-list-col-name">カテゴリ名</div>
                        </div>

                        <div id="category-list" class="input-list-body">
                            <div class="category-row input-row">
                                <div class="input-row-grid input-row-grid-edit">

                                    <div class="input-cell input-cell-no">
                                        <span class="mobile-label">No.</span>
                                        <div class="row-number">1</div>
                                    </div>

                                    <div class="input-cell input-cell-name">
                                        <label class="mobile-label">
                                            カテゴリ名 <span class="required-badge">必須</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ old('name', $category->name ?? '') }}"
                                            placeholder="例：予約・受付"
                                            class="text-input"
                                            required
                                        >
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sort-guide form-guide">
                        <p class="form-guide-title">入力ルール</p>
                        <ul class="form-guide-list">
                            <li>カテゴリ名は、内容が分かりやすい名称で入力してください</li>
                            <li>既存FAQで利用中のカテゴリ名もここから変更できます</li>
                            <li>表示順の変更は一覧画面の並び替えから行えます</li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <a
                            href="{{ route('categories.index') }}"
                            class="header-sub-button form-back-button"
                        >
                            キャンセル
                        </a>

                        <button
                            type="submit"
                            class="header-main-button form-submit-button"
                        >
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