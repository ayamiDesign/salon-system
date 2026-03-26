<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = [
            ['question' => 'テスト', 'answer' => 'テスト回答']
        ];

        return view('faqs.index', compact('faqs'));
    }
}