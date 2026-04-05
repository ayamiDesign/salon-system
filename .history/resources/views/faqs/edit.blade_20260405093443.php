@php
    $faqInput = [
        'category1_id' => old('category1_id', $faq->category1_id ?? ''),
        'category2_id' => old('category2_id', $faq->category2_id ?? ''),
        'is_visible'   => old('is_visible', isset($faq->is_visible) ? (int)$faq->is_visible : 1),
        'question'     => old('question', $faq->question ?? ''),
        'answer'       => old('answer', $faq->answer ?? ''),
        'note'         => old('note', $faq->note ?? ''),
        'url'          => old('url', $faq->url ?? ''),
        'save_history' => old('save_history', 1),
        'change_summary' => old('change_summary', ''),
    ];

    $beforeCategory1Name = optional($categories->firstWhere('id', $faq->category1_id))->name ?? '未設定';
    $beforeCategory2Name = optional($categories->firstWhere('id', $faq->category2_id))->name ?? '未設定';
@endphp

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ編集</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">FAQ編集</p>
                    <p class="brand-sub">変更前の内容を確認しながらFAQを編集できます</p>
                </div>
            </div>

            <div class="topbar-actions topbar-actions-sort">
                <a
                    href="{{ route('faqs.index') }}"
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
                    <h1 class="search-heading">FAQを編集</h1>
                    <p class="search-sub">
                        変更前の情報を確認しながら編集できます。必要なら履歴として保存できます。
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
                        <p class="form-card-sub">変更内容を確認してから更新画面へ進めます。</p>
                    </div>
                </div>

                <form
                    action="{{ route('faqs.confirmEdit', $faq->id) }}"
                    method="post"
                    class="form-body"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="faq-form-list">
                        <div class="faq-form-block">
                            <div class="faq-form-block-head">
                                <div class="faq-form-block-title-wrap">
                                    <div class="faq-form-number">1</div>

                                    <div>
                                        <p class="faq-form-block-title">FAQ編集</p>
                                        <p class="faq-form-block-sub">
                                            質問・回答・参考資料を編集してください。
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="faq-form-sections">
                                {{-- 変更前情報 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">変更前の情報</h3>

                                    <div class="before-summary-grid">
                                        <div class="before-summary-item">
                                            <span class="before-summary-label">メインカテゴリ</span>
                                            <span class="before-summary-value">{{ $category1_name }}</span>
                                        </div>

                                        <div class="before-summary-item">
                                            <span class="before-summary-label">表示設定</span>
                                            <span class="before-summary-value">{{ !empty($faq->is_visible) ? '表示する' : '非表示' }}</span>
                                        </div>
                                    </div>

                                    <details class="before-details">
                                        <summary class="before-details-summary">
                                            変更前の詳細を見る
                                        </summary>

                                        <div class="before-details-body">
                                            <div class="faq-form-stack">
                                                <div>
                                                    <p class="faq-label">カテゴリ（メイン）</p>
                                                    <div class="confirm-value-box">
                                                        {{ $beforeCategory1Name }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">カテゴリ（サブ）</p>
                                                    <div class="confirm-value-box {{ $faq->category2_id ? '' : 'is-empty' }}">
                                                        {{ $faq->category2_id ? $beforeCategory2Name : '未設定' }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">質問</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline">
                                                        {{ trim($faq->question ?? '') }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">回答</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline">
                                                        {{ trim($faq->answer ?? '') }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">あわせて確認</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline {{ filled($faq->note) ? '' : 'is-empty' }}">
                                                        {{ filled($faq->note) ? trim($faq->note) : '未入力' }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">参考URL</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline {{ filled($faq->url) ? '' : 'is-empty' }}">
                                                        {{ filled($faq->url) ? $faq->url : '未入力' }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">PDFファイル</p>
                                                    <div class="confirm-value-box {{ filled($faq->pdf_original_name) ? '' : 'is-empty' }}">
                                                        {{ filled($faq->pdf_original_name) ? $faq->pdf_original_name : '未設定' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                </div>

                                {{-- 履歴保存設定 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">履歴保存設定</h3>

                                    <label class="toggle-box">
                                        <input type="hidden" name="save_history" value="0">
                                        <input
                                            type="checkbox"
                                            name="save_history"
                                            value="1"
                                            class="toggle-input"
                                            {{ (int)$faqInput['save_history'] === 1 ? 'checked' : '' }}
                                        >
                                        <span>変更前の情報を faq_history に残す</span>
                                    </label>

                                    <div class="faq-form-stack faq-form-stack-tight">
                                        <div>
                                            <label class="faq-label">
                                                変更メモ
                                            </label>
                                            <textarea
                                                name="change_summary"
                                                rows="3"
                                                placeholder="例：回答内容を2026年版の運用ルールに更新"
                                                class="text-textarea"
                                            >{{ $faqInput['change_summary'] }}</textarea>
                                            <p class="faq-help-text">
                                                履歴を残す際に、変更内容の要約として使えるようにしておくと管理しやすいです。
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- 基本情報 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">基本情報</h3>

                                    <div class="faq-form-grid faq-form-grid-2">
                                        <div>
                                            <label class="faq-label">
                                                カテゴリ（メイン） <span class="required-badge">必須</span>
                                            </label>
                                            <select
                                                name="category1_id"
                                                class="text-select"
                                                required
                                            >
                                                <option value="">選択してください</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected($faqInput['category1_id'] == $category->id)>
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
                                                name="category2_id"
                                                class="text-select"
                                            >
                                                <option value="">選択してください</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected($faqInput['category2_id'] == $category->id)>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- FAQ内容 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">FAQ内容</h3>

                                    <div class="faq-form-stack">
                                        <div>
                                            <label class="faq-label">
                                                質問 <span class="required-badge">必須</span>
                                            </label>
                                            <textarea
                                                name="question"
                                                rows="3"
                                                placeholder="例：施術に入る順番はどうやって決める？"
                                                class="text-textarea"
                                                required
                                            >{{ $faqInput['question'] }}</textarea>
                                        </div>

                                        <div>
                                            <label class="faq-label">
                                                回答 <span class="required-badge">必須</span>
                                            </label>
                                            <textarea
                                                name="answer"
                                                rows="6"
                                                placeholder="例：① 施術が先に終わった人が優先&#10;② 直前の予約状況が同じ場合は出勤順"
                                                class="text-textarea"
                                                required
                                            >{{ $faqInput['answer'] }}</textarea>
                                        </div>

                                        <div>
                                            <label class="faq-label">
                                                あわせて確認
                                            </label>
                                            <textarea
                                                name="note"
                                                rows="4"
                                                placeholder="例：予約表で押さえられている時間を基準に判断してください。"
                                                class="text-textarea"
                                            >{{ $faqInput['note'] }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- 参考資料 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">参考資料</h3>

                                    <div class="faq-form-grid faq-form-grid-2">
                                        <div>
                                            <label class="faq-label">参考URL</label>
                                            <input
                                                type="url"
                                                name="url"
                                                value="{{ $faqInput['url'] }}"
                                                placeholder="https://example.com"
                                                class="text-input"
                                            >
                                        </div>

                                        <div>
                                            <label class="faq-label">PDFファイル</label>
                                            <input
                                                type="file"
                                                name="pdf"
                                                accept="application/pdf"
                                                class="file-input"
                                            >

                                            @if (!empty($faq->pdf_original_name))
                                                <p class="faq-help-text">
                                                    現在のファイル：{{ $faq->pdf_original_name }}
                                                </p>
                                            @else
                                                <p class="faq-help-text">PDFのみアップロードできます。</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- 表示設定 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">表示設定</h3>

                                    <label class="toggle-box">
                                        <input type="hidden" name="is_visible" value="0">
                                        <input
                                            type="checkbox"
                                            name="is_visible"
                                            value="1"
                                            class="toggle-input"
                                            {{ (int)$faqInput['is_visible'] === 1 ? 'checked' : '' }}
                                        >
                                        <span>表示する</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sort-guide form-guide">
                        <p class="form-guide-title">入力ルール</p>
                        <ul class="form-guide-list">
                            <li>カテゴリはメイン1つ、必要に応じてサブ1つまで設定してください</li>
                            <li>表示順は一覧画面で調整してください</li>
                            <li>PDFを差し替える場合は再アップロードしてください</li>
                            <li>履歴を残す場合は変更メモも入れておくと管理しやすいです</li>
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
</body>
</html>