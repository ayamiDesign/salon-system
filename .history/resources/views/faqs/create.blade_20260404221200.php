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
                    複数のFAQをまとめて登録できます。スマホでも入力しやすいように、1件ずつカード形式で整理しています。
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
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">登録フォーム</h3>
                            <p class="mt-1 text-sm text-slate-500">必要なFAQをまとめて追加できます。</p>
                        </div>

                        <button type="button"
                                onclick="addFaqBlock()"
                                class="inline-flex items-center justify-center rounded-lg bg-accent-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-700">
                            ＋ 入力欄を追加
                        </button>
                    </div>
                </div>

                @php
                    $oldFaqs = old('faqs', [
                        [
                            'category1_id' => '',
                            'category2_id' => '',
                            'is_visible' => 1,
                            'question' => '',
                            'answer' => '',
                            'note' => '',
                            'url' => '',
                            'pdf' => '',
                            'change_summary' => '',
                        ]
                    ]);
                @endphp

                <form action="{{ route('faqs.store') }}" method="post" class="p-5">
                    @csrf

                    <div id="faq-list" class="space-y-6">
                        @foreach ($oldFaqs as $index => $faq)
                            <div class="faq-block rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                                <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="inline-flex h-9 min-w-[36px] items-center justify-center rounded-full border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 faq-number">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900">FAQ <span class="faq-number-text">{{ $index + 1 }}</span></h4>
                                            <p class="text-sm text-slate-500">質問・回答・参考資料を入力してください。</p>
                                        </div>
                                    </div>

                                    <button type="button"
                                            onclick="removeFaqBlock(this)"
                                            class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-100">
                                        削除
                                    </button>
                                </div>

                                <div class="mt-5 space-y-6">

                                    <!-- 基本情報 -->
                                    <div>
                                        <h5 class="text-sm font-semibold text-slate-900">基本情報</h5>

                                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    カテゴリ（メイン） <span class="text-rose-500">必須</span>
                                                </label>
                                                <select
                                                    name="faqs[{{ $index }}][category1_id]"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                    required
                                                >
                                                    <option value="">選択してください</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @selected(($faq['category1_id'] ?? '') == $category->id)>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    カテゴリ（サブ・任意）
                                                </label>
                                                <select
                                                    name="faqs[{{ $index }}][category2_id]"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                >
                                                    <option value="">選択してください</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @selected(($faq['category2_id'] ?? '') == $category->id)>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQ内容 -->
                                    <div>
                                        <h5 class="text-sm font-semibold text-slate-900">FAQ内容</h5>

                                        <div class="mt-4 space-y-4">
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    質問 <span class="text-rose-500">必須</span>
                                                </label>
                                                <textarea
                                                    name="faqs[{{ $index }}][question]"
                                                    rows="3"
                                                    placeholder="例：施術に入る順番はどうやって決める？"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                    required
                                                >{{ $faq['question'] ?? '' }}</textarea>
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    回答 <span class="text-rose-500">必須</span>
                                                </label>
                                                <textarea
                                                    name="faqs[{{ $index }}][answer]"
                                                    rows="6"
                                                    placeholder="例：① 施術が先に終わった人が優先&#10;② 直前の予約状況が同じ場合は出勤順"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                    required
                                                >{{ $faq['answer'] ?? '' }}</textarea>
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    あわせて確認
                                                </label>
                                                <textarea
                                                    name="faqs[{{ $index }}][note]"
                                                    rows="4"
                                                    placeholder="例：予約表で押さえられている時間を基準に判断してください。"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                >{{ $faq['note'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 参考資料 -->
                                    <div>
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h5 class="text-sm font-semibold text-slate-900">参考資料</h5>
                                                <p class="text-sm text-slate-500">必要な場合のみ入力してください。</p>
                                            </div>
                                        </div>

                                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    参考URL
                                                </label>
                                                <input
                                                    type="url"
                                                    name="faqs[{{ $index }}][url]"
                                                    value="{{ $faq['url'] ?? '' }}"
                                                    placeholder="https://example.com"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                >
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                                    PDFパス
                                                </label>
                                                <input
                                                    type="text"
                                                    name="faqs[{{ $index }}][pdf]"
                                                    value="{{ $faq['pdf'] ?? '' }}"
                                                    placeholder="例：manuals/faq-rule.pdf"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                                >
                                                <p class="mt-2 text-xs leading-5 text-slate-500">
                                                    サーバーに保存済みのPDFパスを入力します。
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 表示設定 -->
                                    <div>
                                        <div class="mt-4">
                                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                                表示設定
                                            </label>
                                            <label class="inline-flex w-full items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 sm:w-auto">
                                                <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="0">
                                                <input
                                                    type="checkbox"
                                                    name="faqs[{{ $index }}][is_visible]"
                                                    value="1"
                                                    class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500"
                                                    {{ !isset($faq['is_visible']) || $faq['is_visible'] ? 'checked' : '' }}
                                                >
                                                表示する
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- 入力ルール -->
                    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-medium text-blue-900">入力ルール</p>
                        <ul class="mt-1 space-y-1 text-sm leading-6 text-blue-800">
                            <li>・質問はスタッフが検索しそうな言い回しを意識して入力してください</li>
                            <li>・回答は改行を使って、スマホでも読みやすい文章量で入力してください</li>
                            <li>・カテゴリはメイン1つ、必要に応じてサブ1つまで設定してください</li>
                            <li>・表示順は登録後の一覧画面で調整してください</li>
                        </ul>
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

        <!-- JS -->
    <script>
        function addCategoryInput() {
            const container = document.getElementById('category-list');

            const index = document.querySelectorAll('.category-row').length + 1;

            const div = document.createElement('div');
            div.className = 'category-row px-4 py-4';

            div.innerHTML = `
                 <div class="faq-block rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                    <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="inline-flex h-9 min-w-[36px] items-center justify-center rounded-full border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 faq-number">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">FAQ <span class="faq-number-text">{{ $index + 1 }}</span></h4>
                                <p class="text-sm text-slate-500">質問・回答・参考資料を入力してください。</p>
                            </div>
                        </div>

                        <button type="button"
                                onclick="removeFaqBlock(this)"
                                class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-100">
                            削除
                        </button>
                    </div>

                    <div class="mt-5 space-y-6">
                        <div>
                            <h5 class="text-sm font-semibold text-slate-900">基本情報</h5>

                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        カテゴリ（メイン） <span class="text-rose-500">必須</span>
                                    </label>
                                    <select
                                        name="faqs[{{ $index }}][category1_id]"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                        required
                                    >
                                        <option value="">選択してください</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(($faq['category1_id'] ?? '') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        カテゴリ（サブ・任意）
                                    </label>
                                    <select
                                        name="faqs[{{ $index }}][category2_id]"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >
                                        <option value="">選択してください</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(($faq['category2_id'] ?? '') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-sm font-semibold text-slate-900">FAQ内容</h5>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        質問 <span class="text-rose-500">必須</span>
                                    </label>
                                    <textarea
                                        name="faqs[{{ $index }}][question]"
                                        rows="3"
                                        placeholder="例：施術に入る順番はどうやって決める？"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                        required
                                    >{{ $faq['question'] ?? '' }}</textarea>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        回答 <span class="text-rose-500">必須</span>
                                    </label>
                                    <textarea
                                        name="faqs[{{ $index }}][answer]"
                                        rows="6"
                                        placeholder="例：① 施術が先に終わった人が優先&#10;② 直前の予約状況が同じ場合は出勤順"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                        required
                                    >{{ $faq['answer'] ?? '' }}</textarea>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        あわせて確認
                                    </label>
                                    <textarea
                                        name="faqs[{{ $index }}][note]"
                                        rows="4"
                                        placeholder="例：予約表で押さえられている時間を基準に判断してください。"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >{{ $faq['note'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h5 class="text-sm font-semibold text-slate-900">参考資料</h5>
                                    <p class="text-sm text-slate-500">必要な場合のみ入力してください。</p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        参考URL
                                    </label>
                                    <input
                                        type="url"
                                        name="faqs[{{ $index }}][url]"
                                        value="{{ $faq['url'] ?? '' }}"
                                        placeholder="https://example.com"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        PDFパス
                                    </label>
                                    <input
                                        type="text"
                                        name="faqs[{{ $index }}][pdf]"
                                        value="{{ $faq['pdf'] ?? '' }}"
                                        placeholder="例：manuals/faq-rule.pdf"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100"
                                    >
                                    <p class="mt-2 text-xs leading-5 text-slate-500">
                                        サーバーに保存済みのPDFパスを入力します。
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mt-4">
                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                    表示設定
                                </label>
                                <label class="inline-flex w-full items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 sm:w-auto">
                                    <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="0">
                                    <input
                                        type="checkbox"
                                        name="faqs[{{ $index }}][is_visible]"
                                        value="1"
                                        class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500"
                                        {{ !isset($faq['is_visible']) || $faq['is_visible'] ? 'checked' : '' }}
                                    >
                                    表示する
                                </label>
                            </div>
                        </div>

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