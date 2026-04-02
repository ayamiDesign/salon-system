<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function create()
    {
        // セッションを削除
        session()->forget('category_input');

        // セッションを保存
        $sessionInput = session('category_input');

        return view('categories.create',compact('sessionInput'));
    }

    public function confirm(Request $request)
    {

        // バリデーション
        $requestData = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255|distinct|unique:categories,name',
        ]);

        // データを形成
        $categoryNames = $requestData['name'];

        // セッションに保存
        session(['category_input' => $requestData]);

        return view('categories.confirm', compact('categoryNames'));
    }

    public function store(Request $request)
    {
        // バリデーション
        $requestData = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255|distinct|unique:categories,name',
        ]);

        foreach ($requestData['name'] as $name) {
            $category = new Category();

            // カテゴリー名保存
            $category->name = $name;
            $category->save();

            //　表示順保存
            $category->update([
                'sort_order' => $category->id
            ]);
        }

        // セッションを削除
        $request->session()->forget('category_input');
        // 二重送信を防ぐためリダイレクト
        return redirect()->route('categories.complete');
    }

    public function complete()
    {
        return view('categories.complete');
    }
}