<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // セッションを削除
        $request->session()->forget('category_input');
        
        $categories = Category::all();
        return view('categories.index',compact('categories'));
    }

    public function create()
    {
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
        ],
        [
            'name.*.required' => 'カテゴリ名を入力してください',
            'name.*.distinct' => '同じカテゴリ名が入力されています',
            'name.*.unique'   => 'すでに登録されているカテゴリ名です',
        ]);

        // データを形成
        $categoryNames = $requestData['name'];

        // セッションに保存
        session(['category_input' => $requestData]);

        return view('categories.confirm', [
            'mode' => 'create',
            'categoryNames' => $categoryNames,
        ]);
    }

    public function store(Request $request)
    {
        // バリデーション
        $requestData = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255|distinct|unique:categories,name',
        ],
        [
            'name.*.required' => 'カテゴリ名を入力してください',
            'name.*.distinct' => '同じカテゴリ名が入力されています',
            'name.*.unique'   => 'すでに登録されているカテゴリ名です',
        ]);

        foreach ($requestData['name'] as $name) {

            $category = new Category();

            // カテゴリー名保存
            $category->name = $name;
            $category->save();

            // 表示順保存
            $category->update([
                'sort_order' => $category->id
            ]);
        }

        // セッションを削除
        $request->session()->forget('category_input');
        // 二重送信を防ぐためリダイレクト
        return redirect()->route('categories.complete');
    }

    public function edit($id)
    {
        // データを取得する
        $category = Category::findOrFail($id);

        // セッションを保存
        $sessionInput = session('category_input');

        return view('categories.edit',compact('category','sessionInput'));
    }

    public function confirmEdit(Request $request, $id)
    {

        // バリデーション
        $requestData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),
            ],
        ]);

        // セッションに保存
        session(['category_input' => $requestData]);

        return view('categories.confirm', [
            'mode' => 'edit',
            'requestData' => $requestData,
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id)
    {
        // バリデーション
        $requestData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),
            ],
        ]);

        // カテゴリー名保存
        $category = new Category();
        
        $category->update([
            'name' => $requestData['name']
        ]);

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