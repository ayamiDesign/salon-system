<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Faq;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
         // セッションを削除
        $request->session()->forget('category_input');

        $categories = Category::orderBy('sort_order')->get();

        // カテゴリごとのFAQ件数を取得
        foreach ($categories as $index => $category) {
            $count = Faq::categoryMatch($category->id)->count();
            $category['count'] = $count;
        }
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
        $requestData = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories', 'name')->ignore($id),
                ],
            ],
            [
                'name.required' => 'カテゴリ名を入力してください',
                'name.max' => 'カテゴリ名は255文字以内で入力してください',
                'name.unique' => 'このカテゴリ名はすでに存在しています',
            ]
        );

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
            [
                'name.required' => 'カテゴリ名を入力してください',
                'name.max' => 'カテゴリ名は255文字以内で入力してください',
                'name.unique' => 'このカテゴリ名はすでに存在しています',
            ]
        ]);

        // カテゴリー名保存
        $category = Category::findOrFail($id);
        
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

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:categories,id'],
        ]);

        foreach ($request->ids as $index => $id) {
            Category::where('id', $id)->update([
                'sort_order' => $index + 1
            ]);
        }

        return response()->json([
            'message' => '表示順を保存しました'
        ]);
    }
}