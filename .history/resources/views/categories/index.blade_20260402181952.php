{{-- resources/views/categories/index.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ一覧</title>

    {{-- Node不要で使う Tailwind CDN --}}
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
    </script>
</head>
<body class="bg-slate-50 text-slate-800">
    @php
        $visibleCount = $categories->where('is_visible', true)->count();
        $hiddenCount = $categories->where('is_visible', false)->count();
    @endphp

    <div class="min-h-screen">
        {{-- ヘッダー --}}
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium tracking-wide text-slate-500">SALON ADMIN</p>
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">カテゴリ一覧</h1>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        {{-- <a href="#"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            一括登録
                        </a> --}}
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
            {{-- 概要カード --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">FAQカテゴリを一覧で管理</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            FAQに紐づくカテゴリを見やすく整理した、管理画面デザインの仮レイアウトです。
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">カテゴリ数</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $categories->count() }}件</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">表示中</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $visibleCount }}件</p>
                        </div>

                        <div class="col-span-2 rounded-xl bg-slate-50 px-4 py-3 sm:col-span-1">
                            <p class="text-xs text-slate-500">非表示</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $hiddenCount }}件</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 一覧テーブル --}}
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">カテゴリ一覧テーブル</h3>
                        <p class="mt-1 text-sm text-slate-500">実装前のデザイン確認用の仮画面です。</p>
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
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($categories as $category)
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-5 py-4 font-semibold text-slate-900">
                                            {{ $category->sort_order }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-accent-50 text-sm font-semibold text-accent-700">
                                                    {{ $category->sort_order }}
                                                </span>
                                                <span class="font-medium text-slate-800">
                                                    {{ $category->name }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                <a
                                                    href="#"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50"
                                                >
                                                    編集
                                                </a>
                                                <a
                                                    href="#"
                                                    class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100"
                                                >
                                                    削除
                                                </a>
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
                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                表示順 {{ $category->sort_order }}
                                            </span>
                                        </div>

                                        <p class="mt-3 text-base font-semibold leading-6 text-slate-900">
                                            {{ $category->name }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex gap-2">
                                    <a
                                        href="#"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700"
                                    >
                                        編集
                                    </a>
                                    <a
                                        href="#"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700"
                                    >
                                        削除
                                    </a>
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