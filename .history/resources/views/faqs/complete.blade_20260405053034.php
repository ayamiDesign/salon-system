<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ登録完了</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">FAQ登録完了</p>
                    <p class="brand-sub">一覧・登録・編集・確認と同じデザインルールで統一した完了画面</p>
                </div>
            </div>

            <div class="topbar-actions topbar-actions-sort">
                <a
                    href="{{ route('categories.index') }}"
                    class="header-sub-button"
                >
                    一覧へ戻る
                </a>
            </div>
        </div>
    </header>
    <main class="category-layout-single">
        <section class="content">
            <section class="faq-card complete-card">
                <div class="complete-card-inner">
                    <div class="complete-icon-wrap" aria-hidden="true">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="complete-icon"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h1 class="complete-title">登録が完了しました</h1>

                    <p class="complete-text">
                        FAQの登録処理が正常に完了しました。<br>
                        登録した内容はFAQ一覧画面から確認できます。
                    </p>

                    <div class="complete-status-box">
                        <div class="complete-status-row">
                            <span class="complete-status-label">処理状態</span>
                            <span class="complete-status-value">正常に完了</span>
                        </div>
                    </div>

                    <div class="form-actions complete-actions">
                        <a
                            href="{{ route('faqs.index') }}"
                            class="form-primary-button form-submit-button"
                        >
                            FAQ一覧へ
                        </a>
                    </div>
                </div>
            </section>

        </section>
    </main>
</div>
</body>
</html>