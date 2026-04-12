<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Faq;
use App\Models\FaqHistory;

class FaqController extends Controller
{

    public function index(Request $request)
    {

        // セッションを削除
        $request->session()->forget(['_old_input',]);

        // FAQ全件取得
        $faqCount = Faq::count();

        // カテゴリを取得
        $categoriesList = Category::orderBy('sort_order')->get();

        // カテゴリごとのFAQ件数を取得
        foreach ($categoriesList as $index => $category) {
            $count = Faq::categoryMatch($category->id)->count();
            $category['count'] = $count;
        }

        // 検索を実行
        $searchCategory = $request->input('category', '0');
        $searchKeyword = $request->input('keyword', '');

        $faqs = Faq::search($searchCategory, $searchKeyword)
            ->paginate(20)
            ->appends($request->query());

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

            'faqs.*.question' => ['required', 'string','distinct','unique:faqs,question'],
            'faqs.*.answer' => ['required', 'string'],
            'faqs.*.note' => ['nullable', 'string'],
            'faqs.*.url' => ['nullable', 'url'],
            'faqs.*.is_visible' => ['nullable', 'boolean'],
            // 'faqs.*.pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
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
            // 'faqs.*.pdf.file' => 'PDFファイルの選択が正しくありません',
            // 'faqs.*.pdf.mimes' => 'PDFファイルのみアップロードできます',
            // 'faqs.*.pdf.max' => 'PDFファイルは10MB以下にしてください',
        ]);

        // データを形成
        $faqs = $requestData['faqs'];

        // カテゴリ名取得
        $categories = Category::pluck('name', 'id');
        
        foreach ($faqs as $index => &$faq) {

            // // PDF一時保存
            // if ($request->hasFile("faqs.$index.pdf")) {
            //     $tempPath = $request->file("faqs.$index.pdf")->store('faq-temp', 'public');

            //     $faq['pdf_temp_path'] = $tempPath;
            //     $faq['pdf_original_name'] = $request->file("faqs.$index.pdf")->getClientOriginalName();
            // } else {
            //     $faq['pdf_temp_path'] = null;
            //     $faq['pdf_original_name'] = null;
            // }

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

            'faqs.*.question' => ['required', 'string','distinct','unique:faqs,question'],
            'faqs.*.answer' => ['required', 'string'],
            'faqs.*.note' => ['nullable', 'string'],
            'faqs.*.url' => ['nullable', 'url'],
            'faqs.*.is_visible' => ['nullable', 'boolean'],
            // 'faqs.*.pdf_temp_path' => ['nullable', 'string'],
            // 'faqs.*.pdf_original_name' => ['nullable', 'string'],
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
            // 'faqs.*.pdf_temp_path.string' => 'PDFのデータが不正です',
            // 'faqs.*.pdf_original_name.string' => 'PDFファイル名が不正です',
        ]);

        foreach ($requestData['faqs'] as $faq) {

            // $pdfPath = null;

            // // 一時保存済みPDFを正式保存
            // if (!empty($faq['pdf_temp_path']) && Storage::disk('public')->exists($faq['pdf_temp_path'])) {
            //     $fileName = basename($faq['pdf_temp_path']);
            //     $finalPath = 'faq-pdfs/' . $fileName;

            //     Storage::disk('public')->move($faq['pdf_temp_path'], $finalPath);

            //     $pdfPath = $finalPath;
            // }

            // FAQ保存
            $FaqModel = Faq::create([
                'category1_id' => $faq['category1_id'],
                'category2_id' => $faq['category2_id'],
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'note' => $faq['note'],
                'url' => $faq['url'],
                'is_visible' => $faq['is_visible'],
                // 'pdf' => $pdfPath,
                // 'pdf_original_name' => $faq['pdf_original_name'],
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
                'category1_id' => ['required', 'integer', 'exists:categories,id'],
                'category2_id' => ['nullable', 'integer', 'exists:categories,id', 'different:category1_id'],
                'question' => ['required', 'string','unique:faqs,question,'.$id],
                'answer' => ['required', 'string'],
                'note' => ['nullable', 'string'],
                'url' => ['nullable', 'url'],
                'is_visible' => ['nullable', 'boolean'],

                // 'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
                // 'delete_pdf' => ['nullable', 'boolean'],
                // 'current_pdf_original_name' => ['nullable', 'string'],
                // 'current_pdf_path' => ['nullable', 'string'],

                'faq_history' => ['nullable', 'boolean'],
                'change_summary' => ['exclude_unless:faq_history,1', 'required_if:faq_history,1', 'string', 'max:25'],
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

                // 'pdf.file' => 'PDFファイルの選択が正しくありません',
                // 'pdf.mimes' => 'PDFファイルのみアップロードできます',
                // 'pdf.max' => 'PDFファイルは10MB以下にしてください',

                'change_summary.required' => '変更メモは必須です',
                'change_summary.max' => '変更メモは25文字以内で入力してください',
            ]
        );

        // $validator->after(function ($validator) use ($request) {
        //     if ($request->boolean('delete_pdf') && $request->hasFile('pdf')) {
        //         $validator->errors()->add(
        //             'pdf',
        //             'PDFを削除する場合は、新しいPDFを同時にアップロードできません'
        //         );
        //     }
        // });

        $requestData = $validator->validate();

        $categoriesList = Category::select('id', 'name')->get();

        $requestData['category1_name'] = optional(
            $categoriesList->firstWhere('id', $requestData['category1_id'])
        )->name ?? '-';

        $requestData['category2_name'] = !empty($requestData['category2_id'])
            ? optional($categoriesList->firstWhere('id', $requestData['category2_id']))->name
            : null;

        /*
        |------------------------------------------------------------
        | PDFの状態を整理
        | 1. delete_pdf = 1        -> 削除扱い
        | 2. 新しいpdfあり         -> 一時保存して差し替え候補
        | 3. それ以外              -> 変更なし（既存情報を引き継ぐ）
        |------------------------------------------------------------
        */
        // $requestData['pdf_temp_path'] = null;

        // if ($request->boolean('delete_pdf')) {
        //     $requestData['pdf_original_name'] = null;
        //     $requestData['pdf'] = null;
        // } elseif ($request->hasFile('pdf')) {
        //     $file = $request->file('pdf');
        //     $requestData['pdf_temp_path'] = $file->store('tmp/faq_pdfs');
        //     $requestData['pdf_original_name'] = $file->getClientOriginalName();
        //     $requestData['pdf'] = null;
        // } else {
        //     $requestData['pdf_original_name'] = $request->input('current_pdf_original_name');
        //     $requestData['pdf'] = $request->input('current_pdf_path');
        // }

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
                'category1_id' => ['required', 'integer', 'exists:categories,id'],
                'category2_id' => ['nullable', 'integer', 'exists:categories,id', 'different:category1_id'],
                'question' => ['required', 'string'],
                'answer' => ['required', 'string'],
                'note' => ['nullable', 'string'],
                'url' => ['nullable', 'url'],
                'is_visible' => ['nullable', 'boolean'],

                // confirm画面から来るPDF関連
                // 'pdf_temp_path' => ['nullable', 'string'],
                // 'pdf_original_name' => ['nullable', 'string'],
                // 'pdf' => ['nullable', 'string'],
                // 'delete_pdf' => ['nullable', 'boolean'],

                'faq_history' => ['nullable', 'boolean'],
                'change_summary' => ['exclude_unless:faq_history,1', 'required_if:faq_history,1', 'string', 'max:25'],
            ],
            [
                'category1_id.required' => 'カテゴリ（メイン）は必須です',
                'category1_id.integer' => 'カテゴリ（メイン）の値が不正です',
                'category1_id.exists' => 'カテゴリ（メイン）の値が不正です',

                'category2_id.integer' => 'カテゴリ（サブ）の値が不正です',
                'category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
                'category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）に同じものは選べません',

                'question.required' => '質問は必須です',
                'answer.required' => '回答は必須です',
                'url.url' => 'URLの形式が正しくありません',

                'change_summary.required' => '変更メモは必須です',
                'change_summary.max' => '変更メモは25文字以内で入力してください',
            ]
        );

        $requestData = $validator->validate();

        // DB::beginTransaction();

        // try {
            /*
            |--------------------------------------------------------------------------
            | 1. 履歴保存（変更前情報）
            |--------------------------------------------------------------------------
            | faq_history = 1 の時だけ、更新前の内容を履歴に保存
            */
            if (!empty($requestData['faq_history'])) {
                FaqHistory::create([
                    'faq_id' => $faq->id,

                    'category1_id' => $faq->category1_id,
                    'category2_id' => $faq->category2_id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'note' => $faq->note,
                    'url' => $faq->url,
                    // 'pdf' => $faq->pdf,
                    // 'pdf_original_name' => $faq->pdf_original_name,
                    'is_visible' => $faq->is_visible,

                    'change_summary' => $requestData['change_summary']
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 2. PDF処理
            |--------------------------------------------------------------------------
            | パターンは3つ
            | - delete_pdf = 1       -> 削除
            | - pdf_temp_pathあり    -> 差し替え
            | - それ以外             -> 変更なし
            |--------------------------------------------------------------------------
            */
            // $newPdfPath = $faq->pdf;
            // $newPdfOriginalName = $faq->pdf_original_name;

            // if (!empty($requestData['delete_pdf'])) {
            //     // PDF削除
            //     if (!empty($faq->pdf) && Storage::exists($faq->pdf)) {
            //         Storage::delete($faq->pdf);
            //     }

            //     $newPdfPath = null;
            //     $newPdfOriginalName = null;

            // } elseif (!empty($requestData['pdf_temp_path'])) {
            //     // PDF差し替え
            //     $tempPath = $requestData['pdf_temp_path'];

            //     if (Storage::exists($tempPath)) {
            //         // 古いPDF削除
            //         if (!empty($faq->pdf) && Storage::exists($faq->pdf)) {
            //             Storage::delete($faq->pdf);
            //         }

            //         // tmp から本保存へ移動
            //         $fileName = basename($tempPath);
            //         $finalPath = 'faq_pdfs/' . $fileName;

            //         Storage::move($tempPath, $finalPath);

            //         $newPdfPath = $finalPath;
            //         $newPdfOriginalName = $requestData['pdf_original_name'] ?? null;
            //     }
            // }

            /*
            |--------------------------------------------------------------------------
            | 3. FAQ本体更新
            |--------------------------------------------------------------------------
            */
            $faq->update([
                'category1_id' => $requestData['category1_id'],
                'category2_id' => $requestData['category2_id'] ?: null,
                'question' => $requestData['question'],
                'answer' => $requestData['answer'],
                'note' => $requestData['note'] ?? null,
                'url' => $requestData['url'] ?? null,
                'is_visible' => !empty($requestData['is_visible']) ? 1 : 0,

                // 'pdf' => $newPdfPath,
                // 'pdf_original_name' => $newPdfOriginalName,
            ]);

            DB::commit();

            // セッションを削除
            $request->session()->forget(['_old_input',]);


            return redirect()->route('faqs.complete');

        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     throw $e;
        // }
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