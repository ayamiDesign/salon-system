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
<body class="bg-slate-100 text-slate-800">
    @php
        $visibleCount = $categories->where('is_visible', true)->count();
        $hiddenCount = $categories->where('is_visible', false)->count();
    @endphp

    <div class="min-h-screen">
        {{-- ヘッダー --}}
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto w-full max-w-[1600px] px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-slate-500 sm:text-sm">
                            SALON ADMIN
                        </p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                            カテゴリー一覧
                        </h1>
                    </div>

                    <div class="flex justify-end sm:justify-start">
                        <a
                            href="{{ route('categories.create') }}"
                            class="inline-flex min-h-[48px] items-center justify-center rounded-2xl bg-accent-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-accent-700"
                        >
                            新規登録
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
            {{-- 一覧テーブル --}}
            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-6 py-7 sm:px-8">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                            カテゴリー一覧テーブル
                        </h2>
                        <p class="mt-3 text-base leading-7 text-slate-500">
                            実装前のデザイン確認用の仮画面です。
                        </p>
                    </div>
                </div>

                @if ($categories->isEmpty())
                    <div class="px-6 py-16 text-center text-sm text-slate-500 sm:px-8">
                        カテゴリはまだ登録されていません。
                    </div>
                @else
                    {{-- PC表示 --}}
                    <div class="hidden lg:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-fixed text-sm">
                                <colgroup>
                                    <col class="w-40">
                                    <col>
                                    <col class="w-72">
                                </colgroup>

                                <thead class="bg-slate-50">
                                    <tr class="text-left text-slate-500">
                                        <th class="px-8 py-5 text-base font-semibold">表示順</th>
                                        <th class="px-8 py-5 text-base font-semibold">カテゴリー名</th>
                                        <th class="px-8 py-5 text-base font-semibold">操作</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($categories as $category)
                                        <tr class="transition hover:bg-slate-50/70">
                                            <td class="px-8 py-7 align-middle">
                                                <span class="text-[2rem] font-semibold leading-none text-slate-900">
                                                    {{ $category->sort_order }}
                                                </span>
                                            </td>

                                            <td class="px-8 py-7 align-middle">
                                                <span class="text-2xl font-bold leading-tight text-slate-900">
                                                    {{ $category->name }}
                                                </span>
                                            </td>

                                            <td class="px-8 py-7 align-middle">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <a
                                                        href="#"
                                                        class="inline-flex min-h-[48px] min-w-[72px] items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-base font-semibold text-slate-800 transition hover:bg-slate-50"
                                                    >
                                                        編集
                                                    </a>
                                                    <a
                                                        href="#"
                                                        class="inline-flex min-h-[48px] min-w-[72px] items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-base font-semibold text-rose-600 transition hover:bg-rose-100"
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
                    </div>

                    {{-- スマホ / タブレット表示 --}}
                    <div class="space-y-5 bg-slate-50 px-4 py-5 sm:px-6 sm:py-6 lg:hidden">
                        @foreach ($categories as $category)
                            <article class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                                            表示順 {{ $category->sort_order }}
                                        </span>

                                        <h3 class="mt-5 text-2xl font-bold leading-tight text-slate-900 sm:text-[2rem]">
                                            {{ $category->name }}
                                        </h3>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <a
                                        href="#"
                                        class="inline-flex min-h-[54px] items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-lg font-semibold text-slate-800 transition hover:bg-slate-50"
                                    >
                                        編集
                                    </a>
                                    <a
                                        href="#"
                                        class="inline-flex min-h-[54px] items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-lg font-semibold text-rose-600 transition hover:bg-rose-100"
                                    >
                                        削除
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>