<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    @include('partials.header')

    <main class="layout dashboard-layout-simple">
        <section class="content">
            <div class="search-panel dashboard-simple-hero">
                <div class="search-copy">
                    <h1 class="search-heading">ダッシュボード</h1>
                    <p class="search-sub">
                        管理メニューの入口です。必要な項目はあとから追加できます。
                    </p>
                </div>
            </div>

            <div class="dashboard-simple-grid">
                <a href="{{ route('faqs.index') }}" class="dashboard-simple-card">
                    <span class="dashboard-simple-badge">FAQ</span>
                    <h2 class="dashboard-simple-title">FAQ一覧</h2>
                    <p class="dashboard-simple-text">
                        FAQの確認・編集・管理
                    </p>
                </a>

                <a href="{{ route('categories.index') }}" class="dashboard-simple-card">
                    <span class="dashboard-simple-badge">CAT</span>
                    <h2 class="dashboard-simple-title">カテゴリ管理</h2>
                    <p class="dashboard-simple-text">
                        カテゴリの追加・編集・並び替え
                    </p>
                </a>

                <a href="{{ route('users.index') }}" class="dashboard-simple-card">
                    <span class="dashboard-simple-badge">USER</span>
                    <h2 class="dashboard-simple-title">アカウント管理</h2>
                    <p class="dashboard-simple-text">
                        管理ユーザーの確認・登録
                    </p>
                </a>
            </div>

            <div class="dashboard-simple-footer-card">
                <div>
                    <p class="dashboard-simple-footer-label">ログイン中</p>
                    <p class="dashboard-simple-footer-name">
                        {{ Auth::user()->name ?? 'ユーザー' }}
                    </p>
                </div>

                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="header-sub-button dashboard-simple-logout">
                        ログアウト
                    </button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>