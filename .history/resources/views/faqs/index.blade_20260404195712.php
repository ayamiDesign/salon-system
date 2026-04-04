{{-- resources/views/faq-demo.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ検索デモ</title>
    <style>
        :root { --bg:#f6f7fb; --card:#fff; --line:#e5e7eb; --text:#1f2937; --sub:#6b7280; --accent:#2563eb; --accent-soft:#eff6ff; --success:#16a34a; --warn:#f59e0b; --danger:#dc2626; --shadow:0 10px 30px rgba(15,23,42,.08); }
        *{box-sizing:border-box}
        body{margin:0;font-family:sans-serif;background:var(--bg);color:var(--text)}
        .topbar{background:#fff;border-bottom:1px solid var(--line);padding:12px 20px;display:flex;justify-content:space-between;align-items:center}
        .layout{display:grid;grid-template-columns:260px 1fr;gap:20px;padding:20px}
        .sidebar,.hero,.faq-card{background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:var(--shadow)}
        .sidebar{padding:14px}
        .hero{padding:20px;margin-bottom:16px}
        .faq-list{display:grid;gap:12px}
        .faq-head{padding:16px;display:flex;justify-content:space-between;cursor:pointer}
        .faq-body{display:none;padding:16px;border-top:1px solid var(--line)}
        .faq-card.is-open .faq-body{display:block}
        .btn{border:1px solid var(--line);padding:8px 12px;border-radius:8px;font-size:13px;cursor:pointer}
        .btn-primary{background:var(--accent);color:#fff;border:none}
        .btn-edit{background:#fff;color:var(--accent)}
        .btn-delete{background:#fff;color:var(--danger)}
        .btn-row{display:flex;gap:8px}
        .top-actions{display:flex;gap:10px}
        @media(max-width:768px){.layout{grid-template-columns:1fr}.sidebar{display:none}}
    </style>
</head>
<body>

@php
$faqs=[
 ['id'=>1,'category'=>'予約','question'=>'施術順は？','answer'=>'施術終了が早い人優先'],
 ['id'=>2,'category'=>'予約','question'=>'ブロックは？','answer'=>'予約扱い'],
];
@endphp

<div x-data="faqApp(@js($faqs))">

    <div class="topbar">
        <div style="display:flex;justify-content:space-between;align-items:center;width:100%;">
            <strong>FAQ管理</strong>
            <button class="btn btn-primary" @click="createFaq()">＋ 新規登録</button>
        </div>
        <strong>FAQ管理</strong>
        <div class="top-actions">
            <!-- 新規登録ボタン（追加） -->
            <button class="btn btn-primary" @click="createFaq()">＋ 新規登録</button>
        </div>
    </div>

    <div class="layout">
        <div class="sidebar">
            <p><strong>カテゴリ</strong></p>
            <button class="btn" @click="selected='all'">すべて</button>
        </div>

        <div>
            <div class="hero">
                <input type="text" x-model="keyword" placeholder="検索" style="width:100%;height:40px;padding:8px;border:1px solid var(--line);border-radius:8px;">
            </div>

            <div class="faq-list">
                <template x-for="faq in filteredFaqs" :key="faq.id">
                    <div class="faq-card" :class="{'is-open':openId===faq.id}">
                        <div class="faq-head" @click="toggle(faq.id)">
                            <div>
                                <strong x-text="faq.question"></strong>
                            </div>

                            <!-- 変更・削除ボタン（追加のみ） -->
                            <div class="btn-row" @click.stop>
                                <button class="btn btn-edit" @click="editFaq(faq)">変更</button>
                                <button class="btn btn-delete" @click="deleteFaq(faq.id)">削除</button>
                            </div>
                        </div>

                        <div class="faq-body">
                            <p x-text="faq.answer"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function faqApp(faqs){
 return{
  faqs:faqs,
  keyword:'',
  openId:null,
  selected:'all',

  get filteredFaqs(){
    return this.faqs.filter(f=>{
      return f.question.includes(this.keyword)||f.answer.includes(this.keyword)
    })
  },

  toggle(id){this.openId=this.openId===id?null:id},

  // 新規
  createFaq(){
    alert('新規登録画面へ');
  },

  // 変更
  editFaq(faq){
    alert('編集: '+faq.id);
  },

  // 削除
  deleteFaq(id){
    if(confirm('削除しますか？')){
      this.faqs=this.faqs.filter(f=>f.id!==id)
    }
  }
 }
}
</script>

</body>
</html>
