{{-- resources/views/faq-demo.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ検索デモ</title>

<style>
/* ★ここはあなたの元CSSそのまま（変更なし） */
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
body{margin:0;font-family:sans-serif;background:var(--bg);}
.topbar{background:#fff;border-bottom:1px solid var(--line);}
.topbar-inner{display:flex;justify-content:space-between;align-items:center;padding:14px 20px;}
.layout{display:grid;grid-template-columns:280px 1fr;gap:24px;padding:20px;}
.faq-list{display:grid;gap:14px;}
.faq-card{background:#fff;border:1px solid var(--line);border-radius:12px;}
.faq-head{padding:18px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;}
.faq-body{display:none;padding:18px;border-top:1px solid var(--line);}
.faq-card.is-open .faq-body{display:block;}
</style>
</head>

<body>

@php
$faqs = [
    ['id'=>1,'category'=>'予約','question'=>'施術順は？','summary'=>'基本ルール','answer'=>'早く終わった人優先'],
    ['id'=>2,'category'=>'予約','question'=>'ブロックは？','summary'=>'予約扱い','answer'=>'予約として扱う'],
];
@endphp

<div x-data="faqApp(@js($faqs))">

<!-- =======================
  ヘッダー
======================= -->
<header class="topbar">
    <div class="topbar-inner">
        <strong>FAQ</strong>

        <!-- ★追加：新規登録 -->
        <button
            style="border:1px solid #e5e7eb; background:#2563eb; color:#fff; padding:8px 14px; border-radius:10px; font-size:13px; cursor:pointer;"
            @click="createFaq()"
        >
            ＋ 新規登録
        </button>
    </div>
</header>

<main class="layout">

    <aside>カテゴリ</aside>

    <section>
        <div class="faq-list">

            <template x-for="faq in faqs" :key="faq.id">
                <div class="faq-card" :class="{'is-open':openId===faq.id}">

                    <!-- =======================
                      FAQヘッダー
                    ======================= -->
                    <div class="faq-head" @click="toggle(faq.id)">

                        <div>
                            <strong x-text="faq.question"></strong>
                        </div>

                        <!-- ★追加：変更・削除 -->
                        <div style="display:flex; gap:6px;" @click.stop>

                            <button
                                style="border:1px solid #e5e7eb; background:#fff; padding:6px 10px; border-radius:8px; font-size:12px; cursor:pointer;"
                                @click="editFaq(faq)"
                            >
                                変更
                            </button>

                            <button
                                style="border:1px solid #fecaca; background:#fff; color:#dc2626; padding:6px 10px; border-radius:8px; font-size:12px; cursor:pointer;"
                                @click="deleteFaq(faq.id)"
                            >
                                削除
                            </button>

                        </div>

                    </div>

                    <div class="faq-body">
                        <p x-text="faq.answer"></p>
                    </div>

                </div>
            </template>

        </div>
    </section>

</main>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
function faqApp(faqs){
    return {
        faqs: faqs,
        openId: null,

        toggle(id){
            this.openId = this.openId === id ? null : id;
        },

        // 新規
        createFaq(){
            alert('新規登録');
        },

        // 編集
        editFaq(faq){
            alert('編集: ' + faq.id);
        },

        // 削除
        deleteFaq(id){
            if(confirm('削除しますか？')){
                this.faqs = this.faqs.filter(f => f.id !== id);
            }
        }
    }
}
</script>

</body>
</html>