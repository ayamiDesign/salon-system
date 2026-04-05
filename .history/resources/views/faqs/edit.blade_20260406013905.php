@php
    $session = session('faq_input');
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
                    <p class="brand-sub">FAQ一覧と同じデザインルールで統一した編集画面</p>
                </div>
            </div>

            <div class="topbar-actions topbar-actions-sort">
                <a href="{{ route('faqs.index') }}" class="header-sub-button">
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

                <form action="{{ route('faqs.confirmEdit', $faq->id) }}" method="post" class="form-body" enctype="multipart/form-data">
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

                                {{-- FAQ内容 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">FAQ内容</h3>

                                    <div class="faq-form-stack">
                                        <div>
                                            <label class="faq-label">
                                                質問 <span class="required-badge">必須</span>
                                            </label>
                                            <textarea name="question" rows="3" class="text-textarea" required>{{ old('question', $session['question'] ?? $faq->question ?? '') }}</textarea>
                                        </div>

                                        <div>
                                            <label class="faq-label">
                                                回答 <span class="required-badge">必須</span>
                                            </label>
                                            <textarea name="answer" rows="6" class="text-textarea" required>{{ old('answer', $session['answer'] ?? $faq->answer ?? '') }}</textarea>
                                        </div>

                                        <div>
                                            <label class="faq-label">あわせて確認</label>
                                            <textarea name="note" rows="4" class="text-textarea">{{ old('note', $session['note'] ?? $faq->note ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- 参考資料 --}}
                                <div class="faq-form-section">
                                    <h3 class="faq-form-section-title">参考資料</h3>

                                    <div class="faq-form-grid faq-form-grid-2">
                                        <div>
                                            <label class="faq-label">参考URL</label>
                                            <input type="url" name="url" value="{{ old('url', $session['url'] ?? $faq->url ?? '') }}" class="text-input">
                                        </div>

                                        <div>
                                            <label class="faq-label">PDFファイル</label>
                                            <input type="file" name="pdf" accept="application/pdf" class="file-input">

                                            @if (!empty(old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name ?? '')))
                                                <div class="pdf-current-row">
                                                    <div class="pdf-current-info">
                                                        <span class="pdf-current-label">現在のファイル</span>
                                                    </div>

                                                    <label class="pdf-delete-check">
                                                        <input
                                                            type="checkbox"
                                                            name="delete_pdf"
                                                            value="1"
                                                            class="toggle-input"
                                                            {{ old('delete_pdf', ($session['delete_pdf'] ?? $faq->delete_pdf ?? 0)) == 1 ? 'checked' : '' }}
                                                        >
                                                        <span>削除する</span>
                                                    </label>
                                                </div>
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
                                            {{ old('is_visible', ($session['is_visible'] ?? $faq->is_visible ?? 0)) == 1 ? 'checked' : '' }}
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
                                                変更メモ
                                            </label>
                                            <textarea name="change_summary" rows="3" class="text-textarea">{{ old('change_summary', $session['change_summary'] ?? $faq->change_summary ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <label class="toggle-box">
                                        <input
                                            type="checkbox"
                                            name="faq_history"
                                            value="1"
                                            class="toggle-input"
                                            {{ old('faq_history', ($session['faq_history'] ?? $faq->faq_history ?? 0)) == 1 ? 'checked' : '' }}
                                        >
                                        <span>変更前の情報を残す</span>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('faqs.index') }}" class="header-sub-button">
                            キャンセル
                        </a>

                        <button type="submit" class="header-main-button">
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