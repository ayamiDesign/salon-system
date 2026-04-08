<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Faq;

class FaqImportController extends Controller
{

    public function create()
    {
        return view('faqs.import');
    }

    public function store(Request $request)
    {
        // CSVファイルのバリデーション
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        // アップロードされたファイルを取得
        $file = $request->file('csv_file');
        $content = file_get_contents($file->getRealPath());

        // UTF-8に変換
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8, SJIS-win');

        // 一時ファイルに保存（UTF-8変換のため）
        $tempPath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempPath, $content);

        $handle = fopen($tempPath, 'r');

        if ($handle === false) {
            return back()->with('error', 'CSVファイルを開けませんでした。');
        }

        $importCount = 0;

        try {
            DB::transaction(function () use ($handle, &$importCount) {
                $isHeader = true;
                $maxSortOrder = Faq::max('sort_order') ?? 0;

                while (($row = fgetcsv($handle)) !== false) {
                    if ($isHeader) {
                        $isHeader = false;
                        continue;
                    }

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $categoryText = trim($row[0] ?? '');
                    $question = trim($row[1] ?? '');
                    $answer = trim($row[2] ?? '');
                    $note = trim($row[3] ?? '');
                    $URL = trim($row[4] ?? '');

                    // 質問と回答が空の場合はエラー
                    if ($question === '' || $answer === '') {
                        throw new \RuntimeException('質問または回答が空の行があります');
                    }

                    // カテゴリーの処理
                    // 改行ごとに分割
                    $categoryNames = preg_split('/\r\n|\r|\n/', $categoryText);

                    $categoryNames = array_map(function ($name) {
                        $name = trim($name);

                        // 先頭の絵文字・記号削除
                        $name = preg_replace('/^[^\p{L}\p{N}\p{Han}\p{Hiragana}\p{Katakana}]+/u', '', $name);

                        return trim($name);
                    }, $categoryNames);

                    // 空削除
                    $categoryNames = array_values(array_filter($categoryNames));

                    // 初期値
                    $category1Id = null;
                    $category2Id = null;

                    // 1つ目
                    if (!empty($categoryNames[0])) {
                        $category1 = Category::firstOrCreate([
                            'name' => $categoryNames[0],
                        ]);
                        $category1Id = $category1->id;
                    }

                    // 2つ目
                    if (!empty($categoryNames[1])) {
                        $category2 = Category::firstOrCreate([
                            'name' => $categoryNames[1],
                        ]);
                        $category2Id = $category2->id;
                    }

                    // 表示順の処理
                    $maxSortOrder++;

                    // データの形成
                    $faq = [
                        'category1_id' => $category1Id,
                        'category2_id' => $category2Id,
                        'question' => $question,
                        'answer' => $answer,
                        'note' => $note,
                        'url' => $URL,
                        'sort_order' => $maxSortOrder,
                    ];

                    // バリデーション
                    $validator = Validator::make($faq, [
                        'category1_id' => [
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

                        'question' => [
                            'required',
                            'string',
                            'max:255',
                            'unique:faqs,question',
                        ],

                        'answer' => ['required', 'string'],
                        'note' => ['nullable', 'string'],
                        'url' => ['nullable', 'url'],
                    ], [
                        'category1_id.required' => 'カテゴリ（メイン）は必須です',
                        'category1_id.exists' => 'カテゴリ（メイン）の値が不正です',
                        'category2_id.exists' => 'カテゴリ（サブ）の値が不正です',
                        'category2_id.different' => 'カテゴリ（メイン）とカテゴリ（サブ）は別にしてください',
                        'question.required' => '質問は必須です',
                        'question.unique' => '同じ質問が既に存在します',
                        'answer.required' => '回答は必須です',
                        'url.url' => 'URLの形式が正しくありません',
                    ]);

                    if ($validator->fails()) {
                        throw new \RuntimeException($validator->errors()->first());
                    }

                    Faq::create([
                        'category1_id' => $category1Id,
                        'category2_id' => $category2Id,
                        'question' => $question,
                        'answer' => $answer,
                        'note' => $note,
                        'url' => $URL,
                        'sort_order' => $maxSortOrder,
                    ]);

                    $importCount++;
                }
            });
        } finally {
            fclose($handle);
            @unlink($tempPath);
        }

        return redirect()
            ->route('faqs.index')
            ->with('success', $importCount . '件のFAQをインポートしました。');
    }
}