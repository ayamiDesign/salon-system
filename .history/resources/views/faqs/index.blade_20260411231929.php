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
                        href="{{ route('faqs.index', ['category' => 0, 'keyword' => request('keyword', '')]) }}"
                        class="category-button {{ ($searchCategory ?? '0') == '0' ? 'is-active' : '' }}"
                    >
                        <span class="category-name">すべて</span>
                        <span class="category-count">{{ count($faqs) }}件</span>
                    </a>

                    @foreach($categoriesList as $category)
                        <a
                            href="{{ route('faqs.index', ['category' => $category['id'], 'keyword' => request('keyword', '')]) }}"
                            class="category-button {{ ($searchCategory ?? '0') == $category['id'] ? 'is-active' : '' }}"
                        >
                            <span class="category-name">{{ $category['name'] }}</span>
                            <span class="category-count">{{ $category['count'] }}件</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <section class="content">
             @if ($errors->any())
                <div class="form-alert">
                    <p class="form-alert-title">CSVデータの内容を確認してください</p>
                    <ul class="form-alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">知りたいことをすぐ検索</h1>
                    <p class="search-sub">検索しやすさを最優先にした、スマホでも使いやすいFAQ一覧です。</p>
                </div>

                <form method="GET" action="{{ route('faqs.index') }}" class="search-form">
                    <div class="search-box">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $searchKeyword ?? '' }}"
                            placeholder="例：施術順 / 予約 / ブロック / サロンボード"
                        >
                        <span class="search-icon">⌕</span>
                    </div>

                    <div class="search-inline-actions">
                        <select class="mobile-category-select" name="category" aria-label="カテゴリ選択">
                            <option value="0" {{ ($searchCategory ?? '') == '0' ? 'selected' : '' }}>すべて</option>
                            @foreach($categoriesList as $category)
                                <option value="{{ $category['id'] }}" {{ ($searchCategory ?? '') == $category['id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}（{{ $category['count'] }}件）
                                </option>
                            @endforeach
                        </select>
                        <input type="submit" value="検索する" class="submit-button">
                    </div>
                </form>

                <div class="chips-label">よく使うキーワード</div>
                <div class="chips">
                    <a
                        href="{{ route('faqs.index', ['keyword' => '施術順', 'category' => request('category', 0)]) }}"
                        class="chip {{ $searchKeyword === '施術順' ? 'is-active' : '' }}"
                    >
                        施術順
                    </a>

                    <a
                        href="{{ route('faqs.index', ['keyword' => '予約', 'category' => request('category', 0)]) }}"
                        class="chip {{ $searchKeyword === '予約' ? 'is-active' : '' }}"
                    >
                        予約
                    </a>

                    <a
                        href="{{ route('faqs.index', ['keyword' => 'ブロック', 'category' => request('category', 0)]) }}"
                        class="chip {{ $searchKeyword === 'ブロック' ? 'is-active' : '' }}"
                    >
                        ブロック
                    </a>

                    <a
                        href="{{ route('faqs.index', ['keyword' => 'サロンボード', 'category' => request('category', 0)]) }}"
                        class="chip {{ $searchKeyword === 'サロンボード' ? 'is-active' : '' }}"
                    >
                        サロンボード
                    </a>
                </div>

                <div class="hero-actions">
                    <a href="{{ route('faqs.index') }}" class="clear-button">条件をクリア</a>
                </div>
            </div>

            <div id="sortGuide" class="sort-guide hidden">
                ドラッグして表示順を変更できます。並び替え後に「保存する」を押してください。
            </div>

            @if(count($faqs) > 0)
                {{-- PC / 共通DOM --}}
                <div class="faq-list">
                    <div id="pc-sortable-body">
                        @foreach($faqs as $index => $faq)
                            <article
                                class="faq-card js-sort-row"
                                data-id="{{ $faq->id }}"
                                data-order="{{ $faq->firstItem() + $index }}"
                            >
                                <details class="faq-details">
                                    <summary class="faq-head">
                                        <div class="faq-head-main">
                                            <div class="faq-meta">
                                                <span class="sort-order-badge js-sort-order-label">{{ $faq->firstItem() + $index }}</span>

                                                <span class="tag">{{ $faq->category1_name }}</span>
                                                @if($faq->category2_name)
                                                    <span class="tag">{{ $faq->category2_name }}</span>
                                                @endif
                                            </div>

                                            <h2 class="faq-question">
                                                {{ $faq->question }}
                                            </h2>

                                            <div class="faq-updated">{{ $faq->updated_at }}</div>
                                        </div>

                                        <div class="faq-head-side">
                                            <div class="js-normal-actions faq-action-group">
                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('faqs.edit', $faq->id) }}" class="row-action-button">変更</a>

                                                    <button
                                                        type="button"
                                                        class="row-action-button delete js-delete-open"
                                                        data-id="{{ $faq->id }}"
                                                        data-name="{{ $faq->question }}"
                                                    >
                                                        削除
                                                    </button>
                                                 @endif
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
                                            <p class="block-text">{{ $faq->answer }}</p>
                                        </div>

                                        @if(!empty($faq->note))
                                            <div class="note-block">
                                                <p class="block-title">あわせて確認</p>
                                                <p class="block-text">{{ $faq->note }}</p>
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
                <div class="faq-pagination-wrap">
                    <div class="faq-pagination-info">
                        {{ $faqs->firstItem() ?? 0 }}〜{{ $faqs->lastItem() ?? 0 }}件 / {{ $faqs->total() }}件
                    </div>

                    @if ($faqs->hasPages())
                        <nav class="faq-pagination" aria-label="ページネーション">
                            {{-- 前へ --}}
                            @if ($faqs->onFirstPage())
                                <span class="page-button is-disabled">前へ</span>
                            @else
                                <a class="page-button" href="{{ $faqs->previousPageUrl() }}">前へ</a>
                            @endif

                            {{-- ページ番号 --}}
                            @foreach ($faqs->getUrlRange(1, $faqs->lastPage()) as $page => $url)
                                @if ($page == $faqs->currentPage())
                                    <span class="page-number is-current">{{ $page }}</span>
                                @else
                                    <a class="page-number" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- 次へ --}}
                            @if ($faqs->hasMorePages())
                                <a class="page-button" href="{{ $faqs->nextPageUrl() }}">次へ</a>
                            @else
                                <span class="page-button is-disabled">次へ</span>
                            @endif
                        </nav>
                    @endif
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const importButton = document.getElementById('csvImportButton');
    const fileInput = document.getElementById('csv_file');
    const importForm = document.getElementById('csvImportForm');

    importButton.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            importForm.submit();
        }
    });
});
</script>
</body>
</html>