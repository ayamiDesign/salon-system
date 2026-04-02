<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ新規登録</title>

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
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800">
    <div class="min-h-screen">

        <!-- ヘッダー -->
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-5xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium tracking-wide text-slate-500">SALON ADMIN</p>
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">カテゴリ新規登録</h1>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('categories.index') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            一覧へ戻る
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- メイン -->
        <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- 説明 -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <h2 class="text-lg font-semibold text-slate-900">カテゴリ情報を登録</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">
                    FAQで使用するカテゴリをまとめて登録するための仮デザインです。
                </p>
            </section>

            <!-- フォーム -->
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">登録フォーム</h3>
                            <p class="mt-1 text-sm text-slate-500">複数のカテゴリをまとめて追加できます。</p>
                        </div>

                        <button type="button"
                                onclick="addCategoryInput()"
                                class="inline-flex items-center justify-center rounded-lg bg-accent-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-700">
                            ＋ 入力欄を追加
                        </button>
                    </div>
                </div>

                @php
                    $categoryNames = old('name', $sessionInput['name'] ?? ['']);
                @endphp

                <!-- バリデーションアラート -->
                @if ($errors->any())
                    <script>
                        window.addEventListener('DOMContentLoaded', function () {
                            alert(@json(implode("\n", $errors->all())));
                        });
                    </script>
                @endif

                <form action="{{ route('categories.confirm') }}" method="post" class="p-5">
                    @csrf

                    <div class="rounded-xl border border-slate-200 bg-slate-50/70">

                        <!-- ヘッダー -->
                        <div class="hidden md:grid grid-cols-12 gap-3 border-b border-slate-200 px-4 py-3 text-xs font-semibold text-slate-500">
                            <div class="col-span-7">カテゴリ名</div>
                            <div class="col-span-5 text-right">操作</div>
                        </div>

                        <!-- 入力欄 -->
                        <div id="category-list" class="divide-y divide-slate-200">
                            @foreach($categoryNames as $index => $categoryName)
                                <div class="category-row px-4 py-4">
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-12 md:items-center">

                                        <!-- カテゴリ名 -->
                                        <div class="md:col-span-7">
                                            <input
                                                type="text"
                                                name="name[]"
                                                value="{{ $categoryName }}"
                                                class="w-full rounded-lg border px-3 py-2"
                                                required
                                            >
                                        </div>

                                        <!-- 削除ボタン -->
                                        <div class="md:col-span-5 flex md:justify-end">
                                            <button type="button"
                                                    onclick="removeInput(this)"
                                                    class="rounded-lg border px-4 py-2 text-red-600">
                                                削除
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ボタン -->
                    <div class="mt-6 flex justify-end">
                        <button class="bg-accent-600 text-white px-6 py-2 rounded-lg">
                            確認する
                        </button>
                    </div>
                </form>
            </section>

        </main>
    </div>

    <!-- JS -->
    <script>
        function addCategoryInput() {
            const container = document.getElementById('category-list');

            const div = document.createElement('div');
            div.className = 'category-row px-4 py-4';

            div.innerHTML = `
                <div class="grid grid-cols-1 gap-3 md:grid-cols-12 md:items-center">

                    <div class="md:col-span-7">
                        <input
                            type="text"
                            name="name[]"
                            class="w-full rounded-lg border px-3 py-2"
                            required
                        >
                    </div>

                    <div class="md:col-span-5 flex md:justify-end">
                        <button type="button"
                                onclick="removeInput(this)"
                                class="rounded-lg border px-4 py-2 text-red-600">
                            削除
                        </button>
                    </div>

                </div>
            `;

            container.appendChild(div);
        }

        function removeInput(button) {
            const rows = document.querySelectorAll('.category-row');

            if (rows.length === 1) {
                rows[0].querySelector('input').value = '';
                return;
            }

            button.closest('.category-row').remove();
        }
    </script>

</body>
</html>