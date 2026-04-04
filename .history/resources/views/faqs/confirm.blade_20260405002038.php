<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ登録確認</title>

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
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">FAQ登録確認</h1>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                                onclick="history.back()"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            入力画面へ戻る
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- メイン -->
        <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- 説明 -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <h2 class="text-lg font-semibold text-slate-900">入力内容を確認</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">
                    登録内容を確認し、問題なければそのまま登録してください。
                </p>
            </section>

            <!-- フォーム -->
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-base font-semibold text-slate-900">確認フォーム</h3>
                        <p class="text-sm text-slate-500">FAQごとの内容を確認できます。</p>
                    </div>
                </div>

                <form action="{{ route('faqs.store') }}" method="post" class="p-5">
                    @csrf

                    <div class="space-y-6">
                        @foreach ($faqs as $index => $faq)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                                <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="inline-flex h-9 min-w-[36px] items-center justify-center rounded-full border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900">FAQ {{ $index + 1 }}</h4>
                                            <p class="text-sm text-slate-500">登録内容の確認</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 space-y-6">

                                    <!-- 基本情報 -->
                                    <div>
                                        <h5 class="text-sm font-semibold text-slate-900">基本情報</h5>

                                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">カテゴリ（メイン）</p>
                                                <div class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800">
                                                    {{ $faq['category1_name'] ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">カテゴリ（サブ・任意）</p>
                                                <div class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800">
                                                    {{ $faq['category2_name'] ?: '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQ内容 -->
                                    <div>
                                        <h5 class="text-sm font-semibold text-slate-900">FAQ内容</h5>

                                        <div class="mt-4 space-y-4">
                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">質問</p>
                                                <div class="flex items-start rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 whitespace-pre-line">
                                                    {{ $faq['question'] }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">回答</p>
                                                <div class="flex items-start rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 whitespace-pre-line">
                                                    {{ $faq['answer'] }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">あわせて確認</p>
                                               <div class="flex min-h-[56px] items-start rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm leading-relaxed whitespace-pre-line {{ !empty($faq['note']) ? 'text-slate-800' : 'text-slate-400' }}">
                                                    {{ $faq['note'] ?: '未入力' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 参考資料 -->
                                    <div>
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h5 class="text-sm font-semibold text-slate-900">参考資料</h5>
                                                <p class="text-sm text-slate-500">入力された内容を確認してください。</p>
                                            </div>
                                        </div>

                                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">参考URL</p>
                                                <div class="flex min-h-[46px] items-start rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm break-all {{ !empty($faq['url']) ? 'text-slate-800' : 'text-slate-400' }}">
                                                    {{ $faq['url'] ?: '未入力' }}
                                                </div>
                                            </div>

                                            <div>
                                                <p class="mb-2 block text-sm font-medium text-slate-700">PDFファイル</p>
                                                <div class="flex min-h-[46px] items-start rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm {{ !empty($faq['pdf_original_name']) ? 'text-slate-800' : 'text-slate-400' }}">
                                                    {{ $faq['pdf_original_name'] ?: 'なし' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 表示設定 -->
                                    <div>
                                        <div class="mt-4">
                                            <p class="mb-2 block text-sm font-medium text-slate-700">表示設定</p>
                                            <div class="inline-flex w-full items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 sm:w-auto">
                                                {{ !empty($faq['is_visible']) ? '表示する' : '非表示' }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- hidden -->
                                <input type="hidden" name="faqs[{{ $index }}][category1_id]" value="{{ $faq['category1_id'] }}">
                                <input type="hidden" name="faqs[{{ $index }}][category2_id]" value="{{ $faq['category2_id'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] }}">
                                <input type="hidden" name="faqs[{{ $index }}][answer]" value="{{ $faq['answer'] }}">
                                <input type="hidden" name="faqs[{{ $index }}][note]" value="{{ $faq['note'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][url]" value="{{ $faq['url'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][pdf_temp_path]" value="{{ $faq['pdf_temp_path'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][pdf_original_name]" value="{{ $faq['pdf_original_name'] ?? '' }}">
                                <input type="hidden" name="faqs[{{ $index }}][is_visible]" value="{{ !empty($faq['is_visible']) ? 1 : 0 }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- 注意 -->
                    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-medium text-blue-900">確認事項</p>
                        <ul class="mt-1 space-y-1 text-sm leading-6 text-blue-800">
                            <li>・登録後の表示順は一覧画面で調整してください</li>
                            <li>・PDFは確認画面表示時点で一時保存されています</li>
                            <li>・入力画面に戻る場合、ブラウザの仕様によりPDF再選択が必要になることがあります</li>
                        </ul>
                    </div>
                    
                    <!-- ボタン -->
                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('faqs.create') }}"
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