<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Faqs;
use App\Models\FaqHistories;

class FaqHistoriesController extends Controller
{
    public function histories($id)
    {

        // FAQを取得
        $faq = Faqs::findOrFail($id);

        // カテゴリ名取得
        $categories = Categories::pluck('name', 'id');
        
        // 表示用のカテゴリ名を形成
        $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
        $faq['category2_name'] = !empty($faq['category2_id'])
        ? ($categories[$faq['category2_id']] ?? '')
        : '';
        
        // 履歴を取得
        $histories = FaqHistories::where('faq_id', $id)->orderBy('id', 'desc')->get();

        foreach($histories as $faq){

            // カテゴリ名取得
            $categories = Categories::pluck('name', 'id');
            
            // 表示用のカテゴリ名を形成
            $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
            $faq['category2_name'] = !empty($faq['category2_id'])
            ? ($categories[$faq['category2_id']] ?? '')
            : '';
        }

        return view('faqs.histories.index', compact('faq','histories'));
    }

    public function destroy(FaqHistories $history)
    {
        $faqId = $history->faq_id;

        $history->delete();

        return redirect()
            ->route('faqs.histories.index', $faqId)
            ->with('status', '履歴を削除しました。');
    }
}

