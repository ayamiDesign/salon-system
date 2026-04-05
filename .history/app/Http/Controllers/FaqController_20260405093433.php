<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Faq;

class FaqController extends Controller
{

    public function index(Request $request)
    {
         // セッションを削除
        $request->session()->forget('faq_input');

        // カテゴリを取得
        $categoriesList = Category::orderBy('sort_order')->get();

        // カテゴリごとのFAQ件数を取得
        foreach ($categoriesList as $index => $category) {
            $count = Faq::categoryMatch($category->id)->count();
            $category['count'] = $count;
        }

        // 表示用のカテゴリ名を形成
        $categories = Category::pluck('name', 'id');
        $faqs = Faq::orderBy('sort_order')->get();
        foreach ($faqs as $index => $faq) {

            $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
            $faq['category2_name'] = !empty($faq['category2_id'])
            ? ($categories[$faq['category2_id']] ?? '')
            : '';
        }

        return view('faqs.index',compact('faqs','categoriesList'));
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

        // データを形成
        $faqs = $requestData['faqs'];

        // カテゴリ名取得
        $categories = Category::pluck('name', 'id');
        
        foreach ($faqs as $index => &$faq) {

            // PDF一時保存
            if ($request->hasFile("faqs.$index.pdf")) {
                $tempPath = $request->file("faqs.$index.pdf")->store('faq-temp', 'public');

                $faq['pdf_temp_path'] = $tempPath;
                $faq['pdf_original_name'] = $request->file("faqs.$index.pdf")->getClientOriginalName();
            } else {
                $faq['pdf_temp_path'] = null;
                $faq['pdf_original_name'] = null;
            }

            // 表示用のカテゴリ名を形成
            $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
            $faq['category2_name'] = !empty($faq['category2_id'])
            ? ($categories[$faq['category2_id']] ?? '')
            : '';
        }

        //参照を切る
        unset($faq);

        // セッションに保存
        session(['faq_input' => $requestData]);

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
                'different:category1_id',
            ],

            'faqs.*.question' => ['required', 'string','distinct'],
            'faqs.*.answer' => ['required', 'string'],
            'faqs.*.note' => ['nullable', 'string'],
            'faqs.*.url' => ['nullable', 'url'],
            'faqs.*.is_visible' => ['nullable', 'boolean'],
            'faqs.*.pdf_temp_path' => ['nullable', 'string'],
            'faqs.*.pdf_original_name' => ['nullable', 'string'],
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
            'faqs.*.pdf_temp_path.string' => 'PDFのデータが不正です',
            'faqs.*.pdf_original_name.string' => 'PDFファイル名が不正です',
        ]);

        foreach ($requestData['faqs'] as $faq) {

            $pdfPath = null;

            // 一時保存済みPDFを正式保存
            if (!empty($faq['pdf_temp_path']) && Storage::disk('public')->exists($faq['pdf_temp_path'])) {
                $fileName = basename($faq['pdf_temp_path']);
                $finalPath = 'faq-pdfs/' . $fileName;

                Storage::disk('public')->move($faq['pdf_temp_path'], $finalPath);

                $pdfPath = $finalPath;
            }

            // FAQ保存
            $FaqModel = Faq::create([
                'category1_id' => $faq['category1_id'],
                'category2_id' => $faq['category2_id'],
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'note' => $faq['note'],
                'url' => $faq['url'],
                'is_visible' => $faq['is_visible'],
                'pdf' => $pdfPath,
            ]);

            // 表示順保存
            $FaqModel->update([
                'sort_order' => $FaqModel->id
            ]);
        }

        // セッションを削除
        $request->session()->forget('faq_input');
        
        // 二重送信を防ぐためリダイレクト
        return redirect()->route('faqs.complete');
    }

    public function edit($id)
    {
        // データを取得する
        $faq = Faq::findOrFail($id);

        // カテゴリを取得
        $categories = Category::orderBy('sort_order')->get();

        // カテゴリ名取得
        $categories = Category::pluck('name', 'id');
        
        // 表示用のカテゴリ名を形成
        $faq['category1_name'] = $categories[$faq['category1_id']] ?? '';
        $faq['category2_name'] = !empty($faq['category2_id'])
        ? ($categories[$faq['category2_id']] ?? '')
        : '';

        // セッションを保存
        $sessionInput = session('faq_input');

        return view('faqs.edit',compact('faq','categories','sessionInput'));
    }

    public function confirmEdit(Request $request, $id)
    {

        // バリデーション
        $requestData = $request->validate([
           'faq.category1_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'category2_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                'different:category1_id',
            ],

            'question' => ['required', 'string','distinct'],
            'answer' => ['required', 'string'],
            'note' => ['nullable', 'string'],
            'url' => ['nullable', 'url'],
            'is_visible' => ['nullable', 'boolean'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'required' => 'FAQを1件以上入力してください',
            'category1_id.required' => 'カテゴリ（メイン）は必須です',
            'category1_id.exists' => 'カテゴリ（メイン）の値が不正です',
            'category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
            'category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）に同じものは選べません',
            'question.required' => '質問は必須です',
            'question.distinct' => '同じ質問が入力されています',
            'answer.required' => '回答は必須です',
            'url.url' => 'URLの形式が正しくありません',
            'pdf.mimes' => 'PDFファイルのみアップロードできます',
            'pdf.max' => 'PDFファイルは10MB以下にしてください',
        ]);

        // セッションに保存
        session(['faq_input' => $requestData]);

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