<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaqHistoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('faq_histories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faqs = Faq::take(15)->get();

        $changePatterns = [
            [
                'summary' => '回答文の表現を見直し',
                'answer_suffix' => ' 以前は説明が簡潔だったため、スタッフが案内しやすい表現に修正しました。',
                'note_suffix' => ' 口頭案内時は統一した言い回しを意識してください。',
            ],
            [
                'summary' => '現場運用に合わせて案内内容を更新',
                'answer_suffix' => ' 現在の店舗運用に合わせて、案内手順を一部更新しています。',
                'note_suffix' => ' 判断に迷う場合は当日の責任者へ確認してください。',
            ],
            [
                'summary' => '注意事項を追記',
                'answer_suffix' => ' あわせて、お客様への伝え方に関する注意点を追記しました。',
                'note_suffix' => ' 強い言い切りにならないよう配慮してください。',
            ],
            [
                'summary' => '新人スタッフ向けに補足説明を追加',
                'answer_suffix' => ' 初見でも理解しやすいように、補足説明を追加しています。',
                'note_suffix' => ' 研修中は先輩スタッフと認識を合わせてください。',
            ],
            [
                'summary' => 'クレーム予防の観点で表現を調整',
                'answer_suffix' => ' 誤解を招きにくいよう、お客様への案内文を調整しました。',
                'note_suffix' => ' 断定表現は避け、状況に応じて柔軟に案内してください。',
            ],
        ];

        $baseDate = Carbon::now()->subMonths(8);

        foreach ($faqs as $index => $faq) {
            // FAQごとに履歴件数を変える
            $historyCount = match (true) {
                $index < 5 => 3,
                $index < 10 => 2,
                default => 1,
            };

            for ($i = 0; $i < $historyCount; $i++) {
                $pattern = $changePatterns[array_rand($changePatterns)];

                $createdAt = (clone $baseDate)
                    ->addDays(($index * 9) + ($i * 35))
                    ->setTime(rand(9, 18), rand(0, 59), 0);

                $answer = $faq->answer . $pattern['answer_suffix'];

                $note = $faq->note
                    ? $faq->note . $pattern['note_suffix']
                    : ltrim($pattern['note_suffix']);

                if ($i === 0) {
                    $answer = $faq->answer . ' 更新前は店舗ごとの案内差があったため、基準表現を整理していました。';
                }

                if ($i === 1 && $faq->category1_id) {
                    $answer .= ' また、受付・施術担当間で共有しやすい内容に調整しています。';
                }

                if ($i === 2) {
                    $note .= ' 旧ルール参照時は最新版との差異に注意してください。';
                }

                FaqHistory::create([
                    'faq_id' => $faq->id,
                    'category1_id' => $faq->category1_id,
                    'category2_id' => $faq->category2_id,
                    'question' => $faq->question,
                    'answer' => $answer,
                    'note' => $note,
                    'pdf' => $faq->pdf,
                    'pdf_original_name' => $faq->pdf_original_name,
                    'url' => $faq->url,
                    'change_summary' => $pattern['summary'],
                    'sort_order' => $faq->sort_order,
                    'is_visible' => $i === 0 ? 0 : $faq->is_visible, 
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}