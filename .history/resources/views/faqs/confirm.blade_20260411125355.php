<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ登録確認</title>
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
                        登録内容に問題がなければ、そのまま登録してください。必要があれば入力画面へ戻れます。
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
                            <h2 class="form-card-title">確認フォーム</h2>
                            <p class="form-card-sub">FAQごとの内容を確認できます。</p>
                        </div>

                        <span class="confirm-status-badge">確認画面</span>
                    </div>

                    <div class="form-body">

                        <div class="faq-form-list">
                            @foreach ($faqs as $index => $faq)
                                <div class="faq-form-block faq-confirm-block">
                                    <div class="faq-form-block-head">
                                        <div class="faq-form-block-title-wrap">
                                            <div class="faq-form-number">{{ $index + 1 }}</div>

                                            <div>
                                                <p class="faq-form-block-title">FAQ {{ $index + 1 }}</p>
                                                <p class="faq-form-block-sub">登録内容の確認</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="faq-form-sections">
                                        {{-- 基本情報 --}}
                                        <div class="faq-form-section">
                                            <h3 class="faq-form-section-title">基本情報</h3>

                                            <div class="faq-form-grid faq-form-grid-2">
                                                <div>
                                                    <p class="faq-label">カテゴリ（メイン）</p>
                                                    <div class="confirm-value-box">
                                                        {{ $faq['category1_name'] ?? '-' }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">カテゴリ（サブ・任意）</p>
                                                    <div class="confirm-value-box {{ filled($faq['category2_name']) ? '' : 'is-empty' }}">
                                                        {{ filled($faq['category2_name']) ? $faq['category2_name'] : '未選択' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- FAQ内容 --}}
                                        <div class="faq-form-section">
                                            <h3 class="faq-form-section-title">FAQ内容</h3>

                                            <div class="faq-form-stack">
                                                <div>
                                                    <p class="faq-label">質問</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline">
                                                        {{ trim($faq['question']) }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">回答</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline">
                                                        {{ trim($faq['answer']) }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="faq-label">あわせて確認</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline {{ filled($faq['note']) ? '' : 'is-empty' }}">
                                                        {{ filled($faq['note']) ? trim($faq['note']) : '未入力' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 参考資料 --}}
                                        <div class="faq-form-section">
                                            <h3 class="faq-form-section-title">参考資料</h3>

                                            <div class="faq-form-grid">
                                            {{-- <div class="faq-form-grid faq-form-grid-2"> --}}
                                                <div>
                                                    <p class="faq-label">参考URL</p>
                                                    <div class="confirm-value-box confirm-value-box-multiline {{ filled($faq['url']) ? '' : 'is-empty' }}">
                                                        {{ filled($faq['url']) ? $faq['url'] : '未入力' }}
                                                    </div>
                                                </div>

                                                {{-- <div>
                                                    <p class="faq-label">PDFファイル</p>
                                                    <div class="confirm-value-box {{ filled($faq['pdf_original_name']) ? '' : 'is-empty' }}">
                                                        {{ filled($faq['pdf_original_name']) ? $faq['pdf_original_name'] : '未選択' }}
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>

                                        {{-- 表示設定 --}}
                                        <div class="faq-form-section">
                                            <h3 class="faq-form-section-title">表示設定</h3>

                                            <div class="toggle-box">
                                                {{ !empty($faq['is_visible']) ? '表示する' : '非表示' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="confirm-note faq-confirm-note">
                            <p class="confirm-note-title">確認事項</p>
                            <ul class="form-guide-list">
                                <li>登録後の表示順は一覧画面で調整してください</li>
                                {{-- <li>PDFは確認画面表示時点で一時保存されています</li>
                                <li>入力画面に戻る場合、ブラウザの仕様によりPDF再選択が必要になることがあります</li> --}}
                            </ul>
                        </div>

                        <div class="form-actions">
                            <form action="{{ route('faqs.create.back') }}" method="post" class="form-action-form">
                                @csrf
                                @foreach ($faqs as $index => $faq)
                                    <input type="hidden" name="faqs[{{ $index }}][category1_id]" value="{{ $faq['category1_id'] }}">
                                    <input type="hidden" name="faqs[{{ $index }}][category2_id]" value="{{ $faq['category2_id'] ?? '' }}">
                                    <input type="hidden" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] }}">
                                    <input type="hidden" name="faqs[{{ $index }}][answer]" value="{{ $faq['answer'] }}">
                                    <input type="hidden" name="faqs[{{ $index }}][note]" value="{{ $faq['note'] ?? '' }}">
                                    <input type="hidden" name="faqs[{{ $index }}][url]" value="{{ $faq['url'] ?? '' }}">
                                    {{-- <input type="hidden" name="faqs[{{ $index }}][pdf_temp_path]" value="{{ $faq['pdf_temp_path'] ?? '' }}">
                                    <input type="hidden" name="faqs[{{ $index }}][pdf_original_name]" value="{{ $faq['pdf_original_name'] ?? '' }}"> --}}
                                    <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="{{ !empty($faq['is_visible']) ? 1 : 0 }}">
                                @endforeach

                                <button
                                    type="submit"
                                    class="header-sub-button form-back-button"
                                >
                                    入力画面へ戻る
                                </button>
                            </form>

                            <form action="{{ route('faqs.store') }}" method="post" class="form-action-form">
                                @csrf
                                @foreach ($faqs as $index => $faq)
                                    <input type="hidden" name="faqs[{{ $index }}][category1_id]" value="{{ $faq['category1_id'] }}">
                                    <input type="hidden" name="faqs[{{ $index }}][category2_id]" value="{{ $faq['category2_id'] ?? '' }}">
                                    <input type="hidden" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] }}">
                                    <input type="hidden" name="faqs[{{ $index }}][answer]" value="{{ $faq['answer'] }}">
                                    <input type="hidden" name="faqs[{{ $index }}][note]" value="{{ $faq['note'] ?? '' }}">
                                    <input type="hidden" name="faqs[{{ $index }}][url]" value="{{ $faq['url'] ?? '' }}">
                                    {{-- <input type="hidden" name="faqs[{{ $index }}][pdf_temp_path]" value="{{ $faq['pdf_temp_path'] ?? '' }}">
                                    <input type="hidden" name="faqs[{{ $index }}][pdf_original_name]" value="{{ $faq['pdf_original_name'] ?? '' }}"> --}}
                                    <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="{{ !empty($faq['is_visible']) ? 1 : 0 }}">
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
                            <h2 class="form-card-title">確認フォーム</h2>
                            <p class="form-card-sub">更新内容を確認してFAQを保存できます。</p>
                        </div>

                        <span class="confirm-status-badge">確認画面</span>
                    </div>

                    <form action="{{ route('faqs.update', $id) }}" method="post" class="form-body">
                        @csrf
                        @method('PUT')

                        <div class="faq-form-list">
                            <div class="faq-form-block faq-confirm-block">
                                <div class="faq-form-block-head">
                                    <div class="faq-form-block-title-wrap">
                                        <div class="faq-form-number">1</div>

                                        <div>
                                            <p class="faq-form-block-title">FAQ更新内容</p>
                                            <p class="faq-form-block-sub">更新前に入力内容を確認してください。</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="faq-form-sections">
                                    {{-- 基本情報 --}}
                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">基本情報</h3>

                                        <div class="faq-form-grid faq-form-grid-2">
                                            <div>
                                                <p class="faq-label">カテゴリ（メイン）</p>
                                                <div class="confirm-value-box">
                                                    {{ $requestData['category1_name'] ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="faq-label">カテゴリ（サブ・任意）</p>
                                                <div class="confirm-value-box {{ filled($requestData['category2_name']) ? '' : 'is-empty' }}">
                                                    {{ filled($requestData['category2_name']) ? $requestData['category2_name'] : '未選択' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FAQ内容 --}}
                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">FAQ内容</h3>

                                        <div class="faq-form-stack">
                                            <div>
                                                <p class="faq-label">質問</p>
                                                <div class="confirm-value-box confirm-value-box-multiline">
                                                    {{ trim($requestData['question']) }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="faq-label">回答</p>
                                                <div class="confirm-value-box confirm-value-box-multiline">
                                                    {{ trim($requestData['answer']) }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="faq-label">あわせて確認</p>
                                                <div class="confirm-value-box confirm-value-box-multiline {{ filled($requestData['note']) ? '' : 'is-empty' }}">
                                                    {{ filled($requestData['note']) ? trim($requestData['note']) : '未入力' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 参考資料 --}}
                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">参考資料</h3>

                                        <div class="faq-form-grid faq-form-grid-2">
                                            <div>
                                                <p class="faq-label">参考URL</p>
                                                <div class="confirm-value-box confirm-value-box-multiline {{ filled($requestData['url']) ? '' : 'is-empty' }}">
                                                    {{ filled($requestData['url']) ? $requestData['url'] : '未入力' }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="faq-label">PDFファイル</p>
                                                <div class="confirm-value-box {{ filled($requestData['pdf_original_name'] ?? null) ? '' : 'is-empty' }}">
                                                    {{ filled($requestData['pdf_original_name'] ?? null) ? $requestData['pdf_original_name'] : '未選択' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 表示設定 --}}
                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">表示設定</h3>

                                        <div class="toggle-box">
                                            {{ !empty($requestData['is_visible']) ? '表示する' : '非表示' }}
                                        </div>
                                    </div>

                                    {{-- 変更履歴 --}}
                                    <div class="faq-form-section">
                                        <h3 class="faq-form-section-title">変更履歴</h3>

                                        <div class="faq-form-stack faq-form-stack-tight">
                                            <div>
                                                <p class="faq-label">変更メモ</p>
                                                <div class="confirm-value-box confirm-value-box-multiline {{ filled($requestData['change_summary'] ?? '') ? '' : 'is-empty' }}">
                                                    {{ filled($requestData['change_summary'] ?? '') ? trim($requestData['change_summary']) : '未入力' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="toggle-box">
                                            {{ !empty($requestData['faq_history']) ? '変更前の情報を残す' : '変更前の情報は残さない' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- hidden --}}
                                <input type="hidden" name="category1_id" value="{{ $requestData['category1_id'] }}">
                                <input type="hidden" name="category2_id" value="{{ $requestData['category2_id'] ?? '' }}">
                                <input type="hidden" name="question" value="{{ $requestData['question'] }}">
                                <input type="hidden" name="answer" value="{{ $requestData['answer'] }}">
                                <input type="hidden" name="note" value="{{ $requestData['note'] ?? '' }}">
                                <input type="hidden" name="url" value="{{ $requestData['url'] ?? '' }}">
                                <input type="hidden" name="pdf_temp_path" value="{{ $requestData['pdf_temp_path'] ?? '' }}">
                                <input type="hidden" name="pdf_original_name" value="{{ $requestData['pdf_original_name'] ?? '' }}">
                                <input type="hidden" name="pdf_path" value="{{ $requestData['pdf'] ?? '' }}">
                                <input type="hidden" name="is_visible" value="{{ !empty($requestData['is_visible']) ? 1 : 0 }}">
                                <input type="hidden" name="faq_history" value="{{ !empty($requestData['faq_history']) ? 1 : 0 }}">
                                <input type="hidden" name="change_summary" value="{{ $requestData['change_summary'] ?? '' }}">
                            </div>
                        </div>

                        <div class="confirm-note faq-confirm-note">
                            <p class="confirm-note-title">確認事項</p>
                            <ul class="form-guide-list">
                                <li>更新後の表示内容はFAQ一覧画面で確認してください</li>
                                <li>PDFを差し替えた場合は、この確認画面表示時点で一時保存されています</li>
                                <li>履歴を残す場合は、変更前情報が faq_history に保存されます</li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <a
                                href="{{ route('faqs.edit', $id) }}"
                                class="header-sub-button form-back-button"
                            >
                                入力画面へ戻る
                            </a>

                            <button
                                type="submit"
                                class="header-main-button form-submit-button"
                            >
                                更新する
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