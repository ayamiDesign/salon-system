<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ新規登録</title>

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
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">FAQ新規登録</h1>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('faqs.index') }}"
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
                <h2 class="text-lg font-semibold text-slate-900">FAQ情報を登録</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">
                    スタッフが検索しやすいように、質問・回答・補足情報を入力してください。
                </p>
            </section>

            <!-- エラー -->
            @if ($errors->any())
                <section class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-soft">
                    <h3 class="text-sm font-semibold text-rose-800">入力内容を確認してください</h3>
                    <ul class="mt-2 space-y-1 text-sm text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>・{{ $error }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- フォーム -->
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-base font-semibold text-slate-900">登録フォーム</h3>
                        <p class="text-sm text-slate-500">スマホでも入力しやすいように、項目ごとに分かりやすく整理しています。</p>
                    </div>
                </div>

                <form action="{{ route('faqs.store') }}" method="post" class="p-5">
                    @csrf

                    <div class="space-y-6">

                        <!-- 基本情報 -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                            <h4 class="text-sm font-semibold text-slate-900">基本情報</h4>

                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <!-- カテゴリ1（必須） -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        カテゴリ（メイン） <span class="text-rose-500">必須</span>
                                    </label>
                                    <select
                                        name="category_ids[]"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                        required
                                    >
                                        <option value="">選択してください</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- カテゴリ2（任意） -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        カテゴリ（サブ・任意）
                                    </label>
                                    <select
                                        name="category_ids[]"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >
                                        <option value="">選択してください</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700">
                                    <input
                                        type="hidden"
                                        name="is_visible"
                                        value="0"
                                    >
                                    <input
                                        type="checkbox"
                                        name="is_visible"
                                        value="1"
                                        class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500"
                                        {{ old('is_visible', 1) ? 'checked' : '' }}
                                    >
                                    表示する
                                </label>
                            </div>
                        </div>

                        <!-- FAQ内容 -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                            <h4 class="text-sm font-semibold text-slate-900">FAQ内容</h4>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="question" class="mb-2 block text-sm font-medium text-slate-700">
                                        質問 <span class="text-rose-500">必須</span>
                                    </label>
                                    <textarea
                                        id="question"
                                        name="question"
                                        rows="3"
                                        placeholder="例：施術に入る順番はどうやって決める？"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                        required
                                    >{{ old('question') }}</textarea>
                                </div>

                                <div>
                                    <label for="answer" class="mb-2 block text-sm font-medium text-slate-700">
                                        回答 <span class="text-rose-500">必須</span>
                                    </label>
                                    <textarea
                                        id="answer"
                                        name="answer"
                                        rows="6"
                                        placeholder="例：① 施術が先に終わった人が優先&#10;② 直前の予約状況が同じ場合は出勤順"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                        required
                                    >{{ old('answer') }}</textarea>
                                </div>

                                <div>
                                    <label for="note" class="mb-2 block text-sm font-medium text-slate-700">
                                        あわせて確認
                                    </label>
                                    <textarea
                                        id="note"
                                        name="note"
                                        rows="4"
                                        placeholder="例：予約表で押さえられている時間を基準に判断してください。"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 参考資料 -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                            <h4 class="text-sm font-semibold text-slate-900">参考資料</h4>

                            <div class="mt-4 grid grid-cols-1 gap-4">
                                <div>
                                    <label for="url" class="mb-2 block text-sm font-medium text-slate-700">
                                        参考URL
                                    </label>
                                    <input
                                        type="url"
                                        id="url"
                                        name="url"
                                        value="{{ old('url') }}"
                                        placeholder="https://example.com"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >
                                </div>

                                <div>
                                    <label for="pdf" class="mb-2 block text-sm font-medium text-slate-700">
                                        PDFパス
                                    </label>
                                    <input
                                        type="text"
                                        id="pdf"
                                        name="pdf"
                                        value="{{ old('pdf') }}"
                                        placeholder="例：manuals/faq-rule.pdf"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- 入力ルール -->
                        <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                            <p class="text-sm font-medium text-blue-900">入力ルール</p>
                            <ul class="mt-1 space-y-1 text-sm leading-6 text-blue-800">
                                <li>・質問はスタッフが検索しそうな言い回しを意識して入力してください</li>
                                <li>・回答は改行を使って、スマホでも読みやすい文章量で入力してください</li>
                                <li>・補足や参考資料がある場合は「あわせて確認」「URL」「PDF」を活用してください</li>
                            </ul>
                        </div>

                    </div>

                    <!-- ボタン -->
                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('faqs.index') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            キャンセル
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-accent-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-accent-700">
                            登録する
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>