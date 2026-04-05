<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>変更履歴</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        .history-page-head {
            margin-bottom: 14px;
            padding: 18px;
        }

        .history-page-title {
            margin: 0;
            font-size: 28px;
            line-height: 1.35;
            letter-spacing: 0.01em;
        }

        .history-page-sub {
            margin: 8px 0 0;
            color: var(--sub);
            font-size: 14px;
        }

        .history-target-inline {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid var(--line-soft);
        }

        .history-target-kicker {
            font-size: 12px;
            font-weight: 700;
            color: var(--sub);
        }

        .history-target-main {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .history-target-question {
            margin: 0;
            font-size: 22px;
            line-height: 1.45;
            letter-spacing: 0.01em;
        }

        .history-list {
            display: grid;
            gap: 12px;
        }

        .history-card {
            overflow: hidden;
        }

        .history-details {
            display: block;
        }

        .history-details summary {
            list-style: none;
            cursor: pointer;
        }

        .history-details summary::-webkit-details-marker {
            display: none;
        }

        .history-summary {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 18px 20px;
        }

        .history-summary-main {
            min-width: 0;
        }

        .history-summary-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .history-type-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-deep);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .history-date {
            font-size: 13px;
            color: var(--sub);
            white-space: nowrap;
        }

        .history-summary-line {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .history-summary-content {
            min-width: 0;
        }

        .history-inline-label {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: 700;
            color: var(--sub);
            letter-spacing: 0.02em;
        }

        .history-summary-text {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.5;
            color: var(--text);
            word-break: break-word;
        }

        .history-summary-text.is-empty {
            font-size: 15px;
            font-weight: 500;
            color: #9ca3af;
        }

        .history-summary-side {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .history-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #fff;
            color: var(--sub);
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            transition: 0.2s ease;
            flex-shrink: 0;
        }

        .history-toggle::before {
            content: "+";
        }

        .history-details[open] .history-toggle {
            background: #f8fbff;
            color: var(--accent);
            border-color: #d7e4fb;
        }

        .history-details[open] .history-toggle::before {
            content: "−";
        }

        .history-body {
            display: none;
            padding: 18px 20px 20px;
            border-top: 1px solid var(--line-soft);
            background: #fcfdff;
        }

        .history-details[open] .history-body {
            display: block;
        }

        .history-section + .history-section {
            margin-top: 16px;
        }

        .history-section-label {
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--sub);
        }

        .history-pre {
            white-space: pre-wrap;
            word-break: break-word;
        }

        .history-body .faq-question {
            margin: 0;
            font-size: 18px;
            line-height: 1.55;
        }

        .history-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        @media (min-width: 961px) {
            .history-page-head {
                padding: 22px;
                margin-bottom: 16px;
            }

            .history-page-title {
                font-size: 32px;
            }

            .history-target-inline {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
            }

            .history-target-main {
                flex-direction: row;
                align-items: center;
                gap: 14px;
            }

            .history-target-question {
                font-size: 24px;
            }

            .history-summary-line {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
            }

            .history-summary-content {
                flex: 1 1 auto;
                min-width: 0;
            }

            .history-summary-text {
                font-size: 18px;
            }

            .history-body {
                padding: 20px;
            }
        }

        @media (max-width: 640px) {
            .history-page-title {
                font-size: 24px;
            }

            .history-target-question {
                font-size: 18px;
            }

            .history-summary {
                padding: 16px;
            }

            .history-summary-text {
                font-size: 17px;
            }

            .history-summary-side {
                width: 100%;
            }

            .history-summary-side .row-action-button {
                flex: 1 1 auto;
                justify-content: center;
            }

            .history-body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<div class="app">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">変更履歴</p>
                    <p class="brand-sub">FAQの更新内容を時系列で確認できます</p>
                </div>
            </div>

            <div class="topbar-actions">
                <a href="{{ route('faqs.index') }}" class="header-sub-button">一覧へ戻る</a>
            </div>
        </div>
    </header>

    <main class="category-layout-single">
        <section class="content">

            <div class="search-panel history-page-head">
                <h1 class="history-page-title">変更履歴</h1>
                <p class="history-page-sub">対象FAQの過去の内容を確認できます。</p>

                <div class="history-target-inline">
                    <div>
                        <div class="history-target-kicker">対象FAQ</div>
                        <div class="history-target-main">
                            <h2 class="history-target-question">{{ $faq->question }}</h2>

                            <div class="history-meta-row">
                                @if(!empty($faq->category1_name))
                                    <span class="tag">{{ $faq->category1_name }}</span>
                                @endif
                                @if(!empty($faq->category2_name))
                                    <span class="tag">{{ $faq->category2_name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($histories->count())
                <div class="history-list">
                    @foreach($histories as $history)
                        <article class="faq-card history-card">
                            <details class="history-details">
                                <summary class="history-summary">
                                    <div class="history-summary-main">
                                        <div class="history-summary-top">
                                            <span class="history-type-badge">
                                                @switch($history->action_type)
                                                    @case('created')
                                                        新規作成
                                                        @break
                                                    @case('deleted')
                                                        削除前
                                                        @break
                                                    @default
                                                        更新
                                                @endswitch
                                            </span>

                                            <span class="history-date">
                                                {{ optional($history->updated_at ?? $history->created_at)->format('Y-m-d H:i:s') }}
                                            </span>
                                        </div>

                                        <div class="history-summary-line">
                                            <div class="history-summary-content">
                                                <span class="history-inline-label">更新内容</span>
                                                <p class="history-summary-text {{ empty($history->change_summary) ? 'is-empty' : '' }}">
                                                    {{ $history->change_summary ?: '変更内容なし' }}
                                                </p>
                                            </div>

                                            <div class="history-summary-side">
                                                <button
                                                    type="button"
                                                    class="row-action-button delete js-delete-open"
                                                    data-id="{{ $history->id }}"
                                                    data-name="{{ $history->question }}"
                                                    data-action="{{ route('faq-histories.destroy', $history->id) }}"
                                                    onclick="event.preventDefault(); event.stopPropagation();"
                                                >
                                                    履歴削除
                                                </button>

                                                <span class="history-toggle" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                </summary>

                                <div class="history-body">
                                    <div class="history-section">
                                        <div class="history-section-label">カテゴリ</div>
                                        <div class="faq-meta">
                                            @if(!empty($history->category1_name))
                                                <span class="tag">{{ $history->category1_name }}</span>
                                            @endif
                                            @if(!empty($history->category2_name))
                                                <span class="tag">{{ $history->category2_name }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if(!empty($history->change_summary))
                                        <div class="history-section">
                                            <div class="history-section-label">変更内容</div>
                                            <div class="block-text history-pre">{{ $history->change_summary }}</div>
                                        </div>
                                    @endif

                                    <div class="history-section">
                                        <div class="history-section-label">質問</div>
                                        <h2 class="faq-question">{{ $history->question }}</h2>
                                    </div>

                                    <div class="answer-block">
                                        <p class="block-title">回答</p>
                                        <p class="block-text history-pre">{{ $history->answer }}</p>
                                    </div>

                                    @if(!empty($history->note))
                                        <div class="note-block">
                                            <p class="block-title">あわせて確認</p>
                                            <p class="block-text history-pre">{{ $history->note }}</p>
                                        </div>
                                    @endif

                                    @if(!empty($history->url))
                                        <div class="link-block">
                                            <p class="block-title">参考リンク</p>
                                            <ul class="link-list">
                                                <li>
                                                    <a href="{{ $history->url }}" target="_blank" rel="noopener noreferrer">
                                                        {{ $history->url }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty">
                    履歴はありません。
                </div>
            @endif

        </section>
    </main>
</div>

<div id="deleteModal" class="delete-modal hidden">
    <div class="delete-modal-backdrop"></div>
    <div class="delete-modal-card">
        <p class="delete-modal-title">履歴を削除しますか？</p>
        <p class="delete-modal-text">
            「<span id="deleteItemName"></span>」を削除します。<br>
            この操作は取り消せません。
        </p>

        <div class="delete-modal-actions">
            <button type="button" id="deleteModalClose" class="modal-cancel-button">キャンセル</button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-delete-button">削除する</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>