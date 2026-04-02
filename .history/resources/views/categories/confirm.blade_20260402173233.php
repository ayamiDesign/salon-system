<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ登録確認</title>

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
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-5xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium tracking-wide text-slate-500">SALON ADMIN</p>
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">カテゴリ登録確認</h1>
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

        <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <h2 class="text-lg font-semibold text-slate-900">入力内容をご確認ください</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">
                    登録するカテゴリ内容に問題がなければ、「登録する」を押してください。
                </p>
            </section>

            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">確認一覧</h3>
                            <p class="mt-1 text-sm text-slate-500">以下の内容でカテゴリを登録します。</p>
                        </div>

                        <div class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                            確認画面
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <script>
                        window.addEventListener('DOMContentLoaded', function () {
                            alert(@json(implode("\n", $errors->all())));
                        });
                    </script>
                @endif

                <form action="{{ route('categories.store') }}" method="post" class="p-5">
                    @csrf

                    <div class="rounded-xl border border-slate-200 bg-slate-50/70">
                        <div class="hidden md:grid grid-cols-12 gap-3 border-b border-slate-200 px-4 py-3 text-xs font-semibold tracking-wide text-slate-500">
                            <div class="col-span-2">No.</div>
                            <div class="col-span-7">カテゴリ名</div>
                            <div class="col-span-3 text-right">状態</div>
                        </div>

                        <div class="divide-y divide-slate-200">
                            @foreach ($categoryNames as $index => $categoryName)
                                <div class="px-4 py-4">
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-12 md:items-center">
                                        <div class="md:col-span-2">
                                            <div class="mb-1 text-xs font-semibold tracking-wide text-slate-500 md:hidden">
                                                No.
                                            </div>
                                            <div class="inline-flex min-w-[44px] items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>

                                        <div class="md:col-span-7">
                                            <div class="mb-1 text-xs font-semibold tracking-wide text-slate-500 md:hidden">
                                                カテゴリ名
                                            </div>
                                            <div class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800">
                                                {{ $categoryName }}
                                            </div>
                                            <input type="hidden" name="name[]" value="{{ $categoryName }}">
                                        </div>

                                        <div class="md:col-span-3 flex md:justify-end">
                                            <div class="mb-1 text-xs font-semibold tracking-wide text-slate-500 md:hidden">
                                                状態
                                            </div>
                                            <div class="inline-flex items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                                                登録予定
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3">
                        <p class="text-sm font-medium text-amber-900">ご確認ください</p>
                        <ul class="mt-1 space-y-1 text-sm leading-6 text-amber-800">
                            <li>・カテゴリ名に誤字脱字がないかご確認ください</li>
                            <li>・表示順は登録後に一覧画面から変更できます</li>
                            <li>・修正する場合は「入力画面へ戻る」から戻ってください</li>
                        </ul>
                    </div>

                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('categories.create') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            入力画面へ戻る
                        </a>

                        <input type="submit"
                               value="登録する"
                               class="inline-flex cursor-pointer items-center justify-center rounded-lg bg-accent-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-accent-700">
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>