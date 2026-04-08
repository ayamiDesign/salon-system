<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

                while (($row = fgetcsv($handle)) !== false) {
                    if ($isHeader) {
                        $isHeader = false;
                        continue;
                    }

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $category = trim($row[0] ?? '');
                    $question = trim($row[1] ?? '');
                    $answer = trim($row[2] ?? '');
                    $note = trim($row[3] ?? '');
                    $URL = trim($row[4] ?? '');

                    // 質問と回答が空の場合はエラー
                    if ($question === '' || $answer === '') {
                        throw new \RuntimeException('質問または回答が空の行があります');
                    }

                    // カテゴリーの処理

                    // 表示順の処理
                    $maxSortOrder = Faq::max('sort_order') ?? 0;
                    $maxSortOrder++;

                    Faq::create([
                        'question' => $question,
                        'answer' => $answer,
                        'category_id' => $categoryId !== '' ? (int) $categoryId : null,
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