<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <a href="{{ url('/faqs') }}" class="brand-link">
                <div class="brand-badge">FAQ</div>
                <div class="brand-text">
                    <p class="brand-title">FAQ検索システム</p>
                    <p class="brand-sub">業務チェック＆困ったときガイド</p>
                </div>
            </a>
        </div>

        {{-- PC用グローバルメニュー --}}
        <nav class="global-nav pc-only" aria-label="グローバルメニュー">
            <ul class="global-nav-list">
                <li>
                    <a
                        href="{{ route('faqs.index') }}"
                        class="global-nav-link {{ request()->routeIs('faqs.*') ? 'is-active' : '' }}"
                    >
                        FAQ一覧
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                    <li>
                        <a
                            href="{{ route('categories.index') }}"
                            class="global-nav-link {{ request()->routeIs('categories.*') ? 'is-active' : '' }}"
                        >
                            カテゴリ管理
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('users.index') }}"
                            class="global-nav-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}"
                        >
                            アカウント管理
                        </a>
                    </li>
                @endif
                <li>
                    <form method="post" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="global-nav-link logout-link">
                            ログアウト
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        @if(request()->routeIs('faqs.index')&& auth()->user()->role === 'admin')
            <div class="topbar-actions">
                <button
                    type="button"
                    id="cancelSortButton"
                    class="action-button action-button-sub hidden"
                >
                    キャンセル
                </button>

                <button
                    type="button"
                    id="saveSortButton"
                    class="action-button action-button-primary hidden"
                >
                    保存する
                </button>

                <button
                    type="button"
                    id="sortModeButton"
                    class="header-sub-button"
                >
                    並び替え
                </button>

                <form
                    id="csvImportForm"
                    action="{{ route('faqs.import.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    style="display: inline;"
                >
                    @csrf

                    <input
                        type="file"
                        name="csv_file"
                        id="csv_file"
                        accept=".csv"
                        style="display: none;"
                    >

                    <button
                        type="button"
                        class="header-import-button"
                        id="csvImportButton"
                    >
                        CSVインポート
                    </button>
                </form>

                <a
                    href="{{ route('faqs.create') }}"
                    class="header-main-button"
                >
                    新規登録
                </a>

                {{-- スマホ用メニューボタン --}}
                <button
                    type="button"
                    class="menu-toggle sp-only"
                    aria-controls="mobileGlobalMenu"
                    aria-expanded="false"
                    aria-label="メニューを開く"
                    data-menu-toggle
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        @elseif(request()->routeIs('categories.index')&& auth()->user()->role === 'admin')
         <div class="topbar-actions">
                <button
                    type="button"
                    id="cancelSortButton"
                    class="action-button action-button-sub hidden"
                >
                    キャンセル
                </button>

                <button
                    type="button"
                    id="saveSortButton"
                    class="action-button action-button-primary hidden"
                >
                    保存する
                </button>

                <button
                    type="button"
                    id="sortModeButton"
                    class="header-sub-button"
                >
                    並び替え
                </button>

                <a
                    href="{{ route('categories.create') }}"
                    class="header-main-button"
                >
                    新規登録
                </a>

                {{-- スマホ用メニューボタン --}}
                <button
                    type="button"
                    class="menu-toggle sp-only"
                    aria-controls="mobileGlobalMenu"
                    aria-expanded="false"
                    aria-label="メニューを開く"
                    data-menu-toggle
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        @elseif(request()->routeIs('users.index'))
         <div class="topbar-actions">
                <a
                    href="{{ route('users.create') }}"
                    class="header-main-button"
                >
                    新規登録
                </a>

                {{-- スマホ用メニューボタン --}}
                <button
                    type="button"
                    class="menu-toggle sp-only"
                    aria-controls="mobileGlobalMenu"
                    aria-expanded="false"
                    aria-label="メニューを開く"
                    data-menu-toggle
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        @endif
    </div>

    {{-- スマホ用グローバルメニュー --}}
    <div id="mobileGlobalMenu" class="mobile-global-menu sp-only" hidden>
        <nav class="mobile-global-nav" aria-label="スマホ用グローバルメニュー">
            <ul class="mobile-global-nav-list">
                <li>
                    <a
                        href="{{ route('faqs.index') }}"
                        class="mobile-global-nav-link {{ request()->routeIs('faqs.index') ? 'is-active' : '' }}"
                    >
                        FAQ一覧
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                     <li>
                        <a
                            href="{{ route('categories.index') }}"
                            class="mobile-global-nav-link {{ request()->routeIs('categories.*') ? 'is-active' : '' }}"
                        >
                            カテゴリ管理
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('users.index') }}"
                            class="mobile-global-nav-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}"
                        >
                            アカウント管理
                        </a>
                    </li>
                @endif
                <li>
                    <form method="post" action="{{ route('logout') }}" class="mobile-inline-form">
                        @csrf
                        <button type="submit" class="mobile-global-nav-link mobile-global-nav-button">
                            ログアウト
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</header>