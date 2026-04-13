<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Faq;
use App\Models\FaqHistory;

class FaqController extends Controller
{

    public function index(Request $request)
    {

        // セッションを削除
        $request->session()->forget(['_old_input',]);

        // ユーザーロールを取得
        $user = Auth::user();

        // カテゴリを取得
        $categoriesList = Category::orderBy('sort_order')->get();

        $searchCategory = $request->input('category', '0');
        $searchKeyword = $request->input('keyword', '');

        if ($user->role !== 'admin') {
            $faqCount = Faq::where('is_visible', 1)->count();

            $faqs = Faq::search($searchCategory, $searchKeyword)
                ->where('is_visible', 1)
                ->paginate(20)
                ->appends($request->query());
        } else {
            $faqCount = Faq::count();

            $faqs = Faq::search($searchCategory, $searchKeyword)
                ->paginate(20)
                ->appends($request->query());
        }
      
        // カテゴリごとのFAQ件数を取得
        foreach ($categoriesList as $index => $category) {
            if ($user->role !== 'admin') {
                $count = Faq::categoryMatch($category->id)->where('is_visible', 1)->count();
                $category['count'] = $count;
            } else {
                $count = Faq::categoryMatch($category->id)->count();
                $category['count'] = $count;
            }
        }

        // 表示用のカテゴリ名を形成
        $categories = $categoriesList->pluck('name', 'id');
        foreach ($faqs as $faq) {

            $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
            $faq['category2_name'] = !empty($faq['category2_id'])
            ? ($categories[$faq['category2_id']] ?? '')
            : '';
        }

        return view('faqs.index',
            compact(
                'faqs',
                'faqCount',
                'categoriesList',
                'searchCategory',
                'searchKeyword',
            )
        );
    }

    public function back(Request $request)
    {
        return redirect()
            ->route('faqs.create')
            ->withInput();
    }

    public function create()
    {
        // カテゴリを取得
        $categories = Category::orderBy('sort_order')->get();

        return view('faqs.create',compact('categories'));
    }

    public function confirm(Request $request)
    {

        // バリデーション
        $rules = [
            'faqs' => ['required', 'array', 'min:1'],

            'faqs.*.category1_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'faqs.*.category2_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                'different:faqs.*.category1_id',
            ],

            'faqs.*.question' => [
                'required',
                'string',
                'distinct',
                Rule::unique('faqs', 'question')->whereNull('deleted_at'),
            ],

            'faqs.*.answer' => ['required', 'string'],
            'faqs.*.note' => ['nullable', 'string'],
            'faqs.*.url' => ['nullable', 'url'],
            'faqs.*.is_visible' => ['nullable', 'boolean'],
        ];

        $messages = [
            'faqs.required' => 'FAQを1件以上入力してください',
            'faqs.*.category1_id.required' => ':attributeは必須です',
            'faqs.*.category1_id.exists' => ':attributeの値が不正です',
            'faqs.*.category2_id.exists' => ':attributeの値が不正です',
            'faqs.*.category2_id.different' => ':attributeはカテゴリ（メイン）と同じにできません',
            'faqs.*.question.required' => ':attributeは必須です',
            'faqs.*.question.distinct' => ':attributeが重複しています',
            'faqs.*.question.unique' => ':attributeは既に存在します',
            'faqs.*.answer.required' => ':attributeは必須です',
            'faqs.*.note.string' => ':attributeは文字列で入力してください',
            'faqs.*.url.url' => ':attributeの形式が正しくありません',
        ];

        $attributes = [];

        foreach ($request->input('faqs', []) as $index => $faq) {
            $no = $index + 1;

            $attributes["faqs.$index.category1_id"] = "FAQ {$no} のカテゴリ（メイン）";
            $attributes["faqs.$index.category2_id"] = "FAQ {$no} のカテゴリ（サブ）";
            $attributes["faqs.$index.question"] = "FAQ {$no} の質問";
            $attributes["faqs.$index.answer"] = "FAQ {$no} の回答";
            $attributes["faqs.$index.note"] = "FAQ {$no} のあわせて確認";
            $attributes["faqs.$index.url"] = "FAQ {$no} の参考URL";
        }

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        $requestData = $validator->validate();

        // データを形成
        $faqs = $requestData['faqs'];

