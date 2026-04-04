{{-- resources/views/faqs/index.blade.php --}}
@php
    $categories = [
        'すべて',
        '予約・受付',
        '施術ルール',
        'シフト',
        '接客',
        '割引・特典',
    ];

    $popularKeywords = [
        '予約',
        '当日予約',
        '指名',
        'シフト',
        '割引',
        'クーポン',
        '施術順',
        '接客',
    ];

    $faqs = [
        [
            'sort_order' => 1,
            'category' => '予約・受付',
            'question' => '当日予約のお客様は何分前まで受付可能ですか？',
            'answer' => '基本的には予約枠の空き状況によりますが、直前予約の場合でも受付可能なケースがあります。店舗の予約状況を確認したうえで案内してください。',
            'note' => '受付ルール一覧を確認',
            'reference' => 'URLあり',
            'is_visible' => true,
        ],
        [
            'sort_order' => 2,
            'category' => '割引・特典',
            'question' => 'Instagram特典は他クーポンと併用できますか？',
            'answer' => '原則として他クーポンとの併用はできません。ただし、キャンペーン内容によって例外があるため、実施中の案内文を確認してください。',
            'note' => 'SNSキャンペーン資料あり',
            'reference' => 'PDFあり',
            'is_visible' => true,
        ],
        [
            'sort_order' => 3,
            'category' => '施術ルール',
            'question' => '施術に入る順番はどのように決まりますか？',
            'answer' => '基本は予約時間と店舗ルールに沿って判断します。スタッフ状況やコース内容によって調整が必要な場合があります。',
            'note' => '施術順ガイドを確認',
            'reference' => 'なし',
            'is_visible' => false,
        ],
        [
            'sort_order' => 4,
            'category' => 'シフト',
            'question' => '急なシフト変更をお願いしたい場合はどうすればいいですか？',
            'answer' => 'まず店舗責任者へ連絡し、その後所定の連絡方法で共有してください。既存予約への影響がある場合は個別に調整が必要です。',
            'note' => 'シフト変更ルールあり',
            'reference' => 'URLあり',
            'is_visible' => true,
        ],
        [
            'sort_order' => 5,
            'category' => '接客',
            'question' => '指名変更の申し出があった場合はどう対応しますか？',
            'answer' => 'お客様のご希望を丁寧に確認し、店舗ルールに沿って責任者へ共有したうえで案内します。感情的な受け答えは避け、事実ベースで対応してください。',
            'note' => '接客対応フロー参照',
            'reference' => 'PDFあり',
            'is_visible' => true,
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ一覧</title>

    {{-- Node.js不要で使う Tailwind CDN --}}
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
        {{-- ヘッダー --}}
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium tracking-wide text-slate-500">SALON ADMIN</p>
                        <h1 class="mt-1 text-2xl font-semibold text-slate-900">FAQ一覧</h1>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="#"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            一括登録
                        </a>
                        <a href="#"
                           class="inline-flex items-center justify-center rounded-lg bg-accent-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-700">
                            新規登録
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            {{-- 概要カード --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">サロン業務Q&Aを一覧管理</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            よくある質問をカテゴリごとに整理し、確認しやすくした管理画面イメージです。
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">FAQ件数</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ count($faqs) }}件</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">表示中</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">
                                {{ collect($faqs)->where('is_visible', true)->count() }}件
                            </p>
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 col-span-2 sm:col-span-1">
                            <p class="text-xs text-slate-500">非表示</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">
                                {{ collect($faqs)->where('is_visible', false)->count() }}件
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 検索フォーム --}}
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <form action="#" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                        <div class="md:col-span-3">
                            <label for="category" class="mb-2 block text-sm font-medium text-slate-700">カテゴリ</label>
                            <select id="category" name="category"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-accent-500 focus:ring-2 focus:ring-accent-100">
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-5">
                            <label for="keyword" class="mb-2 block text-sm font-medium text-slate-700">キーワード検索</label>
                            <input type="text"
                                id="keyword"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                placeholder="質問・回答・補足を検索"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-100">
                        </div>

                        <div class="md:col-span-2">
                            <label for="status" class="mb-2 block text-sm font-medium text-slate-700">表示状態</label>
                            <select id="status" name="status"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-accent-500 focus:ring-2 focus:ring-accent-100">
                                <option value="">すべて</option>
                                <option value="visible">表示中</option>
                                <option value="hidden">非表示</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 flex items-end">
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
                                検索
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="sm:pt-2">
                                <p class="text-sm font-medium text-slate-700">よくある検索</p>
                                <p class="mt-1 text-xs text-slate-500">よく使うキーワードをワンタップで検索できます</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach ($popularKeywords as $popularKeyword)
                                    <button type="submit"
                                            name="keyword"
                                            value="{{ $popularKeyword }}"
                                            class="inline-flex items-center rounded-full border border-slate-300 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-accent-200 hover:bg-accent-50 hover:text-accent-700">
                                        {{ $popularKeyword }}
                                    </button>
                                @endforeach

                                <a href="#"
                                class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-50">
                                    クリア
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            {{-- テーブル --}}
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">FAQ一覧テーブル</h3>
                        <p class="mt-1 text-sm text-slate-500">後からLaravelの実データに差し替えやすい構成です。</p>
                    </div>
                </div>

                {{-- PC表示 --}}
                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-slate-500">
                                <th class="px-4 py-3 font-medium">表示順</th>
                                <th class="px-4 py-3 font-medium">カテゴリ</th>
                                <th class="px-4 py-3 font-medium">質問</th>
                                <th class="px-4 py-3 font-medium">回答</th>
                                <th class="px-4 py-3 font-medium">合わせて確認</th>
                                <th class="px-4 py-3 font-medium">PDFやURL有無</th>
                                <th class="px-4 py-3 font-medium">表示状態</th>
                                <th class="px-4 py-3 font-medium">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($faqs as $faq)
                                <tr class="align-top hover:bg-slate-50/70">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $faq['sort_order'] }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full bg-accent-50 px-3 py-1 text-xs font-medium text-accent-700">
                                            {{ $faq['category'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-800">
                                        <div class="max-w-[220px] leading-6">
                                            {{ $faq['question'] }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        <div class="max-w-[280px] truncate" title="{{ $faq['answer'] }}">
                                            {{ $faq['answer'] }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        <div class="max-w-[180px] leading-6">
                                            {{ $faq['note'] }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if ($faq['reference'] === 'なし')
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                なし
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                                {{ $faq['reference'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if ($faq['is_visible'])
                                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                                表示中
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                非表示
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="#"
                                               class="inline-flex rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                                編集
                                            </a>
                                            <a href="#"
                                               class="inline-flex rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100">
                                                非表示
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- スマホ・タブレット表示 --}}
                <div class="space-y-4 p-4 lg:hidden">
                    @foreach ($faqs as $faq)
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-semibold text-slate-900">#{{ $faq['sort_order'] }}</span>
                                        <span class="inline-flex rounded-full bg-accent-50 px-3 py-1 text-xs font-medium text-accent-700">
                                            {{ $faq['category'] }}
                                        </span>
                                        @if ($faq['is_visible'])
                                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                                表示中
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                非表示
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-3 text-sm font-medium leading-6 text-slate-900">
                                        {{ $faq['question'] }}
                                    </p>
                                </div>
                            </div>

                            <dl class="mt-4 space-y-3 text-sm">
                                <div>
                                    <dt class="mb-1 font-medium text-slate-500">回答</dt>
                                    <dd class="leading-6 text-slate-700 line-clamp-3">
                                        {{ $faq['answer'] }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="mb-1 font-medium text-slate-500">合わせて確認</dt>
                                    <dd class="text-slate-700">{{ $faq['note'] }}</dd>
                                </div>
                                <div>
                                    <dt class="mb-1 font-medium text-slate-500">PDFやURL有無</dt>
                                    <dd class="text-slate-700">{{ $faq['reference'] }}</dd>
                                </div>
                            </dl>

                            <div class="mt-4 flex gap-2">
                                <a href="#"
                                   class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700">
                                    編集
                                </a>
                                <a href="#"
                                   class="inline-flex flex-1 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700">
                                    非表示
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ページネーション風 --}}
                <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">1〜5件を表示 / 全12件</p>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="#"
                           class="inline-flex h-9 items-center rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-600 hover:bg-slate-50">
                            前へ
                        </a>
                        <a href="#"
                           class="inline-flex h-9 min-w-[36px] items-center justify-center rounded-lg bg-accent-600 px-3 text-sm font-medium text-white">
                            1
                        </a>
                        <a href="#"
                           class="inline-flex h-9 min-w-[36px] items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 hover:bg-slate-50">
                            2
                        </a>
                        <a href="#"
                           class="inline-flex h-9 min-w-[36px] items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 hover:bg-slate-50">
                            3
                        </a>
                        <a href="#"
                           class="inline-flex h-9 items-center rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-600 hover:bg-slate-50">
                            次へ
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>