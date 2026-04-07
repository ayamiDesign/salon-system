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

            <div id="sortGuide" class="sort-guide hidden">
                ドラッグして表示順を変更できます。並び替え後に「保存する」を押してください。
            </div>

            @if ($categories->isEmpty())
                <div class="admin-index-empty">
                    カテゴリはまだ登録されていません。
                </div>
            @else
                <div class="admin-index-table-wrap">
                    <div class="admin-index-table-scroll">
                        <table class="admin-index-table">
                            <thead>
                                <tr>
                                    <th style="width: 14%;">表示順</th>
                                    <th style="width: 46%;">カテゴリ名</th>
                                    <th style="width: 18%;">FAQ件数</th>
                                    <th style="width: 22%;">操作</th>
                                </tr>
                            </thead>
                            <tbody id="pc-sortable-body">
                                @foreach ($categories as $category)
                                    <tr
                                        class="js-sort-row"
                                        data-id="{{ $category->id }}"
                                        data-order="{{ $category->sort_order }}"
                                    >
                                        <td class="admin-index-sort-cell">
                                            <div class="admin-index-sort-wrap">
                                                <span class="admin-index-sort-order js-sort-order-label">
                                                    {{ $category->sort_order }}
                                                </span>

                                                <button
                                                    type="button"
                                                    class="admin-index-sort-handle js-sort-handle hidden"
                                                    aria-label="並び替え"
                                                >
                                                    ≡
                                                </button>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="admin-index-title">{{ $category->name }}</p>
                                            <p class="admin-index-sub">
                                                表示順:
                                                <span class="js-sp-sort-order-label">{{ $category->sort_order }}</span>
                                            </p>
                                        </td>

                                        <td>
                                            <span class="admin-index-muted">FAQ {{ $category->count }}件</span>
                                        </td>

                                        <td>
                                            <div class="js-normal-actions admin-index-actions">
                                                <a
                                                    href="{{ route('categories.edit', $category->id) }}"
                                                    class="admin-index-button"
                                                >
                                                    編集
                                                </a>

                                                <button
                                                    type="button"
                                                    class="admin-index-button admin-index-button--delete js-delete-open"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                >
                                                    削除
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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