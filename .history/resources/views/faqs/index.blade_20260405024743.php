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
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            --shadow-soft: 0 10px 24px rgba(15, 23, 42, 0.04);
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        .action-button,
        .submit-button,
        .row-action-button,
        .clear-button,
        .chip,
        .category-button,
        .faq-toggle,
        .mobile-category-select,
        .search-box input[type="text"] {
            transition: .2s ease;
        }

        .sidebar,
        .hero,
        .faq-card,
        .stats-card {
            background: var(--card);
            border: 1px solid rgba(229, 231, 235, 0.92);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }

        .hero {
            padding: 28px;
            margin-bottom: 20px;
        }

        .search-box input[type="text"] {
            width: 100%;
            height: 54px;
            border: 1px solid var(--line);
            border-radius: 15px;
            padding: 0 50px 0 18px;
            font-size: 15px;
            outline: none;
            background: #fff;
        }

        .search-box input[type="text"]:focus {
            border-color: #c7d7f7;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }

        .submit-button {
            height: 54px;
            padding: 0 20px;
            border: 1px solid #1f2937;
            border-radius: 15px;
            background: #1f2937;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: .01em;
            box-shadow: none;
        }

        .submit-button:hover {
            background: #111827;
            border-color: #111827;
            transform: none;
            box-shadow: none;
        }

        .mobile-category-select {
            width: 100%;
            height: 48px;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 0 14px;
            background: #fff;
            font-size: 14px;
            color: var(--text);
        }

        .mobile-category-select:focus {
            outline: none;
            border-color: #c7d7f7;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .chip {
            border: 1px solid #dbe7fb;
            background: rgba(255,255,255,.95);
            color: var(--accent);
            padding: 9px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: none;
        }

        .chip:hover {
            background: #f8fbff;
            border-color: #bfd7ff;
            transform: none;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .clear-button {
            min-height: 34px;
            padding: 0 14px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background: #fff;
            color: #667085;
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
            box-shadow: none;
        }

        .clear-button:hover {
            background: #f8fafc;
            border-color: #d0d7e2;
            color: var(--text);
        }

        .category-button {
            width: 100%;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 14px;
            padding: 13px 14px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            color: var(--text);
            box-shadow: none;
        }

        .category-button:hover,
        .category-button.is-active {
            border-color: #d7e4fb;
            background: #f8fbff;
            box-shadow: none;
        }

        .action-button {
            height: 42px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
            box-shadow: none;
        }

        .action-button:hover {
            border-color: #d7e4fb;
            background: #f8fbff;
        }

        .action-button.primary {
            border-color: #2563eb;
            background: #2563eb;
            color: #fff;
            box-shadow: none;
        }

        .action-button.primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            transform: none;
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
            box-shadow: none;
        }

        .row-action-button:hover {
            border-color: #d7e4fb;
            background: #f8fbff;
        }

        .row-action-button.delete {
            color: #b91c1c;
            border-color: #fecaca;
            background: #fff;
        }

        .row-action-button.delete:hover {
            background: #fff5f5;
            border-color: #f8b4b4;
        }

        .faq-toggle {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sub);
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
            box-shadow: none;
        }

        .faq-toggle::before {
            content: "+";
        }

        .faq-details[open] .faq-toggle {
            background: #f8fbff;
            color: var(--accent);
            border-color: #d7e4fb;
        }

        .faq-details[open] .faq-toggle::before {
            content: "−";
        }

        .faq-card {
            overflow: hidden;
            box-shadow: var(--shadow-soft);
        }

        .faq-body {
            display: none;
            border-top: 1px solid var(--line-soft);
            padding: 20px;
            background: #fcfdff;
        }

        .faq-details[open] .faq-body {
            display: block;
        }

        @media (max-width: 640px) {
            .hero {
                padding: 18px;
                border-radius: 18px;
            }

            .search-row {
                grid-template-columns: 1fr;
                gap: 10px;
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
                padding: 8px 13px;
                font-size: 13px;
            }

            .hero-actions {
                margin-top: 12px;
            }

            .clear-button {
                width: 100%;
                min-height: 42px;
                padding: 0 16px;
                border-radius: 12px;
                justify-content: center;
            }

            .mobile-category-select {
                height: 50px;
                border-radius: 14px;
            }

            .row-action-button {
                height: 36px;
            }

            .faq-toggle {
                width: 36px;
                height: 36px;
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
                @foreach($categoriesList as $category)
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