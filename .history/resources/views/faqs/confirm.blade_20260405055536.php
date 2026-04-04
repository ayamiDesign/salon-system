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
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">FAQ登録確認</p>
                    <p class="brand-sub">FAQ一覧・登録画面と同じデザインルールで統一した確認画面</p>
                </div>
            </div>

            <div class="topbar-actions topbar-actions-sort">
                <a
                    href="{{ route('faqs.create') }}"
                    class="header-sub-button"
                >
                    入力画面へ戻る
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
                        登録内容に問題がなければ、そのまま登録してください。必要があれば入力画面へ戻れます。
                    </p>
                </div>
            </div>
            <section class="faq-card form-card">
                <div class="form-card-header">
                    <div>
                        <h2 class="form-card-title">確認フォーム</h2>
                        <p class="form-card-sub">FAQごとの内容を確認できます。</p>
                    </div>

                    <span class="confirm-status-badge">確認画面</span>
                </div>

                <form action="{{ route('faqs.store') }}" method="post" class="form-body">
                    @csrf

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

                                        <div class="faq-form-grid faq-form-grid-2">
                                            <div>
                                                <p class="faq-label">参考URL</p>
                                                <div class="confirm-value-box confirm-value-box-multiline {{ filled($faq['url']) ? '' : 'is-empty' }}">
                                                    {{ filled($faq['url']) ? $faq['url'] : '未入力' }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="faq-label">PDFファイル</p>
                                                <div class="confirm-value-box {{ filled($faq['pdf_original_name']) ? '' : 'is-empty' }}">
                                                    {{ filled($faq['pdf_original_name']) ? $faq['pdf_original_name'] : '未選択' }}
                                                </div>
                                            </div>
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

                                {{-- hidden --}}
                                <input type="hidden" name="faqs[{{ $index }}][category1_id]" value="{{ $faq['category1_id'] }}">
                                <input type="hidden" name="faqs[{{ $index }}][category2_id]" value="{{ $faq['category2_id'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] }}">
                                <input type="hidden" name="faqs[{{ $index }}][answer]" value="{{ $faq['answer'] }}">
                                <input type="hidden" name="faqs[{{ $index }}][note]" value="{{ $faq['note'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][url]" value="{{ $faq['url'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][pdf_temp_path]" value="{{ $faq['pdf_temp_path'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][pdf_original_name]" value="{{ $faq['pdf_original_name'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="{{ !empty($faq['is_visible']) ? 1 : 0 }}">
                            </div>
                        @endforeach
                    </div>

                    <div class="confirm-note faq-confirm-note">
                        <p class="confirm-note-title">確認事項</p>
                        <ul class="form-guide-list">
                            <li>登録後の表示順は一覧画面で調整してください</li>
                            <li>PDFは確認画面表示時点で一時保存されています</li>
                            <li>入力画面に戻る場合、ブラウザの仕様によりPDF再選択が必要になることがあります</li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <a
                            href="{{ route('faqs.create') }}"
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
        </section>
    </main>
</div>
</body>
</html>