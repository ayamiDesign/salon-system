{{-- resources/views/categories/index.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ一覧</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: {
                            50: '#f5f7ff',
                            100: '#e8edff',
                            500: '#4f46e5',
                            600: '#4338ca',
                            700: '#3730a3'
                        }
                    },
                    boxShadow: {
                        soft: '0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px rgba(15, 23, 42, 0.06)'
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // -----------------------------
            // 削除モーダル
            // -----------------------------
            const modal = document.getElementById('deleteModal');
            const closeButton = document.getElementById('deleteModalClose');
            const deleteForm = document.getElementById('deleteForm');
            const deleteCategoryName = document.getElementById('deleteCategoryName');
            const openButtons = document.querySelectorAll('.js-delete-open');

            // 各削除ボタンにクリックイベントをつける
            openButtons.forEach(button => {
                button.addEventListener('click', function () {

                    // カテゴリID・カテゴリ名を取得
                    const categoryId = this.dataset.id;
                    const categoryName = this.dataset.name;

                    deleteCategoryName.textContent = categoryName;

                     // 削除フォームの送信先URLをセット
                    deleteForm.action = `/categories/${categoryId}`;

                    // モーダルを表示
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // キャンセルボタンを押したらモーダルを閉じる
            closeButton?.addEventListener('click', function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            // モーダルの背景クリックでも閉じる
            modal?.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });

            // -----------------------------
            // 並び替えモードUI
            // -----------------------------
            const sortModeButton = document.getElementById('sortModeButton');
            const saveSortButton = document.getElementById('saveSortButton');
            const cancelSortButton = document.getElementById('cancelSortButton');
            const sortGuide = document.getElementById('sortGuide');

            // 通常の編集・削除ボタン
            const normalActions = document.querySelectorAll('.js-normal-actions');
            // ドラッグ用ハンドル
            const sortHandles = document.querySelectorAll('.js-sort-handle');
             // 並び替え対象の行
            const sortableRows = document.querySelectorAll('.js-sort-row');

            // 並び替え中かどうか
            let isSortMode = false;
            // 今ドラッグしている行
            let draggedItem = null;

            // 並び替えモードON
            function enterSortMode() {
                isSortMode = true;

                sortModeButton.classList.add('hidden');
                saveSortButton.classList.remove('hidden');
                cancelSortButton.classList.remove('hidden');
                sortGuide.classList.remove('hidden');

                sortHandles.forEach(el => el.classList.remove('hidden'));
                normalActions.forEach(el => el.classList.add('hidden'));

                sortableRows.forEach(row => {
                    row.setAttribute('draggable', 'true');
                    row.classList.add('cursor-move');
                });
            }

            function leaveSortMode(reset = false) {
                isSortMode = false;

                sortModeButton.classList.remove('hidden');
                saveSortButton.classList.add('hidden');
                cancelSortButton.classList.add('hidden');
                sortGuide.classList.add('hidden');

                sortHandles.forEach(el => el.classList.add('hidden'));
                normalActions.forEach(el => el.classList.remove('hidden'));

                sortableRows.forEach(row => {
                    row.removeAttribute('draggable');
                    row.classList.remove('cursor-move', 'opacity-60', 'ring-2', 'ring-accent-100');
                });

                if (reset) {
                    window.location.reload();
                }

                updateSortOrderLabels();
            }

            function updateSortOrderLabels() {
                const pcRows = document.querySelectorAll('#pc-sortable-body .js-sort-row');
                pcRows.forEach((row, index) => {
                    const order = index + 1;
                    row.dataset.order = order;

                    const id = row.dataset.id;

                    const pcLabel = row.querySelector('.js-sort-order-label');
                    if (pcLabel) pcLabel.textContent = order;

                    const spCard = document.querySelector(`.js-sp-card[data-id="${id}"]`);
                    if (spCard) {
                        const spLabel = spCard.querySelector('.js-sp-sort-order-label');
                        if (spLabel) spLabel.textContent = order;
                    }
                });
            }

            sortModeButton?.addEventListener('click', enterSortMode);

            cancelSortButton?.addEventListener('click', function () {
                leaveSortMode(true);
            });

            saveSortButton?.addEventListener('click', async function () {
                const orderedIds = Array.from(document.querySelectorAll('#pc-sortable-body .js-sort-row'))
                    .map(row => row.dataset.id);

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                saveSortButton.disabled = true;
                saveSortButton.textContent = '保存中...';

                try {
                    const response = await fetch('/categories/order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ids: orderedIds
                        })
                    });

                    if (!response.ok) {
                        throw new Error('保存失敗');
                    }

                    const data = await response.json();

                    alert(data.message);
                    window.location.reload();

                } catch (error) {
                    alert('表示順の保存に失敗しました');
                    console.error(error);
                } finally {
                    saveSortButton.disabled = false;
                    saveSortButton.textContent = '保存する';
                }
            });

            sortableRows.forEach(row => {
                row.addEventListener('dragstart', function () {
                    if (!isSortMode) return;
                    draggedItem = this;
                    this.classList.add('opacity-60');
                });

                row.addEventListener('dragend', function () {
                    this.classList.remove('opacity-60');
                    sortableRows.forEach(r => r.classList.remove('ring-2', 'ring-accent-100'));
                });

                row.addEventListener('dragover', function (e) {
                    if (!isSortMode) return;
                    e.preventDefault();
                    this.classList.add('ring-2', 'ring-accent-100');
                });

                row.addEventListener('dragleave', function () {
                    this.classList.remove('ring-2', 'ring-accent-100');
                });

                row.addEventListener('drop', function (e) {
                    if (!isSortMode) return;
                    e.preventDefault();
                    this.classList.remove('ring-2', 'ring-accent-100');

                    if (!draggedItem || draggedItem === this) return;

                    const tbody = this.parentNode;
                    const rows = Array.from(tbody.querySelectorAll('.js-sort-row'));
                    const draggedIndex = rows.indexOf(draggedItem);
                    const targetIndex = rows.indexOf(this);

                    if (draggedIndex < targetIndex) {
                        tbody.insertBefore(draggedItem, this.nextSibling);
                    } else {
                        tbody.insertBefore(draggedItem, this);
                    }

                    updateSortOrderLabels();
                });
            });

            updateSortOrderLabels();
        });
    </script>
