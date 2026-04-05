<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>変更履歴</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        .history-target-card {
            margin-top: 14px;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            background: #fff;
        }

        .history-target-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--sub);
        }

        .history-target-question {
            margin: 8px 0 0;
            font-size: 20px;
            line-height: 1.5;
            letter-spacing: 0.01em;
        }

        .history-target-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
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
            padding: 16px;
        }

        .history-summary-main {
            min-width: 0;
        }

        .history-summary-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .history-type-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-deep);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .history-date {
            font-size: 12px;
            color: var(--sub);
            white-space: nowrap;
        }

        .history-question {
            margin: 0;
            font-size: 17px;
            line-height: 1.55;
            letter-spacing: 0.01em;
        }

        .history-inline-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 14px;
            margin-top: 10px;
        }

        .history-inline-item {
            min-width: 0;
        }

        .history-inline-label {
            display: block;
            margin-bottom: 4px;
            font-size: 11px;
            font-weight: 700;
            color: var(--sub);
            letter-spacing: 0.02em;
        }

        .history-inline-value {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text);
            word-break: break-word;
        }

        .history-inline-value.is-summary {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .history-summary-side {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
        }

        .history-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #fff;
            color: var(--sub);
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
            transition: 0.2s ease;
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
            padding: 16px;
            border-top: 1px solid var(--line-soft);
            background: #fcfdff;
        }

        .history-details[open] .history-body {
            display: block;
        }

        .history-section + .history-section {
            margin-top: 14px;
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

        .history-empty-summary {
            color: #9ca3af;
        }

        @media (min-width: 961px) {
            .history-target-question {
                font-size: 24px;
            }

            .history-summary {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 14px;
                padding: 18px 20px;
            }

            .history-summary-side {
                width: auto;
                flex-shrink: 0;
                justify-content: flex-end;
            }

            .history-body {
                padding: 20px;
            }

            .history-inline-meta {
                gap: 18px;
            }

            .history-inline-item {
                max-width: 360px;
            }
        }

        @media (max-width: 640px) {
            .history-target-question {
                font-size: 18px;
            }

            .history-summary-side {
                align-items: stretch;
            }

            .history-summary-side .row-action-button {
                flex: 1 1 auto;
                justify-content: center;
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
            <div class="search-panel">
                <div class="search-copy">
                    <h1 class="search-heading">変更履歴</h1>
                    <p class="search-sub">対象FAQの過去の内容を確認できます。</p>
                </div>

                <div class="history-target-card">
                    <div class="history-target-label">対象FAQ</div>
                    <h2 class="history-target-question">{{ $faq->question }}</h2>

                    <div class="history-target-meta">
                        @if(!empty($faq->category1_name))
                            <span class="tag">{{ $faq->category1_name }}</span>
                        @endif

                        @if(!empty($faq->category2_name))
                            <span class="tag">{{ $faq->category2_name }}</span>
                        @endif
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

                                        <h2 class="history-question">{{ $history->question }}</h2>

                                        <div class="history-inline-meta">
                                            <div class="history-inline-item">
                                                <span class="history-inline-label">更新内容</span>
                                                <div class="history-inline-value is-summary {{ empty($history->change_summary) ? 'history-empty-summary' : '' }}">
                                                    {{ $history->change_summary ?: '変更内容の要約はありません' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="history-summary-side">
                                        <button
                                            type="button"
                                            class="row-action-button delete js-history-delete-open"
                                            data-id="{{ $history->id }}"
                                            data-name="{{ $history->question }}"
                                            data-action="{{ route('faq-histories.destroy', $history->id) }}"
                                            onclick="event.preventDefault(); event.stopPropagation();"
                                        >
                                            履歴削除
                                        </button>

                                        <span class="history-toggle" aria-hidden="true"></span>
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

{{-- 削除モーダル --}}
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