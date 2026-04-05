{{-- resources/views/categories/index.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ一覧</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div
    class="app"
    data-sort-container
    data-sort-endpoint="{{ url('/categories/order') }}"
    data-delete-base-url="{{ url('/categories') }}"
>
    @include('partials.header')

    <main class="layout category-layout-single">
        <section class="content">
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">カテゴリを管理</h1>
                    <p class="search-sub">編集・削除・並び替えを、FAQ一覧と同じデザインルールで統一しています。</p>
                </div>
            </div>

            <div class="stats-bar">
                <div class="stats-item">
                    <span class="stats-label">総カテゴリ件数</span>
                    <span class="stats-inline-value">{{ $categories->count() }}</span>
                </div>
            </div>

            <div id="sortGuide" class="sort-guide hidden">
                ドラッグして表示順を変更できます。並び替え後に「保存する」を押してください。
            </div>

            @if ($categories->isEmpty())
                <div class="empty">
                    カテゴリはまだ登録されていません。
                </div>
            @else
                <div class="faq-list">
                    <div id="pc-sortable-body">
                        @foreach ($categories as $category)
                            <article
                                class="faq-card js-sort-row js-sp-card"
                                data-id="{{ $category->id }}"
                                data-order="{{ $category->sort_order }}"
                            >
                                <div class="category-card-head">
                                    <div class="category-card-main">
                                        <div class="faq-meta">
                                            <span class="sort-order-badge js-sort-order-label">{{ $category->sort_order }}</span>
                                            <span class="tag">カテゴリ</span>
                                            <span class="tag">FAQ {{ $category->count }}件</span>
                                        </div>

                                        <h2 class="faq-question">
                                            {{ $category->name }}
                                        </h2>

                                        <div class="faq-updated">
                                            表示順:
                                            <span class="js-sp-sort-order-label">{{ $category->sort_order }}</span>
                                        </div>
                                    </div>

                                    <div class="faq-head-side">
                                        <div class="js-normal-actions faq-action-group">
                                            <a
                                                href="{{ route('categories.edit', $category->id) }}"
                                                class="row-action-button"
                                            >
                                                編集
                                            </a>

                                            <button
                                                type="button"
                                                class="row-action-button delete js-delete-open"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                            >
                                                削除
                                            </button>
                                        </div>

                                        <button
                                            type="button"
                                            class="sort-handle-button js-sort-handle hidden"
                                            aria-label="並び替え"
                                        >
                                            ≡
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </main>

    <div id="deleteModal" class="delete-modal hidden">
        <div class="delete-modal-backdrop"></div>
        <div class="delete-modal-card">
            <p class="delete-modal-title">カテゴリを削除しますか？</p>
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