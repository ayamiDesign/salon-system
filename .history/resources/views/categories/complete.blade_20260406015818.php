<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ登録完了</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="category-layout-single">
        <section class="content">
            <section class="faq-card complete-card complete-card-compact">
                <div class="complete-card-inner">
                    <div class="complete-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="complete-icon"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <p class="complete-kicker">完了しました</p>

                    <h1 class="complete-title">
                        登録が完了しました
                    </h1>

                    <p class="complete-text">
                        カテゴリ情報の登録処理が正常に完了しました。<br>
                        登録した内容はカテゴリ一覧画面から確認できます。
                    </p>

                    <div class="complete-cta">
                        <a href="{{ route('categories.index') }}"
                        class="form-primary-button">
                            カテゴリ一覧へ
                        </a>
                    </div>
                </div>
            </section>
        </section>
    </main>
</div>
</body>
</html>