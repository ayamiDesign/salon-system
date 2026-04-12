<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqHistory;
use Illuminate\Database\Seeder;

class FaqHistoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = Faq::take(12)->get();

        foreach ($faqs as $faq) {
            FaqHistory::create([
                'faq_id' => $faq->id,
                'category1_id' => $faq->category1_id,
                'category2_id' => $faq->category2_id,
                'question' => $faq->question,
                'answer' => $faq->answer . ' 旧ルールでは一部案内内容が異なっていました。',
                'note' => $faq->note,
                'pdf' => $faq->pdf,
                'pdf_original_name' => $faq->pdf_original_name,
                'url' => $faq->url,
                'change_summary' => '案内文を現場運用に合わせて調整',
                'sort_order' => $faq->sort_order,
                'is_visible' => $faq->is_visible,
            ]);

            FaqHistory::create([
                'faq_id' => $faq->id,
                'category1_id' => $faq->category1_id,
                'category2_id' => $faq->category2_id,
                'question' => $faq->question,
                'answer' => $faq->answer . ' 更新前は表現が簡略的だったため、説明を追記しました。',
                'note' => $faq->note,
                'pdf' => $faq->pdf,
                'pdf_original_name' => $faq->pdf_original_name,
                'url' => $faq->url,
                'change_summary' => '回答文の表現を見直し',
                'sort_order' => $faq->sort_order,
                'is_visible' => $faq->is_visible,
            ]);
        }
    }
}