</head>

<body class="bg-slate-50 text-slate-800">
    {{-- 削除確認モーダル --}}
    <div
        id="deleteModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h2 class="text-lg font-semibold text-slate-900">カテゴリを削除しますか？</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                <span id="deleteCategoryName" class="font-semibold text-slate-900"></span>
                を削除します。<br>
                この操作は取り消せません。
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    id="deleteModalClose"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    キャンセル
                </button>

                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-700"
                    >
                        削除する
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium tracking-wide text-slate-500">SALON ADMIN</p>
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">カテゴリ一覧</h1>
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
                            href="{{ route('categories.create') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-accent-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-700"
                        >
                            新規登録
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">カテゴリ一覧テーブル</h3>
                            <p class="mt-1 text-sm text-slate-500">カテゴリの編集・削除・並び替えができます。</p>
                        </div>
                    </div>

                    <div
                        id="sortGuide"
                        class="mt-3 hidden rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800"
                    >
                        ドラッグして表示順を変更できます。保存するまでは反映されません。
                    </div>
                </div>

                @if ($categories->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-slate-500">
                        カテゴリはまだ登録されていません。
                    </div>
                @else
                    {{-- PC表示 --}}
                    <div class="hidden overflow-x-auto lg:block">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-slate-500">
                                    <th class="px-5 py-3 font-medium">表示順</th>
                                    <th class="px-5 py-3 font-medium">カテゴリ名</th>
                                    <th class="px-5 py-3 font-medium">操作</th>
                                </tr>
                            </thead>
                            <tbody id="pc-sortable-body" class="divide-y divide-slate-100">
                                @foreach ($categories as $category)
                                    <tr
                                        class="js-sort-row hover:bg-slate-50/70"
                                        data-id="{{ $category->id }}"
                                        data-order="{{ $category->sort_order }}"
                                    >
                                        <td class="px-5 py-4 font-semibold text-slate-900">
                                            <div class="flex items-center gap-3">
                                                <button
                                                    type="button"
                                                    class="js-sort-handle hidden rounded-lg border border-slate-300 bg-white px-2 py-1 text-slate-500"
                                                    tabindex="-1"
                                                >
                                                    ≡
                                                </button>
                                                <span class="js-sort-order-label">{{ $category->sort_order }}</span>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="font-medium text-slate-800">
                                                    {{ $category->name }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="js-normal-actions flex flex-wrap gap-2">
                                                <a
                                                    href="{{ route('categories.edit', $category->id) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50"
                                                >
                                                    編集
                                                </a>
                                                <button
                                                    type="button"
                                                    class="js-delete-open inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100"
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

                    {{-- スマホ表示 --}}
                    <div class="space-y-4 p-4 lg:hidden">
                        @foreach ($categories as $category)
                            <div
                                class="js-sp-card rounded-xl border border-slate-200 bg-white p-4"
                                data-id="{{ $category->id }}"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button
                                                type="button"
                                                class="js-sort-handle hidden rounded-lg border border-slate-300 bg-white px-2 py-1 text-slate-500"
                                                tabindex="-1"
                                            >
                                                ≡
                                            </button>

                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                表示順 <span class="js-sp-sort-order-label ml-1">{{ $category->sort_order }}</span>
                                            </span>
                                        </div>

                                        <p class="mt-3 text-base font-semibold leading-6 text-slate-900">
                                            {{ $category->name }}
                                        </p>
                                    </div>
                                </div>

                                <div class="js-normal-actions mt-4 flex gap-2">
                                    <a
                                        href="{{ route('categories.edit', $category->id) }}"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700"
                                    >
                                        編集
                                    </a>
                                    <button
                                        type="button"
                                        class="js-delete-open inline-flex flex-1 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                    >
                                        削除
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>