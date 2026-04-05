<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>変更履歴</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div
    class="app"
    data-delete-base-url="{{ url('/faq-histories') }}"
>
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