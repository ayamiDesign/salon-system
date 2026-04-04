<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Faq;

class FaqController extends Controller
{

    public function index(Request $request)
    {
         // セッションを削除
        $request->session()->forget('faq_input');

        // $faqs = Faq::orderBy('sort_order')->get();
        // return view('faqs.index',compact('faqs'));
        return view('faqs.index');
    }

    public function create()
    {
        // カテゴリを取得
        $categories = Category::orderBy('sort_order')->get();

        // セッションを保存
        $sessionInput = session('faq_input');

        return view('faqs.create',compact('categories','sessionInput'));
    }

    public function confirm(Request $request)
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
                'different:category1_id',
            ],

            'faqs.*.question' => ['required', 'string','distinct'],
            'faqs.*.answer' => ['required', 'string'],
            'faqs.*.note' => ['nullable', 'string'],
            'faqs.*.url' => ['nullable', 'url'],
            'faqs.*.is_visible' => ['nullable', 'boolean'],
            'faqs.*.pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'faqs.required' => 'FAQを1件以上入力してください',
            'faqs.*.category1_id.required' => 'カテゴリ（メイン）は必須です',
            'faqs.*.category1_id.exists' => 'カテゴリ（メイン）の値が不正です',
            'faqs.*.category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
            'faqs.*.category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）に同じものは選べません',
            'faqs.*.question.required' => '質問は必須です',
            'faqs.*.question.distinct' => '同じ質問が入力されています',
            'faqs.*.answer.required' => '回答は必須です',
            'faqs.*.url.url' => 'URLの形式が正しくありません',
            'faqs.*.pdf.mimes' => 'PDFファイルのみアップロードできます',
            'faqs.*.pdf.max' => 'PDFファイルは10MB以下にしてください',
        ]);
        
        // PDF一時保存
        foreach ($requestData as $index => &$faq) {

            if ($request->hasFile("faqs.$index.pdf")) {
                $tempPath = $request->file("faqs.$index.pdf")->store('faq-temp', 'public');

                $faq['pdf_temp_path'] = $tempPath;
                $faq['pdf_original_name'] = $request->file("faqs.$index.pdf")->getClientOriginalName();
            } else {
                $faq['pdf_temp_path'] = null;
                $faq['pdf_original_name'] = null;
            }

        }

        // データを形成
        $faqs = $requestData['faqs'];

        // セッションに保存
        session(['faq_input' => $requestData]);

        return view('faqs.confirm', [
            'mode' => 'create',
            'categoryNames' => $faqs,
        ]);

    }

    public function store(Request $request)
    {
        // バリデーション
        // $requestData = $request->validate([
        //     'name' => 'required|array',
        //     'name.*' => 'required|string|max:255|distinct|unique:categories,name',
        // ],
        // [
        //     'name.*.required' => 'カテゴリ名を入力してください',
        //     'name.*.distinct' => '同じカテゴリ名が入力されています',
        //     'name.*.unique'   => 'すでに登録されているカテゴリ名です',
        // ]);

        // foreach ($requestData['name'] as $name) {

        //     $category = new Faq();

        //     // カテゴリー名保存
        //     $faq->name = $name;
        //     $faq->save();

        //     // 表示順保存
        //     $faq->update([
        //         'sort_order' => $faq->id
        //     ]);
        // }

        // セッションを削除
        // $request->session()->forget('faq_input');
        
        // 二重送信を防ぐためリダイレクト
        // return redirect()->route('faqs.complete');
    }

    public function edit($id)
    {
        // データを取得する
        $faq = Faq::findOrFail($id);

        // セッションを保存
        $sessionInput = session('faq_input');

        return view('faqs.edit',compact('faq','sessionInput'));
    }

    public function confirmEdit(Request $request, $id)
    {

        // バリデーション
        // $requestData = $request->validate(
        //     [
        //         'name' => [
        //             'required',
        //             'string',
        //             'max:255',
        //             Rule::unique('categories', 'name')->ignore($id),
        //         ],
        //     ],
        //     [
        //         'name.required' => 'カテゴリ名を入力してください',
        //         'name.max' => 'カテゴリ名は255文字以内で入力してください',
        //         'name.unique' => 'このカテゴリ名はすでに存在しています',
        //     ]
        // );

        // セッションに保存
        // session(['faq_input' => $requestData]);

        return view('faqs.confirm', [
            'mode' => 'edit',
            // 'requestData' => $requestData,
            // 'id' => $id,
        ]);
    }

    public function update(Request $request, $id)
    {
        // バリデーション
        // $requestData = $request->validate([
        //     'name' => [
        //         'required',
        //         'string',
        //         'max:255',
        //         Rule::unique('categories', 'name')->ignore($id),
        //     ],
        //     [
        //         'name.required' => 'カテゴリ名を入力してください',
        //         'name.max' => 'カテゴリ名は255文字以内で入力してください',
        //         'name.unique' => 'このカテゴリ名はすでに存在しています',
        //     ]
        // ]);

        // カテゴリー名保存
        // $faq = Faq::findOrFail($id);
        
        // $faq->update([
        //     'name' => $requestData['name']
        // ]);

        // セッションを削除
        $request->session()->forget('faq_input');

        // 二重送信を防ぐためリダイレクト
        return redirect()->route('faqs.complete');
    }

    public function complete()
    {
        return view('faqs.complete');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faqs.index');
    }

    // public function updateOrder(Request $request)
    // {
    //     $request->validate([
    //         'ids' => ['required', 'array'],
    //         'ids.*' => ['required', 'integer', 'exists:categories,id'],
    //     ]);

    //     foreach ($request->ids as $index => $id) {
    //         Faq::where('id', $id)->update([
    //             'sort_order' => $index + 1
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => '表示順を保存しました'
    //     ]);
    // }
}