<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Faq;
use App\Models\FaqHistory;

class FaqHistoryController extends Controller
{
    public function histories($id)
    {

        // FAQを取得
        $faq = Faq::findOrFail($id);

        // カテゴリ名取得
        $categories = Category::pluck('name', 'id');
        
        // 表示用のカテゴリ名を形成
        $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
        $faq['category2_name'] = !empty($faq['category2_id'])
        ? ($categories[$faq['category2_id']] ?? '')
        : '';
        
        // 履歴を取得
        $histories = FaqHistory::where('faq_id', $id)->orderBy('id', 'desc')->get();

        foreach($histories as $historiesFaq){

            // カテゴリ名取得
            $categories = Category::pluck('name', 'id');
            
            // 表示用のカテゴリ名を形成
            $historiesFaq['category1_name'] = $categories[$historiesFaq['category1_id']] ?? '';
            $historiesFaq['category2_name'] = !empty($historiesFaq['category2_id'])
            ? ($categories[$historiesFaq['category2_id']] ?? '')
            : '';
        }

        return view('faqs.histories.index', compact('faq','histories'));
    }

    public function destroyHistory($id)
    {
        $history = FaqHistory::findOrFail($id);
        $faqId = $history->faq_id;

        $history->delete();

        return redirect()->route('faqs.histories.index', $faqId);
    }
}

