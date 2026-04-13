<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>カテゴリ新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">カテゴリを登録</h1>
                    <p class="search-sub">
                        FAQの分類に使用するカテゴリを、まとめて登録できます。
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
                        <h2 class="form-card-title">登録フォーム</h2>
                        <p class="form-card-sub">登録するカテゴリ名を入力してください。</p>
                    </div>

                    <button
                        type="button"
                        onclick="addCategoryInput()"
                        class="action-button action-button-primary form-add-button"
                    >
                        ＋ 入力欄を追加
                    </button>
                </div>

                <form action="{{ route('categories.confirm') }}" method="post" class="form-body">
                    @csrf

                    <div class="input-list-card">
                        <div class="input-list-head">
                            <div class="input-list-col-no">No.</div>
                            <div class="input-list-col-name">カテゴリ名</div>
                            <div class="input-list-col-action">操作</div>
                        </div>

                        <div id="category-list" class="input-list-body">
                            @foreach(old('name', ['']) as $index => $categoryName)
                                <div class="category-row input-row">
                                    <div class="input-row-grid">

                                        <div class="input-cell input-cell-no">
                                            <span class="mobile-label">No.</span>
                                            <div class="row-number">{{ $index + 1 }}</div>
                                        </div>

                                        <div class="input-cell input-cell-name">
                                            <label class="mobile-label">
                                                カテゴリ名 <span class="required-badge">必須</span>
                                            </label>
                                            <input
                                                type="text"
                                                name="name[]"
                                                value="{{ $categoryName}}"
                                                placeholder="例：受付"
                                                class="text-input"
                                            >
                                        </div>

                                        <div class="input-cell input-cell-action">
                                            <span class="mobile-label">操作</span>
                                            <button
                                                type="button"
                                                onclick="removeInput(this)"
                                                class="row-action-button delete soft-delete-button"
                                            >
                                                削除
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="sort-guide form-guide">
                        <p class="form-guide-title">入力ルール</p>
                        <ul class="form-guide-list">
                            <li>カテゴリ名は、内容が分かりやすい名称で入力してください</li>
                            <li>表示順は登録後に一覧画面から調整できます</li>
                            <li>不要な行は「削除」ボタンから外してください</li>
                            <li>複数のカテゴリをまとめて登録できます</li>
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

<script>
    function refreshCategoryNumbers() {
        const rows = document.querySelectorAll('.category-row');
        rows.forEach((row, index) => {
            const number = row.querySelector('.row-number');
            if (number) {
                number.textContent = index + 1;
            }
        });
    }

    function addCategoryInput() {
        const container = document.getElementById('category-list');

        const div = document.createElement('div');
        div.className = 'category-row input-row';

        div.innerHTML = `
            <div class="input-row-grid">
                <div class="input-cell input-cell-no">
                    <span class="mobile-label">No.</span>
                    <div class="row-number"></div>
                </div>

                <div class="input-cell input-cell-name">
                    <label class="mobile-label">
                        カテゴリ名 <span class="required-badge">必須</span>
                    </label>
                    <input
                        type="text"
                        name="name[]"
                        placeholder="例：会計"
                        class="text-input"
                    >
                </div>

                <div class="input-cell input-cell-action">
                    <span class="mobile-label">操作</span>
                    <button
                        type="button"
                        onclick="removeInput(this)"
                        class="row-action-button delete soft-delete-button"
                    >
                        削除
                    </button>
                </div>
            </div>
        `;

        container.appendChild(div);
        refreshCategoryNumbers();
    }

    function removeInput(button) {
        const rows = document.querySelectorAll('.category-row');

        if (rows.length === 1) {
            const input = rows[0].querySelector('input[name="name[]"]');
            if (input) input.value = '';
            alert('最低1件は入力欄が必要です。');
            return;
        }

        button.closest('.category-row').remove();
        refreshCategoryNumbers();
    }

    document.addEventListener('DOMContentLoaded', function () {
        refreshCategoryNumbers();
    });
</script>
</body>
</html>