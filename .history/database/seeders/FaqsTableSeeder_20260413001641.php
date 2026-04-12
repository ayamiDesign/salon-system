<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $data = [
            [
                'category1_id' => $categories['接客'] ?? null,
                'category2_id' => $categories['受付'] ?? null,
                'question' => 'お客様が早く来た場合の対応は？',
                'answer' => '受付後、待合スペースへ案内します。',
                'note' => '混雑時は時間を伝える',
                'pdf' => null,
                'pdf_original_name' => null,
                'url' => null,
                'sort_order' => 1,
                'is_visible' => 1,
            ],
            [
                'category1_id' => $categories['施術'] ?? null,
                'category2_id' => null,
                'question' => '施術前に確認することは？',
                'answer' => '体調・希望部位を確認',
                'note' => 'アレルギー注意',
                'pdf' => null,
                'pdf_original_name' => null,
                'url' => null,
                'sort_order' => 2,
                'is_visible' => 1,
            ],
            [
                'category1_id' => $categories['会計'] ?? null,
                'category2_id' => null,
                'question' => 'レジ締めの手順は？',
                'answer' => '営業終了後に確認',
                'note' => null,
                'pdf' => null,
                'pdf_original_name' => null,
                'url' => null,
                'sort_order' => 3,
                'is_visible' => 0,
            ],
        ];

        foreach ($data as $row) {
            Faq::updateOrCreate(
                ['question' => $row['question']],
                $row
            );
        }
    }
}