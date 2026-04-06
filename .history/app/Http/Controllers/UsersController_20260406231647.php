<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Users;

class UsersController extends Controller
{
    public function index(Request $request)
    {
         // セッションを削除
        $request->session()->forget('user_input');

        $users = Users::orderBy('sort_order')->get();

        return view('users.index',compact('users'));
    }

    public function create()
    {
        // セッションを保存
        $sessionInput = session('user_input');

        return view('users.create',compact('sessionInput'));
    }

    // public function confirm(Request $request)
    // {

    //     // バリデーション
    //     $requestData = $request->validate([
    //         'name' => 'required|array',
    //         'name.*' => 'required|string|max:255|distinct|unique:categories,name',
    //     ],
    //     [
    //         'name.*.required' => 'カテゴリ名を入力してください',
    //         'name.*.distinct' => '同じカテゴリ名が入力されています',
    //         'name.*.unique'   => 'すでに登録されているカテゴリ名です',
    //     ]);

    //     // データを形成
    //     $categoryNames = $requestData['name'];

    //     // セッションに保存
    //     session(['account_input' => $requestData]);

    //     return view('accounts.confirm', [
    //         'mode' => 'create',
    //         'accountNames' => $categoryNames,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     // バリデーション
    //     $requestData = $request->validate([
    //         'name' => 'required|array',
    //         'name.*' => 'required|string|max:255|distinct|unique:categories,name',
    //     ],
    //     [
    //         'name.*.required' => 'カテゴリ名を入力してください',
    //         'name.*.distinct' => '同じカテゴリ名が入力されています',
    //         'name.*.unique'   => 'すでに登録されているカテゴリ名です',
    //     ]);

    //     foreach ($requestData['name'] as $name) {

    //         $category = new Categories();

    //         // カテゴリー名保存
    //         $category->name = $name;
    //         $category->save();

    //         // 表示順保存
    //         $category->update([
    //             'sort_order' => $category->id
    //         ]);
    //     }

    //     // セッションを削除
    //     $request->session()->forget('account_input');
        
    //     // 二重送信を防ぐためリダイレクト
    //     return redirect()->route('accounts.complete');
    // }

    public function edit($id)
    {
        // データを取得する
        $users = Users::findOrFail($id);

        // セッションを保存
        $sessionInput = session('user_input');

        return view('users.edit',compact('users','sessionInput'));
    }

    // public function confirmEdit(Request $request, $id)
    // {

    //     // バリデーション
    //     $requestData = $request->validate(
    //         [
    //             'name' => [
    //                 'required',
    //                 'string',
    //                 'max:255',
    //                 Rule::unique('categories', 'name')->ignore($id),
    //             ],
    //         ],
    //         [
    //             'name.required' => 'カテゴリ名を入力してください',
    //             'name.max' => 'カテゴリ名は255文字以内で入力してください',
    //             'name.unique' => 'このカテゴリ名はすでに存在しています',
    //         ]
    //     );

    //     // セッションに保存
    //     session(['account_input' => $requestData]);

    //     return view('accounts.confirm', [
    //         'mode' => 'edit',
    //         'requestData' => $requestData,
    //         'id' => $id,
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     // バリデーション
    //     $requestData = $request->validate([
    //         'name' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('categories', 'name')->ignore($id),
    //         ],
    //         [
    //             'name.required' => 'カテゴリ名を入力してください',
    //             'name.max' => 'カテゴリ名は255文字以内で入力してください',
    //             'name.unique' => 'このカテゴリ名はすでに存在しています',
    //         ]
    //     ]);

    //     // カテゴリー名保存
    //     $category = Accounts::findOrFail($id);
        
    //     $category->update([
    //         'name' => $requestData['name']
    //     ]);

    //     // セッションを削除
    //     $request->session()->forget('account_input');

    //     // 二重送信を防ぐためリダイレクト
    //     return redirect()->route('accounts.complete');
    // }

    // public function complete()
    // {
    //     return view('accounts.complete');
    // }

    // public function destroy($id)
    // {
    //     $category = Categories::findOrFail($id);
    //     $category->delete();

    //     return redirect()->route('categories.index');
    // }
}