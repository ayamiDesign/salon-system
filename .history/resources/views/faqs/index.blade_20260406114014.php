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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div
    class="app"
    data-sort-container
    data-sort-endpoint="{{ url('/faqs/order') }}"
    data-delete-base-url="{{ url('/faqs') }}"
>
    @include('partials.header')

    <main class="layout">
        <aside class="sidebar">
            <div class="sidebar-card">
                <h2 class="sidebar-title">カテゴリ</h2>

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
            </div>
        </aside>

        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">知りたいことをすぐ検索</h1>
                    <p class="search-sub">検索しやすさを最優先にした、スマホでも使いやすいFAQ一覧です。</p>
                </div>

                <form method="GET" action="{{ route('faqs.search') }}" class="search-form">
                    <div class="search-box">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            placeholder="例：施術順 / 予約 / ブロック / サロンボード"
                        >
                        <span class="search-icon">⌕</span>
                    </div>

                    <div class="search-inline-actions">
                        <select class="mobile-category-select" name="category" aria-label="カテゴリ選択">
                            <option value="0" {{ ($selectedCategory ?? '') == '0' ? 'selected' : '' }}>すべて</option>
                            @foreach($categoriesList as $category)
                                <option value="{{ $category['id'] }}" {{ $selectedCategory === $category['id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}（{{ $category['count'] }}件）
                                </option>
                            @endforeach
                        </select>

                        <input type="submit" value="検索する" class="submit-button">
                    </div>
                </form>

                <div class="chips-label">よく使うキーワード</div>
                <div class="chips">
                    <a href="{{ build_query(['keyword' => '施術順']) }}" class="chip {{ $keyword === '施術順' ? 'is-active' : '' }}">施術順</a>
                    <a href="{{ build_query(['keyword' => '予約']) }}" class="chip {{ $keyword === '予約' ? 'is-active' : '' }}">予約</a>
                    <a href="{{ build_query(['keyword' => 'ブロック']) }}" class="chip {{ $keyword === 'ブロック' ? 'is-active' : '' }}">ブロック</a>
                    <a href="{{ build_query(['keyword' => 'サロンボード']) }}" class="chip {{ $keyword === 'サロンボード' ? 'is-active' : '' }}">サロンボード</a>
                </div>

                <div class="hero-actions">
                    <a href="{{ request()->url() }}" class="clear-button">条件をクリア</a>
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
                    <span class="stats-label">カテゴリ</span>
                    <span class="stats-inline-value">{{ $selectedCategory }}</span>
                </div>
            </div>

            <div id="sortGuide" class="sort-guide hidden">
                ドラッグして表示順を変更できます。並び替え後に「保存する」を押してください。
            </div>

            @if(count($filteredFaqs) > 0)
                {{-- PC / 共通DOM --}}
                <div class="faq-list">
                    <div id="pc-sortable-body">
                        @foreach($filteredFaqs as $faq)
                            <article
                                class="faq-card js-sort-row"
                                data-id="{{ $faq->id }}"
                                data-order="{{ $loop->iteration }}"
                            >
                                <details class="faq-details">
                                    <summary class="faq-head">
                                        <div class="faq-head-main">
                                            <div class="faq-meta">
                                                <span class="sort-order-badge js-sort-order-label">{{ $loop->iteration }}</span>

                                                <span class="tag">{{ $faq->category1_name }}</span>
                                                @if($faq->category2_name)
                                                    <span class="tag">{{ $faq->category2_name }}</span>
                                                @endif
                                            </div>

                                            <h2 class="faq-question">
                                                {!! highlight_text($faq->question, $keyword) !!}
                                            </h2>

                                            <div class="faq-updated">{{ $faq->updated_at }}</div>
                                        </div>

                                        <div class="faq-head-side">
                                            <div class="js-normal-actions faq-action-group">
                                                <a href="{{ route('faqs.edit', $faq->id) }}" class="row-action-button">変更</a>

                                                <button
                                                    type="button"
                                                    class="row-action-button delete js-delete-open"
                                                    data-id="{{ $faq->id }}"
                                                    data-name="{{ $faq->question }}"
                                                >
                                                    削除
                                                </button>
                                                <a href="{{ route('faqs.histories.index', $faq->id) }}" class="row-action-button">履歴</a>
                                            </div>

                                            <button
                                                type="button"
                                                class="sort-handle-button js-sort-handle hidden"
                                                aria-label="並び替え"
                                            >
                                                ≡
                                            </button>

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
                </div>
            @else
                <div class="empty">
                    該当するFAQが見つかりませんでした。<br>
                    キーワードやカテゴリを変更してお試しください。
                </div>
            @endif
        </section>
    </main>

    {{-- 削除モーダル --}}
    <div id="deleteModal" class="delete-modal hidden">
        <div class="delete-modal-backdrop"></div>
        <div class="delete-modal-card">
            <p class="delete-modal-title">FAQを削除しますか？</p>
            <p class="delete-modal-text">
                「<span id="deleteItemName"></span>」を削除します。<br>
                この操作は取り消せません。
            </p>

            <div class="delete-modal-actions">
                <button type="button" id="deleteModalClose" class="modal-cancel-button">キャンセル</button>

                <form id="deleteForm" method="POST" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-delete-button">削除する</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>