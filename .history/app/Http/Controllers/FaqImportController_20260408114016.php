<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8, SJIS-win');

        $tempPath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempPath, $content);

        $handle = fopen($tempPath, 'r');

        if ($handle === false) {
            return back()->with('error', 'CSVファイルを開けませんでした。');
        }

        $isHeader = true;
        $importCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            if (empty(array_filter($row))) {
                continue;
            }

            if (count($row) < 4) {
                continue;
            }

            $question = trim($row[0] ?? '');
            $answer = trim($row[1] ?? '');
            $categoryId = trim($row[2] ?? '');
            $sortOrder = trim($row[3] ?? '');

            if ($question === '' || $answer === '') {
                continue;
            }

            Faq::create([
                'question' => $question,
                'answer' => $answer,
                'category_id' => $categoryId ?: null,
                'sort_order' => $sortOrder !== '' ? (int)$sortOrder : 0,
            ]);

            $importCount++;
        }

        fclose($handle);
        @unlink($tempPath);

        return redirect()
            ->route('faqs.index')
            ->with('success', $importCount . '件のFAQをインポートしました。');
    }
}