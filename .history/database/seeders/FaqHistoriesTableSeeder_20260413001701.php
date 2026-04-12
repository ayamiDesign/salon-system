<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqHistory;
use Illuminate\Database\Seeder;

class FaqHistoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faq = Faq::first();

        if (!$faq) return;

        FaqHistory::create([
            'faq_id' => $faq->id,
            'category1_id' => $faq->category1_id,
            'category2_id' => $faq->category2_id,
            'question' => $faq->question,
            'answer' => $faq->answer,
            'note' => $faq->note,
            'pdf' => $faq->pdf,
            'pdf_original_name' => $faq->pdf_original_name,
            'url' => $faq->url,
            'change_summary' => '初期登録',
            'sort_order' => $faq->sort_order,
            'is_visible' => $faq->is_visible,
        ]);
    }
}