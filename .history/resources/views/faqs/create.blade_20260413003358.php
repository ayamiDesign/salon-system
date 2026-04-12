<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">FAQを登録</h1>
                    <p class="search-sub">
                        現場で確認が必要な手順や対応方法を、FAQとして整理・登録できます。
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
                        <p class="form-card-sub">業務で共有が必要な内容をまとめて登録できます。</p>
                    </div>

                    <button
                        type="button"
                        onclick="addFaqBlock()"
                        class="action-button action-button-primary form-add-button"
                    >
                        ＋ 入力欄を追加
                    </button>
                </div>

                <form action="{{ route('faqs.confirm') }}" method="post" class="form-body" enctype="multipart/form-data">
                    @csrf

                    <div id="faq-list" class="faq-form-list">
                        @foreach(old('faqs', [[]]) as $index => $faq)
                            <div class="faq-form-block">
                                <div class="faq-form-block-head">
                                    <div class="faq-form-block-title-wrap">
                                        <div class="faq-form-number faq-number">{{ $index + 1 }}</div>

                                        <div>
                                            <p class="faq-form-block-title">
                                                FAQ <span class="faq-number-text">{{ $index + 1 }}</span>
                                            </p>
                                            <p class="faq-form-block-sub">
                                                質問・回答・参考資料を入力してください。
                                            </p>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        onclick="removeFaqBlock(this)"
                                        class="row-action-button delete faq-remove-button"
                                    >
                                        削除
                                    </button>
                                </div>

                                <div class="faq-form-sections">
                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">基本情報</h3>

                                        <div class="faq-form-grid faq-form-grid-2">
                                            <div>
                                                <label class="faq-label">
                                                    カテゴリ（メイン） <span class="required-badge">必須</span>
                                                </label>
                                                <select
                                                    name="faqs[{{ $index }}][category1_id]"
                                                    class="text-select"
                                                    required
                                                >
                                                    <option value="">選択してください</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @selected(($faq['category1_id'] ?? '') == $category->id)>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="faq-label">
                                                    カテゴリ（サブ・任意）
                                                </label>
                                                <select
                                                    name="faqs[{{ $index }}][category2_id]"
                                                    class="text-select"
                                                >
                                                    <option value="">選択してください</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @selected(($faq['category2_id'] ?? '') == $category->id)>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">FAQ内容</h3>

                                        <div class="faq-form-stack">
                                            <div>
                                                <label class="faq-label">
                                                    質問 <span class="required-badge">必須</span>
                                                </label>
                                                <textarea
                                                    name="faqs[{{ $index }}][question]"
                                                    rows="3"
                                                    placeholder="例：施術に入る順番はどうやって決める？"
                                                    class="text-textarea"
                                                    required
                                                >{{ $faq['question'] ?? '' }}</textarea>
                                            </div>

                                            <div>
                                                <label class="faq-label">
                                                    回答 <span class="required-badge">必須</span>
                                                </label>
                                                <textarea
                                                    name="faqs[{{ $index }}][answer]"
                                                    rows="6"
                                                    placeholder="例：① 施術が先に終わった人が優先&#10;② 直前の予約状況が同じ場合は出勤順"
                                                    class="text-textarea"
                                                    required
                                                >{{ $faq['answer'] ?? '' }}</textarea>
                                            </div>

                                            <div>
                                                <label class="faq-label">
                                                    あわせて確認
                                                </label>
                                                <textarea
                                                    name="faqs[{{ $index }}][note]"
                                                    rows="4"
                                                    placeholder="例：予約表で押さえられている時間を基準に判断してください。"
                                                    class="text-textarea"
                                                >{{ $faq['note'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">参考資料</h3>

                                        <div class="faq-form-grid">
                                        {{-- <div class="faq-form-grid faq-form-grid-2"> --}}
                                            <div>
                                                <label class="faq-label">参考URL</label>
                                                <input
                                                    type="url"
                                                    name="faqs[{{ $index }}][url]"
                                                    value="{{ $faq['url'] ?? '' }}"
                                                    placeholder="https://example.com"
                                                    class="text-input"
                                                >
                                            </div>

                                            {{-- <div>
                                                <label class="faq-label">PDFファイル</label>
                                                <input
                                                    type="file"
                                                    name="faqs[{{ $index }}][pdf]"
                                                    accept="application/pdf"
                                                    class="file-input"
                                                >
                                                <p class="faq-help-text">PDFのみアップロードできます。</p>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">表示設定</h3>

                                        <label class="toggle-box">
                                            <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="0">
                                            <input
                                                type="checkbox"
                                                name="faqs[{{ $index }}][is_visible]"
                                                value="1"
                                                class="toggle-input"
                                                {{ !isset($faq['is_visible']) || $faq['is_visible'] ? 'checked' : '' }}
                                            >
                                            <span>表示する</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="sort-guide form-guide">
                        <p class="form-guide-title">入力ルール</p>
                        <ul class="form-guide-list">
                            <li>カテゴリはメイン1つ、必要に応じてサブ1つまで設定してください</li>
                            <li>表示順は登録後の一覧画面で調整してください</li>
                        </ul>
                    </div>
                    <div class="form-actions">
                        <a
                            href="{{ route('faqs.index') }}"
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

@php
    $categoryOptions = '<option value="">選択してください</option>';
    foreach ($categories as $category) {
        $categoryOptions .= '<option value="' . $category->id . '">' . e($category->name) . '</option>';
    }
@endphp

<script>
    const categoryOptionsHtml = `{!! $categoryOptions !!}`;

    function addFaqBlock() {
        const container = document.getElementById('faq-list');
        const index = document.querySelectorAll('#faq-list .faq-form-block').length;

        const div = document.createElement('div');
        div.className = 'faq-form-block';

        div.innerHTML = `
            <div class="faq-form-block-head">
                <div class="faq-form-block-title-wrap">
                    <div class="faq-form-number faq-number">${index + 1}</div>

                    <div>
                        <p class="faq-form-block-title">
                            FAQ <span class="faq-number-text">${index + 1}</span>
                        </p>
                        <p class="faq-form-block-sub">
                            質問・回答・参考資料を入力してください。
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="removeFaqBlock(this)"
                    class="row-action-button delete faq-remove-button"
                >
                    削除
                </button>
            </div>

            <div class="faq-form-sections">
                <div class="faq-form-section">
                    <h3 class="faq-form-section-title">基本情報</h3>

                    <div class="faq-form-grid faq-form-grid-2">
                        <div>
                            <label class="faq-label">
                                カテゴリ（メイン） <span class="required-badge">必須</span>
                            </label>
                            <select
                                name="faqs[${index}][category1_id]"
                                class="text-select"
                                required
                            >
                                ${categoryOptionsHtml}
                            </select>
                        </div>

                        <div>
                            <label class="faq-label">
                                カテゴリ（サブ・任意）
                            </label>
                            <select
                                name="faqs[${index}][category2_id]"
                                class="text-select"
                            >
                                ${categoryOptionsHtml}
                            </select>
                        </div>
                    </div>
                </div>

                <div class="faq-form-section">
                    <h3 class="faq-form-section-title">FAQ内容</h3>

                    <div class="faq-form-stack">
                        <div>
                            <label class="faq-label">
                                質問 <span class="required-badge">必須</span>
                            </label>
                            <textarea
                                name="faqs[${index}][question]"
                                rows="3"
                                placeholder="例：施術に入る順番はどうやって決める？"
                                class="text-textarea"
                                required
                            ></textarea>
                        </div>

                        <div>
                            <label class="faq-label">
                                回答 <span class="required-badge">必須</span>
                            </label>
                            <textarea
                                name="faqs[${index}][answer]"
                                rows="6"
                                placeholder="例：① 施術が先に終わった人が優先&#10;② 直前の予約状況が同じ場合は出勤順"
                                class="text-textarea"
                                required
                            ></textarea>
                        </div>

                        <div>
                            <label class="faq-label">
                                あわせて確認
                            </label>
                            <textarea
                                name="faqs[${index}][note]"
                                rows="4"
                                placeholder="例：予約表で押さえられている時間を基準に判断してください。"
                                class="text-textarea"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="faq-form-section">
                    <h3 class="faq-form-section-title">参考資料</h3>

                    <div class="faq-form-grid">
                        <div>
                            <label class="faq-label">参考URL</label>
                            <input
                                type="url"
                                name="faqs[${index}][url]"
                                placeholder="https://example.com"
                                class="text-input"
                            >
                        </div>
                    </div>
                </div>

                <div class="faq-form-section">
                    <h3 class="faq-form-section-title">表示設定</h3>

                    <label class="toggle-box">
                        <input type="hidden" name="faqs[${index}][is_visible]" value="0">
                        <input
                            type="checkbox"
                            name="faqs[${index}][is_visible]"
                            value="1"
                            class="toggle-input"
                            checked
                        >
                        <span>表示する</span>
                    </label>
                </div>
            </div>
        `;

        container.appendChild(div);
        updateFaqNumbers();
        updateFaqInputNames();
    }

    function removeFaqBlock(button) {
        const rows = document.querySelectorAll('#faq-list .faq-form-block');

        if (rows.length === 1) {
            alert('最低1件は入力欄が必要です。');
            return;
        }

        button.closest('.faq-form-block').remove();
        updateFaqNumbers();
        updateFaqInputNames();
    }

    function updateFaqNumbers() {
        document.querySelectorAll('#faq-list .faq-form-block').forEach((block, index) => {
            const number = index + 1;
            const badge = block.querySelector('.faq-number');
            const text = block.querySelector('.faq-number-text');

            if (badge) badge.textContent = number;
            if (text) text.textContent = number;
        });
    }

    function updateFaqInputNames() {
        document.querySelectorAll('#faq-list .faq-form-block').forEach((block, index) => {
            block.querySelectorAll('input, select, textarea').forEach((field) => {
                const name = field.getAttribute('name');
                if (!name) return;
                field.setAttribute('name', name.replace(/faqs\[\d+\]/, `faqs[${index}]`));
            });
        });
    }
</script>
</body>
</html>