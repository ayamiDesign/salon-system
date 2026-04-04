{{-- resources/views/faq-demo.blade.php --}}
@php
    $keyword = request('keyword', '');
    $selectedCategory = request('category', 'すべて');

    $counts = [];

    foreach ($faqs as $faq) {
        foreach (explode('/', $faq->category) as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $counts[$name] = ($counts[$name] ?? 0) + 1;
        }
    }

    ksort($counts);

    $categories = [];
    foreach ($counts as $name => $count) {
        $categories[] = [
            'name' => $name,
            'count' => $count,
        ];
    }

    $filteredFaqs = [];

    foreach ($faqs as $faq) {
        $categoryMatched = $selectedCategory === 'すべて';

        if (!$categoryMatched) {
            $faqCategories = array_map('trim', explode('/', $faq->category));
            $categoryMatched = in_array($selectedCategory, $faqCategories, true);
        }

        if (!$categoryMatched) {
            continue;
        }

        if ($keyword !== '') {
            $target = implode(' ', array_filter([
                $faq->category ?? '',
                $faq->question ?? '',
                $faq->summary ?? '',
                $faq->answer ?? '',
                $faq->note ?? '',
                ...($faq->links ?? []),
            ]));

            if (mb_stripos($target, $keyword) === false) {
                continue;
            }
        }

        $filteredFaqs[] = $faq;
    }

    function h($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    function highlight_text($value, $keyword) {
        $text = nl2br(h($value ?? ''));

        if ($keyword === '') {
            return $text;
        }

        $pattern = '/' . preg_quote($keyword, '/') . '/iu';
        return preg_replace($pattern, '<mark>$0</mark>', $text);
    }

    function build_query(array $overrides = []) {
        $query = array_merge(request()->query(), $overrides);

        foreach ($query as $key => $value) {
            if ($value === null || $value === '') {
                unset($query[$key]);
            }
        }

        return request()->url() . (count($query) ? '?' . http_build_query($query) : '');
    }
@endphp

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ検索デモ</title>
    <style>
        :root {
            --bg: #f6f7fb;
            --bg-grad-1: #f8fafc;
            --bg-grad-2: #eef4ff;
            --card: rgba(255, 255, 255, 0.92);
            --line: #e5e7eb;
            --line-soft: #edf2f7;
            --text: #1f2937;
            --sub: #6b7280;
            --accent: #2563eb;
            --accent-soft: #eff6ff;
            --accent-soft-2: #dbeafe;
            --accent-deep: #1d4ed8;
            --success: #16a34a;
            --warn: #f59e0b;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --shadow-soft: 0 16px 40px rgba(37, 99, 235, 0.06);
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Hiragino Sans", "Hiragino Kaku Gothic ProN", "Yu Gothic", sans-serif;
            background:
                radial-gradient(circle at top left, var(--bg-grad-2), transparent 35%),
                linear-gradient(180deg, var(--bg-grad-1) 0%, var(--bg) 100%);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        .app {
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(246, 247, 251, 0.82);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.9);
        }

        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.22);
            flex-shrink: 0;
        }

        .brand-text {
            min-width: 0;
        }

        .brand-title {
            font-size: 17px;
            font-weight: 800;
            margin: 0;
            letter-spacing: .01em;
        }

        .brand-sub {
            font-size: 12px;
            color: var(--sub);
            margin: 2px 0 0;
        }

        .layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 20px 40px;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 24px;
        }

        .sidebar,
        .hero,
        .faq-card,
        .stats-card {
            background: var(--card);
            border: 1px solid rgba(229, 231, 235, 0.9);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }

        .sidebar {
            position: sticky;
            top: 86px;
            height: fit-content;
            padding: 18px;
        }

        .sidebar h2 {
            margin: 0 0 14px;
            font-size: 16px;
            letter-spacing: .02em;
        }

        .category-list {
            display: grid;
            gap: 10px;
        }

        .category-button {
            width: 100%;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 14px;
            padding: 13px 14px;
            text-align: left;
            transition: .2s ease;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            color: var(--text);
        }

        .category-button:hover,
        .category-button.is-active {
            border-color: #bfdbfe;
            background: linear-gradient(180deg, #f8fbff 0%, #eff6ff 100%);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.8);
        }

        .category-name {
            font-weight: 700;
            font-size: 14px;
        }

        .category-count {
            font-size: 12px;
            color: var(--sub);
            flex-shrink: 0;
        }

        .content {
            min-width: 0;
        }

        .hero {
            padding: 28px;
            margin-bottom: 20px;
        }

        .hero h1 {
            margin: 0 0 10px;
            font-size: 28px;
            line-height: 1.25;
            letter-spacing: .01em;
        }

        .hero p {
            margin: 0;
            color: var(--sub);
            font-size: 15px;
        }

        .search-form {
            margin: 20px 0 0;
        }

        .search-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: stretch;
        }

        .search-box {
            position: relative;
        }

        .search-box input[type="text"] {
            width: 100%;
            height: 56px;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 0 50px 0 18px;
            font-size: 15px;
            outline: none;
            background: #fff;
            transition: .2s ease;
        }

        .search-box input[type="text"]:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--sub);
            font-size: 18px;
            pointer-events: none;
        }

        .action-button,
        .submit-button,
        .row-action-button,
        .clear-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .submit-button {
            height: 56px;
            padding: 0 22px;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(180deg, #253149 0%, #1f2937 100%);
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: .02em;
            box-shadow: 0 10px 24px rgba(31, 41, 55, 0.18);
        }

        .submit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(31, 41, 55, 0.22);
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .chip {
            border: 1px solid #dbeafe;
            background: rgba(255,255,255,.95);
            color: var(--accent);
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            transition: .2s ease;
        }

        .chip:hover {
            background: var(--accent-soft);
            border-color: #bfdbfe;
            transform: translateY(-1px);
        }

        .hero-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .clear-button {
            min-height: 36px;
            padding: 0 14px;
            border: 1px solid #dbeafe;
            border-radius: 999px;
            background: rgba(255,255,255,.96);
            color: #5b6475;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .clear-button:hover {
            background: #f8fbff;
            border-color: #bfd7ff;
            color: var(--text);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .stats-card {
            padding: 18px 20px;
        }

        .stats-label {
            font-size: 12px;
            color: var(--sub);
            margin-bottom: 8px;
        }

        .stats-value {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            letter-spacing: .01em;
        }

        .faq-list {
            display: grid;
            gap: 14px;
        }

        .faq-card {
            overflow: hidden;
            box-shadow: var(--shadow-soft);
        }

        .faq-details {
            display: block;
        }

        .faq-details summary {
            list-style: none;
            cursor: pointer;
        }

        .faq-details summary::-webkit-details-marker {
            display: none;
        }

        .faq-head {
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 14px;
        }

        .faq-head-main {
            flex: 1 1 auto;
            min-width: 0;
        }

        .faq-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
        }

        .faq-question {
            margin: 0;
            font-size: 18px;
            line-height: 1.5;
            letter-spacing: .01em;
        }

        .faq-updated {
            margin-top: 8px;
            font-size: 13px;
            color: var(--sub);
        }

        .faq-head-side {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .faq-toggle {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, #f8fafc 0%, #f3f4f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sub);
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
        }

        .faq-toggle::before {
            content: "+";
        }

        .faq-details[open] .faq-toggle {
            background: linear-gradient(180deg, #f8fbff 0%, #eff6ff 100%);
            color: var(--accent);
            border-color: #bfdbfe;
        }

        .faq-details[open] .faq-toggle::before {
            content: "−";
        }

        .faq-body {
            display: none;
            border-top: 1px solid var(--line-soft);
            padding: 20px;
            background: linear-gradient(180deg, rgba(252,252,253,1) 0%, rgba(248,250,252,1) 100%);
        }

        .faq-details[open] .faq-body {
            display: block;
        }

        .answer-block,
        .note-block,
        .link-block {
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 16px;
            background: #fff;
        }

        .answer-block {
            border-left: 5px solid var(--success);
        }

        .note-block {
            margin-top: 12px;
            border-left: 5px solid var(--warn);
            background: #fffdf7;
        }

        .link-block {
            margin-top: 12px;
        }

        .block-title {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 800;
        }

        .block-text {
            margin: 0;
            font-size: 14px;
            white-space: normal;
        }

        .link-list {
            margin: 0;
            padding-left: 18px;
        }

        .link-list li + li {
            margin-top: 6px;
        }

        mark {
            background: #fef08a;
            color: inherit;
            padding: 0 .15em;
            border-radius: 4px;
        }

        .empty {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 40px 20px;
            text-align: center;
            color: var(--sub);
        }

        .mobile-category-wrap {
            display: none;
            margin-top: 16px;
        }

        .mobile-category-select {
            width: 100%;
            height: 48px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0 14px;
            background: #fff;
            font-size: 14px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .action-button {
            height: 42px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            color: var(--text);
            font-size: 13px;
            font-weight: 800;
        }

        .action-button:hover {
            border-color: #bfdbfe;
            background: var(--accent-soft);
        }

        .action-button.primary {
            border-color: var(--accent);
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: #fff;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);
        }

        .action-button.primary:hover {
            transform: translateY(-1px);
        }

        .row-action-button {
            height: 34px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--text);
            font-size: 12px;
            font-weight: 700;
        }

        .row-action-button:hover {
            border-color: #bfdbfe;
            background: var(--accent-soft);
        }

        .row-action-button.delete {
            color: #b91c1c;
            border-color: #fecaca;
            background: #fff;
        }

        .row-action-button.delete:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .inline-form {
            margin: 0;
        }

        .mobile-category-form {
            margin: 0;
        }

        @media (max-width: 960px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .mobile-category-wrap {
                display: block;
            }

            .hero {
                padding: 22px;
            }
        }

        @media (max-width: 640px) {
            .topbar-inner,
            .layout {
                padding-left: 14px;
                padding-right: 14px;
            }

            .topbar-inner {
                align-items: flex-start;
            }

            .brand-title {
                font-size: 16px;
            }

            .brand-sub {
                font-size: 11px;
            }

            .hero {
                padding: 18px;
                border-radius: 18px;
            }

            .hero h1 {
                font-size: 24px;
                line-height: 1.35;
            }

            .hero p {
                font-size: 14px;
            }

            .search-row {
                grid-template-columns: 1fr;
            }

            .search-box input[type="text"],
            .submit-button {
                height: 50px;
            }

            .submit-button {
                width: 100%;
                border-radius: 14px;
            }

            .chips {
                gap: 8px;
            }

            .chip {
                padding: 9px 14px;
                font-size: 13px;
            }

            .hero-actions {
                margin-top: 12px;
            }

            .clear-button {
                width: 100%;
                min-height: 42px;
                padding: 0 16px;
                font-size: 13px;
                border-radius: 12px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .stats-card {
                padding: 16px 18px;
            }

            .faq-head,
            .faq-body {
                padding: 16px;
            }

            .faq-head {
                flex-direction: column;
            }

            .faq-head-side {
                width: 100%;
                justify-content: flex-end;
            }

            .faq-question {
                font-size: 16px;
            }

            .row-action-button {
                height: 36px;
            }

            .faq-toggle {
                width: 38px;
                height: 38px;
            }

            .action-button.primary {
                min-height: 40px;
            }
        }
    </style>
</head>
<body>
<div class="app">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">社内FAQ検索デモ</p>
                    <p class="brand-sub">PC / スマホ対応 Laravel Blade サンプル</p>
                </div>
            </div>

            <div class="topbar-actions">
                <a href="{{ route('faqs.create') }}" class="action-button primary">新規登録</a>
            </div>
        </div>
    </header>

    <main class="layout">
        <aside class="sidebar">
            <h2>カテゴリ</h2>
            <div class="category-list">
                <a
                    href="{{ build_query(['category' => 'すべて']) }}"
                    class="category-button {{ $selectedCategory === 'すべて' ? 'is-active' : '' }}"
                >
                    <span class="category-name">すべて</span>
                    <span class="category-count">{{ count($faqs) }}件</span>
                </a>
                @php
                    dd($categoriesList);
                @endphp
                @foreach($categories as $category)
                    <a
                        href="{{ build_query(['category' => $category['name']]) }}"
                        class="category-button {{ $selectedCategory === $category['name'] ? 'is-active' : '' }}"
                    >
                        <span class="category-name">{{ $category['name'] }}</span>
                        <span class="category-count">{{ $category['count'] }}件</span>
                    </a>
                @endforeach
            </div>
        </aside>

        <section class="content">
            <div class="hero">
                <h1>知りたいことをすぐ検索</h1>
                <p>スプレッドシートのFAQを、スマホでも見やすい検索UIにしたデモです。カテゴリ絞り込み、キーワード検索、アコーディオン表示に対応しています。</p>

                <form method="GET" action="" class="search-form">
                    <div class="search-row">
                        <div class="search-box">
                            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="例：施術順 / 予約 / ブロック / サロンボード">
                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                            <span class="search-icon">⌕</span>
                        </div>
                        <input type="submit" value="検索" class="submit-button">
                    </div>
                </form>

                <div class="mobile-category-wrap">
                    <form method="GET" action="" class="mobile-category-form">
                        <input type="hidden" name="keyword" value="{{ $keyword }}">
                        <select class="mobile-category-select" name="category">
                            <option value="すべて" {{ $selectedCategory === 'すべて' ? 'selected' : '' }}>すべて</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['name'] }}" {{ $selectedCategory === $category['name'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}（{{ $category['count'] }}件）
                                </option>
                            @endforeach
                        </select>
                        <div style="margin-top:10px;">
                            <input type="submit" value="カテゴリ適用" class="submit-button" style="width:100%;">
                        </div>
                    </form>
                </div>

                <div class="chips">
                    <a href="{{ build_query(['keyword' => '施術順']) }}" class="chip">施術順</a>
                    <a href="{{ build_query(['keyword' => '予約']) }}" class="chip">予約</a>
                    <a href="{{ build_query(['keyword' => 'ブロック']) }}" class="chip">ブロック</a>
                    <a href="{{ build_query(['keyword' => 'サロンボード']) }}" class="chip">サロンボード</a>
                </div>

                <div class="hero-actions">
                    <a href="{{ request()->url() }}" class="clear-button">条件クリア</a>
                </div>
            </div>

            <div class="stats">
                <div class="stats-card">
                    <div class="stats-label">総FAQ件数</div>
                    <p class="stats-value">{{ count($faqs) }}</p>
                </div>
                <div class="stats-card">
                    <div class="stats-label">検索結果</div>
                    <p class="stats-value">{{ count($filteredFaqs) }}</p>
                </div>
                <div class="stats-card">
                    <div class="stats-label">選択カテゴリ</div>
                    <p class="stats-value" style="font-size: 18px;">{{ $selectedCategory }}</p>
                </div>
            </div>

            @if(count($filteredFaqs) > 0)
                <div class="faq-list">
                    @foreach($filteredFaqs as $faq)
                        <article class="faq-card">
                            <details class="faq-details" {{ $loop->first ? 'open' : '' }}>
                                <summary class="faq-head">
                                    <div class="faq-head-main">
                                        <div class="faq-meta">
                                            <span class="tag">📁 {{ $faq->category1_name }}</span>
                                            @if($faq->category2_name)
                                                <span class="tag">📁 {{ $faq->category2_name }}</span>
                                            @endif
                                        </div>

                                        <h2 class="faq-question">
                                            {!! highlight_text($faq->question, $keyword) !!}
                                        </h2>

                                        <div class="faq-updated">{{ $faq->updated_at }}</div>
                                    </div>

                                    <div class="faq-head-side">
                                        <a href="{{ route('faqs.edit', $faq->id) }}" class="row-action-button">変更</a>

                                        <form method="POST" action="{{ route('faqs.destroy', $faq->id) }}" class="inline-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="削除" class="row-action-button delete">
                                        </form>

                                        <a href="{{ url('/faqs/' . $faq->id . '/history') }}" class="row-action-button">履歴</a>

                                        <span class="faq-toggle" aria-hidden="true"></span>
                                    </div>
                                </summary>

                                <div class="faq-body">
                                    <div class="answer-block">
                                        <p class="block-title">回答</p>
                                        <p class="block-text">{!! highlight_text($faq->answer, $keyword) !!}</p>
                                    </div>

                                    @if(!empty($faq->note))
                                        <div class="note-block">
                                            <p class="block-title">あわせて確認</p>
                                            <p class="block-text">{!! highlight_text($faq->note, $keyword) !!}</p>
                                        </div>
                                    @endif

                                    @if(!empty($faq->links) && count($faq->links))
                                        <div class="link-block">
                                            <p class="block-title">参考リンク</p>
                                            <ul class="link-list">
                                                @foreach($faq->links as $link)
                                                    <li>
                                                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $link }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty">
                    該当するFAQが見つかりませんでした。<br>
                    キーワードやカテゴリを変更してお試しください。
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>