        // カテゴリ名取得
        $categories = Category::pluck('name', 'id');
        
        foreach ($faqs as $index => &$faq) {

            // 表示用のカテゴリ名を形成
            $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
            $faq['category2_name'] = !empty($faq['category2_id'])
            ? ($categories[$faq['category2_id']] ?? '')
            : '';
        }

        //参照を切る
        // unset($faq);

        return view('faqs.confirm', [
            'mode' => 'create',
            'faqs' => $faqs,
        ]);

    }

    public function store(Request $request)
    {
        // バリデーション
        $requestData = $request->validate([
            'faqs' => ['required', 'array', 'min:1'],

            'faqs.*.category1_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'faqs.*.category2_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                'different:faqs.*.category1_id',
            ],

            'faqs.*.question' => [
                'required',
                'string',
                'distinct',
                Rule::unique('faqs', 'question')->whereNull('deleted_at'),
            ],
            'faqs.*.answer' => ['required', 'string'],
            'faqs.*.note' => ['nullable', 'string'],
            'faqs.*.url' => ['nullable', 'url'],
            'faqs.*.is_visible' => ['nullable', 'boolean'],
        ], [
            'faqs.required' => 'FAQを1件以上入力してください',
            'faqs.*.category1_id.required' => 'カテゴリ（メイン）は必須です',
            'faqs.*.category1_id.exists' => 'カテゴリ（メイン）の値が不正です',
            'faqs.*.category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
            'faqs.*.category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）に同じものは選べません',
            'faqs.*.question.required' => '質問は必須です',
            'faqs.*.question.distinct' => '同じ質問が入力されています',
            'faqs.*.question.unique' => '同じ質問が既に存在します',
            'faqs.*.answer.required' => '回答は必須です',
            'faqs.*.note.string' => 'あわせて確認は文字列で入力してください',
            'faqs.*.url.url' => 'URLの形式が正しくありません',
        ]);

        foreach ($requestData['faqs'] as $faq) {

            // FAQ保存
            $FaqModel = Faq::create([
                'category1_id' => $faq['category1_id'],
                'category2_id' => $faq['category2_id'],
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'note' => $faq['note'],
                'url' => $faq['url'],
                'is_visible' => $faq['is_visible'],
            ]);

            // 表示順保存
            $FaqModel->update([
                'sort_order' => $FaqModel->id
            ]);
        }

        // セッションを削除
        $request->session()->forget(['_old_input',]);
        
        // 二重送信を防ぐためリダイレクト
        return redirect()->route('faqs.complete');
    }

    public function editBack(Request $request, $id)
    {
        return redirect()
            ->route('faqs.edit', $id)
            ->withInput();
    }

    public function edit($id)
    {
        // データを取得する
        $faq = Faq::findOrFail($id);

        // カテゴリを取得
        $categoriesList = Category::orderBy('sort_order')->get();

        // カテゴリ名取得
        $categories = Category::pluck('name', 'id');
        
        // 表示用のカテゴリ名を形成
        $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
        $faq['category2_name'] = !empty($faq['category2_id'])
        ? ($categories[$faq['category2_id']] ?? '')
        : '';

        return view('faqs.edit',compact('faq','categoriesList'));
    }

    public function confirmEdit(Request $request, $id)
    {

        // バリデーション
        $faq = Faq::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'category1_id' => [
                    'required', 
                    'integer', 
                    'exists:categories,id'
                ],
                'category2_id' => [
                    'nullable', 
                    'integer', 
                    'exists:categories,id', 
                    'different:category1_id'
                ],
                'question' => [
                    'required',
                    'string',
                    Rule::unique('faqs', 'question')
                        ->ignore($id)
                        ->whereNull('deleted_at'),
                ],
                'answer' => ['required', 'string'],
                'note' => ['nullable', 'string'],
                'url' => ['nullable', 'url'],
                'is_visible' => ['nullable', 'boolean'],

                'faq_history' => ['nullable', 'boolean'],
                'change_summary' => [ 
                    'nullable',
                    'required_if:faq_history,1',
                    'prohibited_unless:faq_history,1', 
                    'string', 
                    'max:25'
                ],
            ],
            [
                'category1_id.required' => 'カテゴリ（メイン）は必須です',
                'category1_id.integer' => 'カテゴリ（メイン）の値が不正です',
                'category1_id.exists' => 'カテゴリ（メイン）の値が不正です',

                'category2_id.integer' => 'カテゴリ（サブ）の値が不正です',
                'category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
                'category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）に同じものは選べません',

                'question.required' => '質問は必須です',
                'question.unique' => '同じ質問が既に存在します',
                'answer.required' => '回答は必須です',
                'url.url' => 'URLの形式が正しくありません',

                'change_summary.required_if' => '変更メモは必須です',
                'change_summary.prohibited_unless' => '変更履歴を残さない場合、変更メモは入力できません',
                'change_summary.max' => '変更メモは25文字以内で入力してください',
            ]
        );

        $requestData = $validator->validate();

        $categoriesList = Category::select('id', 'name')->get();

        $requestData['category1_name'] = optional(
            $categoriesList->firstWhere('id', $requestData['category1_id'])
        )->name ?? '-';

        $requestData['category2_name'] = !empty($requestData['category2_id'])
            ? optional($categoriesList->firstWhere('id', $requestData['category2_id']))->name
            : null;

        return view('faqs.confirm', [
            'mode' => 'edit',
            'id' => $faq->id,
            'faq' => $faq,
            'requestData' => $requestData,
        ]);

    }

    public function update(Request $request, $id)
    {
     
        $faq = Faq::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'category1_id' => [
                    'required', 
                    'integer', 
                    'exists:categories,id'
                ],
                'category2_id' => [
                    'nullable', 
                    'integer', 
                    'exists:categories,id', 
                    'different:category1_id'
                ],
                'question' => [
                    'required',
                    'string',
                    Rule::unique('faqs', 'question')
                        ->ignore($id)
                        ->whereNull('deleted_at'),
                ],
                'answer' => ['required', 'string'],
                'note' => ['nullable', 'string'],
                'url' => ['nullable', 'url'],
                'is_visible' => ['nullable', 'boolean'],

                'faq_history' => ['nullable', 'boolean'],
                'change_summary' => [ 'nullable','required_if:faq_history,1','prohibited_unless:faq_history,1', 'string', 'max:25'],
            ],
            [
                'category1_id.required' => 'カテゴリ（メイン）は必須です',
                'category1_id.integer' => 'カテゴリ（メイン）の値が不正です',
                'category1_id.exists' => 'カテゴリ（メイン）の値が不正です',

                'category2_id.integer' => 'カテゴリ（サブ）の値が不正です',
                'category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
                'category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）に同じものは選べません',

                'question.required' => '質問は必須です',
                'question.unique' => '同じ質問が既に存在します',
                'answer.required' => '回答は必須です',
                'url.url' => 'URLの形式が正しくありません',

                'change_summary.required_if' => '変更メモは必須です',
                'change_summary.prohibited_unless' => '変更履歴を残さない場合、変更メモは入力できません',
                'change_summary.max' => '変更メモは25文字以内で入力してください',
            ]
        );

        $requestData = $validator->validate();

            if (!empty($requestData['faq_history'])) {
                FaqHistory::create([
                    'faq_id' => $faq->id,

                    'category1_id' => $faq->category1_id,
                    'category2_id' => $faq->category2_id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'note' => $faq->note,
                    'url' => $faq->url,
                    'is_visible' => $faq->is_visible,
                    'change_summary' => $requestData['change_summary']
                ]);
            }

            $faq->update([
                'category1_id' => $requestData['category1_id'],
                'category2_id' => $requestData['category2_id'] ?: null,
                'question' => $requestData['question'],
                'answer' => $requestData['answer'],
                'note' => $requestData['note'] ?? null,
                'url' => $requestData['url'] ?? null,
                'is_visible' => !empty($requestData['is_visible']) ? 1 : 0,
            ]);

            DB::commit();

            // セッションを削除
            $request->session()->forget(['_old_input',]);


            return redirect()->route('faqs.complete');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);

        // 履歴削除
        FaqHistory::where('faq_id', $id)->delete();

        // FAQ削除
        $faq->delete();

        return redirect()->route('faqs.index');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:faqs,id'],
        ]);

        foreach ($request->ids as $index => $id) {
            Faq::where('id', $id)->update([
                'sort_order' => $index + 1
            ]);
        }

        return response()->json([
            'message' => '表示順を保存しました'
        ]);
    }
}