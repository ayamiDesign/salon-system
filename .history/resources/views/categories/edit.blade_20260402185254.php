<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ編集登録</title>

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
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">カテゴリ編集登録</h1>
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

                <!-- 上部 -->
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">編集フォーム</h3>
                        </div>
                    </div>
                </div>

                @php
                    $categoryNames = old('name', $sessionInput['name'] ?? ['']);
                @endphp

                <!-- エラー -->
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
                        <div class="hidden md:grid grid-cols-12 gap-3 border-b border-slate-200 px-4 py-3 text-xs font-semibold tracking-wide text-slate-500">
                            <div class="col-span-2">No.</div>
                            <div class="col-span-7">カテゴリ名</div>
                        </div>

                        <!-- 入力行 -->
                        <div id="category-list" class="divide-y divide-slate-200">
                            @foreach($categoryNames as $index => $categoryName)
                                <div class="category-row px-4 py-4">
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-12 md:items-center">

                                        <!-- No -->
                                        <div class="md:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-slate-700 md:hidden">
                                                No.
                                            </label>
                                            <div class="inline-flex min-w-[44px] items-center justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-700 border border-slate-200">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>

                                        <!-- カテゴリ名 -->
                                        <div class="md:col-span-7">
                                            <label class="mb-2 block text-sm font-medium text-slate-700 md:hidden">
                                                カテゴリ名 <span class="text-rose-500">必須</span>
                                            </label>
                                            <input
                                                type="text"
                                                name="name[]"
                                                value="{{ $categoryName }}"
                                                placeholder="例：予約・受付"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ルール -->
                    <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-medium text-blue-900">入力ルール</p>
                        <ul class="mt-1 space-y-1 text-sm leading-6 text-blue-800">
                            <li>・カテゴリ名は、内容が分かりやすい名称でご入力ください</li>
                            <li>・表示順は登録後に変更することができます</li>
                            <li>・不要な行は「削除」ボタンから削除してください</li>
                            <li>・複数のカテゴリをまとめて登録することも可能です</li>
                        </ul>
                    </div>

                    <!-- ボタン -->
                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('categories.index') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            キャンセル
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-accent-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-accent-700">
                            確認する
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>