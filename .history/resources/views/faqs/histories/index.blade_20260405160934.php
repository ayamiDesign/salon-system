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
            <div class="history-page-head search-panel">
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

            @if(session('status'))
                <div class="history-flash">
                    {{ session('status') }}
                </div>
            @endif

            @if($histories->count())
                <div class="faq-list">
                    @foreach($histories as $history)
                        <article class="faq-card history-card">
                            <div class="history-card-head">
                                <div class="history-card-head-main">
                                    <div class="history-meta">
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
                                            {{ optional($history->recorded_at)->format('Y-m-d H:i:s') ?? optional($history->created_at)->format('Y-m-d H:i:s') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="faq-action-group">
                                    <form action="{{ route('faq-histories.destroy', $history->id) }}" method="POST" onsubmit="return confirm('この履歴を削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="row-action-button delete">履歴削除</button>
                                    </form>
                                </div>
                            </div>

                            <div class="history-body-visible">
                                <div class="history-section">
                                    <div class="history-section-label">カテゴリ</div>
                                    <div class="faq-meta">
                                        @if(!empty($history->category1_name))
                                            <span class="tag">{{ $history->category1_name }}</span>
                                        @elseif(!empty($history->category1_id))
                                            <span class="tag">カテゴリ1 ID: {{ $history->category1_id }}</span>
                                        @endif

                                        @if(!empty($history->category2_name))
                                            <span class="tag">{{ $history->category2_name }}</span>
                                        @elseif(!empty($history->category2_id))
                                            <span class="tag">カテゴリ2 ID: {{ $history->category2_id }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="history-section">
                                    <div class="history-section-label">質問</div>
                                    <h2 class="faq-question history-question">{{ $history->question }}</h2>
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

                                @if(!empty($history->pdf))
                                    <div class="link-block">
                                        <p class="block-title">PDF</p>
                                        <p class="block-text history-pre">{{ $history->pdf }}</p>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="history-pagination">
                    {{ $histories->links() }}
                </div>
            @else
                <div class="empty">
                    履歴はまだありません。
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>