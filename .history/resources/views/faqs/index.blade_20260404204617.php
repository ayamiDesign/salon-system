{{-- resources/views/faq-demo.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ検索デモ</title>
    <style>
        :root {
            --bg: #f6f7fb;
            --card: #ffffff;
            --line: #e5e7eb;
            --text: #1f2937;
            --sub: #6b7280;
            --accent: #2563eb;
            --accent-soft: #eff6ff;
            --success: #16a34a;
            --warn: #f59e0b;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Hiragino Sans", "Hiragino Kaku Gothic ProN", "Yu Gothic", sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        .app {
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(246, 247, 251, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.9);
        }

        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            box-shadow: var(--shadow);
            flex-shrink: 0;
        }

        .brand-text {
            min-width: 0;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .brand-sub {
            font-size: 12px;
            color: var(--sub);
            margin: 0;
        }

        .layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 20px 40px;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 24px;
        }

        .sidebar,
        .content,
        .hero,
        .faq-card,
        .stats-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
        }

        .sidebar {
            position: sticky;
            top: 78px;
            height: fit-content;
            padding: 18px;
        }

        .sidebar h2,
        .section-title {
            margin: 0 0 14px;
            font-size: 16px;
        }

        .category-list {
            display: grid;
            gap: 10px;
        }

        .category-button {
            width: 100%;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 12px;
            padding: 12px 14px;
            text-align: left;
            cursor: pointer;
            transition: .2s ease;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            color: var(--text);
        }

        .category-button:hover,
        .category-button.is-active {
            border-color: #bfdbfe;
            background: var(--accent-soft);
        }

        .category-name {
            font-weight: 600;
            font-size: 14px;
        }

        .category-count {
            font-size: 12px;
            color: var(--sub);
            flex-shrink: 0;
        }

        .content {
            background: transparent;
            border: 0;
            box-shadow: none;
        }

        .hero {
            padding: 24px;
            margin-bottom: 20px;
        }

        .hero h1 {
            margin: 0 0 10px;
            font-size: 28px;
            line-height: 1.3;
        }

        .hero p {
            margin: 0;
            color: var(--sub);
        }

        .search-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            margin-top: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            height: 52px;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 0 48px 0 16px;
            font-size: 15px;
            outline: none;
            background: #fff;
        }

        .search-box input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--sub);
            font-size: 18px;
        }

        .clear-button {
            height: 52px;
            padding: 0 18px;
            border: 0;
            border-radius: 14px;
            background: var(--text);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .chip {
            border: 1px solid #dbeafe;
            background: #fff;
            color: var(--accent);
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .stats-card {
            padding: 18px;
        }

        .stats-label {
            font-size: 12px;
            color: var(--sub);
            margin-bottom: 6px;
        }

        .stats-value {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
        }

        .faq-list {
            display: grid;
            gap: 14px;
        }

        .faq-card {
            overflow: hidden;
        }

        .faq-head {
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 12px;
            cursor: pointer;
        }

        .faq-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f3f4f6;
            color: #374151;
        }

        .tag.success {
            background: #ecfdf5;
            color: #166534;
        }

        .tag.warn {
            background: #fffbeb;
            color: #92400e;
        }

        .faq-question {
            margin: 0;
            font-size: 18px;
            line-height: 1.5;
        }

        .faq-updated {
            margin-top: 8px;
            font-size: 13px;
            color: var(--sub);
        }

        .faq-toggle {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f3f4f6;
            display: grid;
            place-items: center;
            font-size: 18px;
            color: var(--sub);
        }

        .faq-body {
            border-top: 1px solid var(--line);
            padding: 20px;
            display: none;
            background: #fcfcfd;
        }

        .faq-card.is-open .faq-body {
            display: block;
        }

        .faq-card.is-open .faq-toggle {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .answer-block,
        .note-block,
        .link-block {
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 16px;
            background: #fff;
        }

        .answer-block {
            border-left: 5px solid var(--success);
        }

        .note-block {
            margin-top: 12px;
            border-left: 5px solid var(--warn);
            background: #fffdf7;
        }

        .link-block {
            margin-top: 12px;
        }

        .block-title {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 800;
        }

        .block-text {
            margin: 0;
            font-size: 14px;
            white-space: pre-line;
        }

        .link-list {
            margin: 0;
            padding-left: 18px;
        }

        .link-list li + li {
            margin-top: 6px;
        }

        mark {
            background: #fef08a;
            color: inherit;
            padding: 0 .15em;
            border-radius: 4px;
        }

        .empty {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 40px 20px;
            text-align: center;
            color: var(--sub);
        }

        .mobile-category-wrap {
            display: none;
            margin-top: 16px;
        }

        .mobile-category-select {
            width: 100%;
            height: 48px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0 14px;
            background: #fff;
            font-size: 14px;
        }

        /* 追加：操作ボタン用。既存デザインに寄せた最小限のスタイル */
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .action-button {
            height: 40px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .action-button:hover {
            border-color: #bfdbfe;
            background: var(--accent-soft);
        }

        .action-button.primary {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
        }

        .faq-head-main {
            flex: 1 1 auto;
            min-width: 0;
        }

        .faq-head-side {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .row-action-button {
            height: 32px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--text);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .row-action-button:hover {
            border-color: #bfdbfe;
            background: var(--accent-soft);
        }

        .row-action-button.delete {
            color: #b91c1c;
            border-color: #fecaca;
            background: #fff;
        }

        .row-action-button.delete:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        @media (max-width: 960px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .mobile-category-wrap {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .topbar-inner,
            .layout {
                padding-left: 14px;
                padding-right: 14px;
            }

            .hero {
                padding: 18px;
            }

            .hero h1 {
                font-size: 22px;
            }

            .search-row {
                grid-template-columns: 1fr;
            }

            .clear-button,
            .search-box input {
                height: 48px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .faq-head,
            .faq-body {
                padding: 16px;
            }

            .faq-question {
                font-size: 16px;
            }

            /* 追加：スマホ時だけ自然に縦並び */
            .topbar-inner {
                align-items: flex-start;
            }

            .faq-head {
                flex-direction: column;
            }

            .faq-head-side {
                width: 100%;
                justify-content: flex-end;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
@php
    $faqs = [
        [
            'id' => 1,
            'category' => '予約・受付',
            'question' => '施術に入る順番はどうやって決める？',
            'summary' => 'まずは基本ルールを確認',
            'answer' => "① 施術が先に終わった人が優先\n② 直前に施術が『ない』または終了時刻が『同じ』場合は出勤順",
            'note' => '「施術が終わった時間」ではなく、予約表で押さえられている「予約枠」の時間で判断',
            'links' => ['施術順まるわかりガイド'],
            'updated_at' => '2025/12/22',
            'status' => '改定',
        ],
        [
            'id' => 2,
            'category' => '予約・受付',
            'question' => '予約枠にブロックがある場合、ブロックを「空き時間」か「予約」どちらで扱う？',
            'summary' => 'この場合、施術順はどうなる？',
            'answer' => 'ブロックは「予約」として扱う',
            'note' => '参考リンクもチェック。実際の予約表をPDFで確認。',
            'links' => ['ブロックがあるときの施術順ルール'],
            'updated_at' => '2025/12/15',
            'status' => '基本',
        ],
        [
            'id' => 3,
            'category' => '予約・受付',
            'question' => 'Aさんは12:45に施術終了、Bさんは14:00に出勤。14:30の予約に入るのはどっち？',
            'summary' => '具体例で理解する',
            'answer' => "回答：Aさん\n\n理由：Bさんの出勤時間よりも先にAさんの施術が終わっているため",
            'note' => '実際の予約表をPDFで確認。',
            'links' => ['予約表を見て理解しよう①'],
            'updated_at' => '2025/12/15',
            'status' => '例題',
        ],
        [
            'id' => 4,
            'category' => '予約・受付',
            'question' => 'Aさんは12:00に施術終了、Bさんは12:00に出勤。次に施術に入るのはどっち？',
            'summary' => '同時刻の場合の考え方',
            'answer' => "回答：Aさん\n\n理由：同時刻の場合は、出勤時間の早い人が優先",
            'note' => '実際の予約表をPDFで確認。',
            'links' => ['予約表を見て理解しよう②'],
            'updated_at' => '2025/12/15',
            'status' => '例題',
        ],
        [
            'id' => 5,
            'category' => '予約・受付',
            'question' => 'ペアなどで同時に施術が終わった場合、次に施術に入る順番はどうなる？',
            'summary' => 'ペア対応時の優先ルール',
            'answer' => "① 次の予約までの待機時間が長い人優先\n② 待機時間が『ない』または『同じ』場合は出勤順",
            'note' => '参考リンクも必ずチェック。',
            'links' => ['予約表を見て理解しよう③'],
            'updated_at' => '2025/12/15',
            'status' => '例題',
        ],
        [
            'id' => 6,
            'category' => '予約・受付 / 予約サイト関連',
            'question' => '施術順を入れ替えたはずなのに、サロンボードに反映されていない',
            'summary' => 'サロンボードに反映されないときの対処',
            'answer' => "お会計処理が完了していると、サロンコネクトから担当者の変更を行ってもサロンボードに反映されません。\n\n▼対応方法\n① 会計処理を取り消す\n② サロンコネクトで担当者変更\n③ 再度会計登録を行う",
            'note' => 'サロンボードの会計処理自体を取り消す必要あり。',
            'links' => ['会計処理を取り消す方法'],
            'updated_at' => '2026/01/12',
            'status' => '重要',
        ],
        [
            'id' => 7,
            'category' => '勤怠',
            'question' => '遅刻連絡はどのタイミングで入れる？',
            'summary' => '勤怠ルール',
            'answer' => '出勤予定時刻より前に、所定の連絡先へ共有する。',
            'note' => 'テンプレート文も別途用意しておくと運用しやすいです。',
            'links' => ['勤怠ルールガイド'],
            'updated_at' => '2025/12/05',
            'status' => '基本',
        ],
    ];
@endphp

<div class="app" x-data="faqApp(@js($faqs))" x-init="init()">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">社内FAQ検索デモ</p>
                    <p class="brand-sub">PC / スマホ対応 Laravel Blade サンプル</p>
                </div>
            </div>

            {{-- 追加：新規登録ボタン --}}
            <div class="topbar-actions">
                <button type="button" class="action-button primary" @click="createFaq()">
                    新規登録
                </button>
            </div>
        </div>
    </header>

    <main class="layout">
        <aside class="sidebar">
            <h2>カテゴリ</h2>
            <div class="category-list">
                <button class="category-button" :class="{ 'is-active': selectedCategory === 'すべて' }" @click="selectedCategory = 'すべて'">
                    <span class="category-name">すべて</span>
                    <span class="category-count" x-text="faqs.length + '件'"></span>
                </button>

                <template x-for="category in categories" :key="category.name">
                    <button class="category-button" :class="{ 'is-active': selectedCategory === category.name }" @click="selectedCategory = category.name">
                        <span class="category-name" x-text="category.name"></span>
                        <span class="category-count" x-text="category.count + '件'"></span>
                    </button>
                </template>
            </div>
        </aside>

        <section class="content">
            <div class="hero">
                <h1>知りたいことをすぐ検索</h1>
                <p>スプレッドシートのFAQを、スマホでも見やすい検索UIにしたデモです。カテゴリ絞り込み、キーワード検索、アコーディオン表示に対応しています。</p>

                <div class="search-row">
                    <div class="search-box">
                        <input type="text" x-model="keyword" placeholder="例：施術順 / 予約 / ブロック / サロンボード">
                        <span class="search-icon">⌕</span>
                    </div>
                    <button class="clear-button" @click="resetFilters()">条件クリア</button>
                </div>

                <div class="mobile-category-wrap">
                    <select class="mobile-category-select" x-model="selectedCategory">
                        <option value="すべて">すべて</option>
                        <template x-for="category in categories" :key="category.name">
                            <option :value="category.name" x-text="category.name + '（' + category.count + '件）'"></option>
                        </template>
                    </select>
                </div>

                <div class="chips">
                    <button class="chip" @click="keyword = '施術順'">施術順</button>
                    <button class="chip" @click="keyword = '予約'">予約</button>
                    <button class="chip" @click="keyword = 'ブロック'">ブロック</button>
                    <button class="chip" @click="keyword = 'サロンボード'">サロンボード</button>
                </div>
            </div>

            <div class="stats">
                <div class="stats-card">
                    <div class="stats-label">総FAQ件数</div>
                    <p class="stats-value" x-text="faqs.length"></p>
                </div>
                <div class="stats-card">
                    <div class="stats-label">検索結果</div>
                    <p class="stats-value" x-text="filteredFaqs.length"></p>
                </div>
                <div class="stats-card">
                    <div class="stats-label">選択カテゴリ</div>
                    <p class="stats-value" style="font-size: 18px;" x-text="selectedCategory"></p>
                </div>
            </div>

            <div class="faq-list" x-show="filteredFaqs.length > 0">
                <template x-for="faq in filteredFaqs" :key="faq.id">
                    <article class="faq-card" :class="{ 'is-open': openId === faq.id }">
                        <div class="faq-head" @click="toggle(faq.id)">
                            <div class="faq-head-main">
                                <div class="faq-meta">
                                    <span class="tag">📁 <span x-text="faq.category"></span></span>
                                    {{-- <span class="tag success">✅ <span x-text="faq.status"></span></span> --}}
                                    {{-- <span class="tag warn">🗓 <span x-text="faq.updated_at"></span></span> --}}
                                </div>
                                <h2 class="faq-question" x-html="highlight(faq.question)"></h2>
                                <div class="faq-updated" x-text="faq.updated_at">
                                    <p>最終更新日時：{{faq.updated_at}}</p>
                                </div>
                            </div>

                            {{-- 追加：変更・削除ボタン --}}
                            <div class="faq-head-side" @click.stop>
                                <button type="button" class="row-action-button" @click="editFaq(faq)">
                                    変更
                                </button>
                                <button type="button" class="row-action-button delete" @click="deleteFaq(faq.id)">
                                    削除
                                </button>
                                <button
                                    type="button"
                                    class="row-action-button"
                                    @click="viewHistory(faq.id)"
                                >
                                    履歴
                                </button>
                                <div class="faq-toggle" x-text="openId === faq.id ? '−' : '+'"></div>
                            </div>
                        </div>

                        <div class="faq-body">
                            <div class="answer-block">
                                <p class="block-title">回答</p>
                                <p class="block-text" x-html="highlight(faq.answer).replace(/\n/g, '<br>')"></p>
                            </div>

                            <template x-if="faq.note">
                                <div class="note-block">
                                    <p class="block-title">あわせて確認</p>
                                    <p class="block-text" x-html="highlight(faq.note)"></p>
                                </div>
                            </template>

                            <template x-if="faq.links && faq.links.length">
                                <div class="link-block">
                                    <p class="block-title">参考リンク</p>
                                    <ul class="link-list">
                                        <template x-for="link in faq.links" :key="link">
                                            <li><a href="#" @click.prevent x-text="link"></a></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </article>
                </template>
            </div>

            <div class="empty" x-show="filteredFaqs.length === 0">
                該当するFAQが見つかりませんでした。<br>
                キーワードやカテゴリを変更してお試しください。
            </div>
        </section>
    </main>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function faqApp(initialFaqs) {
        return {
            faqs: initialFaqs,
            keyword: '',
            selectedCategory: 'すべて',
            openId: null,
            init() {
                if (this.filteredFaqs.length) {
                    this.openId = this.filteredFaqs[0].id;
                }
            },
            get categories() {
                const counts = {};

                this.faqs.forEach(faq => {
                    faq.category.split('/').map(v => v.trim()).forEach(name => {
                        counts[name] = (counts[name] || 0) + 1;
                    });
                });

                return Object.entries(counts)
                    .map(([name, count]) => ({ name, count }))
                    .sort((a, b) => a.name.localeCompare(b.name, 'ja'));
            },
            get filteredFaqs() {
                const keyword = this.keyword.trim().toLowerCase();

                return this.faqs.filter(faq => {
                    const categoryMatched = this.selectedCategory === 'すべて'
                        ? true
                        : faq.category.split('/').map(v => v.trim()).includes(this.selectedCategory);

                    if (!categoryMatched) {
                        return false;
                    }

                    if (!keyword) {
                        return true;
                    }

                    const target = [faq.category, faq.question, faq.summary, faq.answer, faq.note, ...(faq.links || [])]
                        .join(' ')
                        .toLowerCase();

                    return target.includes(keyword);
                });
            },
            toggle(id) {
                this.openId = this.openId === id ? null : id;
            },
            resetFilters() {
                this.keyword = '';
                this.selectedCategory = 'すべて';
                this.openId = this.faqs[0]?.id ?? null;
            },
            escapeHtml(value) {
                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            },
            highlight(value) {
                const text = this.escapeHtml(value ?? '');
                const keyword = this.keyword.trim();

                if (!keyword) {
                    return text;
                }

                const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>');
            },

            createFaq() {
                alert('新規登録');
                window.location.href = '{{ route("faqs.create") }}';
            },

            editFaq(faq) {
                alert('変更: ' + faq.id);
                window.location.href = `/faqs/${faq.id}/edit`;
            },

            deleteFaq(id) {
                if (confirm('削除しますか？')) {
                    this.faqs = this.faqs.filter(faq => faq.id !== id);

                    if (this.openId === id) {
                        this.openId = this.filteredFaqs[0]?.id ?? null;
                    }
                }
                // 本番では fetch や form POST に置き換え
            },
            viewHistory(id) {
                window.location.href = `/faqs/${id}/history`;
            }
        }
    }
</script>
</body>
</html>