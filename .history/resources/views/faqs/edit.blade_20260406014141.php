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

            <div class="topbar-actions">
                <a href="{{ route('faqs.index') }}" class="header-sub-button">
                    一覧へ戻る
                </a>
            </div>
        </div>
    </header>

    <main class="category-layout-single">
        <section class="content">

            {{-- エラー --}}
            @if ($errors->any())
                <div class="form-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('faqs.confirmEdit', $faq->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div>
                    <label>カテゴリ（メイン）</label>
                    <select name="category1_id" required>
                        <option value="">選択してください</option>
                        @foreach ($categoriesList as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('category1_id', $session['category1_id'] ?? $faq->category1_id ?? '') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>カテゴリ（サブ）</label>
                    <select name="category2_id">
                        <option value="">選択してください</option>
                        @foreach ($categoriesList as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('category2_id', $session['category2_id'] ?? $faq->category2_id ?? '') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>質問</label>
                    <textarea name="question" required>{{ old('question', $session['question'] ?? $faq->question ?? '') }}</textarea>
                </div>

                <div>
                    <label>回答</label>
                    <textarea name="answer" required>{{ old('answer', $session['answer'] ?? $faq->answer ?? '') }}</textarea>
                </div>

                <div>
                    <label>あわせて確認</label>
                    <textarea name="note">{{ old('note', $session['note'] ?? $faq->note ?? '') }}</textarea>
                </div>
                <div>
                    <label>URL</label>
                    <input type="url" name="url" value="{{ old('url', $session['url'] ?? $faq->url ?? '') }}">
                </div>

                <div>
                    <label>PDF</label>
                    <input type="file" name="pdf" accept="application/pdf">
                    <input type="hidden" name="current_pdf_original_name"
                        value="{{ old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name ?? '') }}">
                    <input type="hidden" name="current_pdf_path"
                        value="{{ old('current_pdf_path', $session['current_pdf_path'] ?? $faq->current_pdf_path ?? '') }}">

                    <input type="hidden" name="delete_pdf" value="0">

                    @if (!empty(old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name ?? '')))
                        <div>
                            <p>現在のファイル：
                                {{ old('current_pdf_original_name', $session['current_pdf_original_name'] ?? $faq->current_pdf_original_name) }}
                            </p>

                            <label>
                                <input type="checkbox" name="delete_pdf" value="1"
                                    {{ old('delete_pdf', ($session['delete_pdf'] ?? $faq->delete_pdf ?? 0)) == 1 ? 'checked' : '' }}>
                                削除する
                            </label>
                        </div>
                    @endif
                </div>
                <div>
                    <input type="hidden" name="is_visible" value="0">
                    <label>
                        <input type="checkbox" name="is_visible" value="1"
                            {{ old('is_visible', ($session['is_visible'] ?? $faq->is_visible ?? 0)) == 1 ? 'checked' : '' }}>
                        表示する
                    </label>
                </div>
                <div>
                    <label>変更メモ</label>
                    <textarea name="change_summary">{{ old('change_summary', $session['change_summary'] ?? $faq->change_summary ?? '') }}</textarea>
                </div>

                <div>
                    <input type="hidden" name="faq_history" value="0">
                    <label>
                        <input type="checkbox" name="faq_history" value="1"
                            {{ old('faq_history', ($session['faq_history'] ?? $faq->faq_history ?? 0)) == 1 ? 'checked' : '' }}>
                        変更前の情報を残す
                    </label>
                </div>
                <div>
                    <a href="{{ route('faqs.index') }}">戻る</a>
                    <button type="submit">確認</button>
                </div>

            </form>
        </section>
    </main>
</div>
</body>
</html>