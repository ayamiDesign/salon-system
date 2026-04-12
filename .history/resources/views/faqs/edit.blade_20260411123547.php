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
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">FAQを編集</h1>
                    <p class="search-sub">
                        登録済みFAQの内容を編集できます。内容を確認して更新してください。
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
                        <p class="form-card-sub">FAQの質問・回答・参考資料を修正できます。</p>
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
                                                @foreach ($categoriesList as $category)
                                                    <option
                                                        value="{{ $category->id }}"
                                                        @selected(old('category1_id', ?? $faq->category1_id ?? '') == $category->id)
                                                    >
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
                                                @foreach ($categoriesList as $category)
                                                    <option
                                                        value="{{ $category->id }}"
                                                        @selected(old('category2_id',$faq->category2_id ?? '') == $category->id)
                                                    >
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
                                            >{{ old('question',$faq->question ?? '') }}</textarea>
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
                                            >{{ old('answer',$faq->answer ?? '') }}</textarea>
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
                                            >{{ old('note', $faq->note ?? '') }}</textarea>
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
                                                value="{{ old('url', $faq->url ?? '') }}"
                                                placeholder="https://example.com"
                                                class="text-input"
                                            >
                                        </div>

                                        {{-- <div>
                                            <label class="faq-label">PDFファイル</label>

                                            <input
                                                type="file"
                                                name="pdf"
                                                accept="application/pdf"
                                                class="file-input"
                                            >

                                            <input
                                                type="hidden"
                                                name="current_pdf_original_name"
                                                value="{{ old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name ?? '') }}"
                                            >
                                            <input
                                                type="hidden"
                                                name="current_pdf_path"
                                                value="{{ old('current_pdf_path', $session['current_pdf_path'] ?? $faq->current_pdf_path ?? '') }}"
                                            >
                                            <input
                                                type="hidden"
                                                name="delete_pdf"
                                                value="0"
                                            >

                                            @if (!empty(old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name ?? '')))
                                                <div class="pdf-current-row">
                                                    <div class="pdf-current-info">
                                                        <span class="pdf-current-label">現在のファイル</span>
                                                        <span class="file-badge">
                                                            {{ old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name ?? '') }}
                                                        </span>
                                                    </div>

                                                    <label class="pdf-delete-check">
                                                        <input
                                                            type="checkbox"
                                                            name="delete_pdf"
                                                            value="1"
                                                            class="toggle-input"
                                                            {{ old('delete_pdf', $session['delete_pdf'] ?? 0) == 1 ? 'checked' : '' }}
                                                        >
                                                        <span>削除する</span>
                                                    </label>
                                                </div>
                                            @else
                                                <p class="faq-help-text">PDFのみアップロードできます。</p>
                                            @endif
                                        </div> --}}
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
                                            {{ old('is_visible', $faq->is_visible ?? 0) == 1 ? 'checked' : '' }}
                                        >
                                        <span>表示する</span>
                                    </label>
                                </div>

                                {{-- 変更履歴 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">変更履歴</h3>
                                     <div class="faq-form-stack faq-form-stack-tight">
                                        <div>
                                            <label class="faq-label">
                                                変更メモ <span class="required-badge">（履歴を残す場合は必須）</span>
                                            </label>
                                            <textarea
                                                name="change_summary"
                                                rows="3"
                                                placeholder="例：回答内容を最新ルールに更新 / 表現を修正 など"
                                                class="text-textarea"
                                            >{{ old('change_summary', $faq->change_summary ?? '') }}</textarea>

                                            <p class="faq-help-text">
                                                変更内容の要約を入力しておくと、履歴管理がしやすくなります。
                                            </p>
                                        </div>
                                    </div>

                                    <input type="hidden" name="faq_history" value="0">
                                    <label class="toggle-box">
                                        <input
                                            type="checkbox"
                                            name="faq_history"
                                            value="1"
                                            class="toggle-input"
                                            {{ old('faq_history', $session['faq_history'] ?? $faq->faq_history ?? 1) == 1 ? 'checked' : '' }}
                                        >
                                        <span>変更前の情報を残す</span>
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