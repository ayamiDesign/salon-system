{{-- resources/views/faqs/index.blade.php --}}
@php
    $keyword = request('keyword', '');
    $selectedCategory = request('category', 'すべて');

    $filteredFaqs = [];

    foreach ($faqs as $faq) {
        $faqCategories = array_filter([
            trim($faq->category1_name ?? ''),
            trim($faq->category2_name ?? ''),
        ]);

        $categoryMatched = $selectedCategory === 'すべて'
            || in_array($selectedCategory, $faqCategories, true);

        if (!$categoryMatched) {
            continue;
        }

        if ($keyword !== '') {
            $target = implode(' ', array_filter([
                $faq->category1_name ?? '',
                $faq->category2_name ?? '',
                $faq->question ?? '',
                $faq->answer ?? '',
                $faq->note ?? '',
                $faq->url ?? '',
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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
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

             <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    id="cancelSortButton"
                    class="hidden inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    キャンセル
                </button>

                <button
                    type="button"
                    id="saveSortButton"
                    class="hidden inline-flex items-center justify-center rounded-lg bg-accent-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-700"
                >
                    保存する
                </button>

                <button
                    type="button"
                    id="sortModeButton"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    並び替え
                </button>

                <a
                    href="{{ route('faqs.create') }}"
                    class="action-button inline-flex items-center justify-center rounded-lg bg-accent-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-700"
                >
                    新規登録
                </a>
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
            <div class="search-panel">
                <h1 class="search-heading">知りたいことをすぐ検索</h1>
                <p class="search-sub">検索を最優先にした、スマホでも使いやすいFAQ一覧です。</p>

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
                            @foreach($categoriesList as $category)
                                <option value="{{ $category['name'] }}" {{ $selectedCategory === $category['name'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}（{{ $category['count'] }}件）
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="sub-button">カテゴリ適用</button>
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

            <div class="stats-bar">
                <div class="stats-item">
                    <span class="stats-label">総FAQ件数</span>
                    <span class="stats-inline-value">{{ count($faqs) }}</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">検索結果</span>
                    <span class="stats-inline-value">{{ count($filteredFaqs) }}</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">選択カテゴリ</span>
                    <span class="stats-inline-value">{{ $selectedCategory }}</span>
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

                                    @if(!empty($faq->url))
                                        <div class="link-block">
                                            <p class="block-title">参考リンク</p>
                                            <ul class="link-list">
                                                <li>
                                                    <a href="{{ $faq->url }}" target="_blank" rel="noopener noreferrer">{{ $faq->url }}</a>
                                                </li>
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