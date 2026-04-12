<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // セッションを削除
        $request->session()->forget(['_old_input',]);

        $users = User::get();

        return view('users.index',compact('users'));
    }

    public function back(Request $request)
    {
        return redirect()
            ->route('users.create')
            ->withInput();
    }

    public function create()
    {

        return view('users.create');
    }

    public function confirm(Request $request)
    {

        // バリデーション
        $userData = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->whereNull('deleted_at')
                ],
                'password' => ['required', 'string', 'min:8'],
                'role' => ['required', 'in:admin,manager,staff'],
                'is_active' => ['required', 'boolean'],
            ],
            [
                'name.required' => '名前を入力してください',
                'name.max' => '名前は255文字以内で入力してください',

                'email.required' => 'メールアドレスを入力してください',
                'email.email' => '有効なメールアドレスを入力してください',
                'email.unique' => 'このメールアドレスはすでに使用されています',
                'email.max' => 'メールアドレスは255文字以内で入力してください',

                'password.required' => 'パスワードを入力してください',
                'password.min' => 'パスワードは8文字以上で入力してください',

                'role.required' => '権限を選択してください',

                'is_active.required' => '利用状態を選択してください',
            ]
        );

        return view('users.confirm', [
            'mode' => 'create',
            'userData' => $userData,
        ]);
    }

    public function store(Request $request)
    {
        // バリデーション
        $requestData = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->whereNull('deleted_at')
                ],
                'password' => ['required', 'string', 'min:8'],
                'role' => ['required', 'in:admin,manager,staff'],
                'is_active' => ['required', 'boolean'],
            ],
            [
                'name.required' => '名前を入力してください',
                'name.max' => '名前は255文字以内で入力してください',

                'email.required' => 'メールアドレスを入力してください',
                'email.email' => '有効なメールアドレスを入力してください',
                'email.unique' => 'このメールアドレスはすでに使用されています',
                'email.max' => 'メールアドレスは255文字以内で入力してください',

                'password.required' => 'パスワードを入力してください',
                'password.min' => 'パスワードは8文字以上で入力してください',

                'role.required' => '権限を選択してください',

                'is_active.required' => '利用状態を選択してください',
            ]
        );

        User::create([
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => Hash::make($requestData['password']),
            'role' => $requestData['role'],
            'is_active' => $requestData['is_active'],
        ]);

        // セッションを削除
        $request->session()->forget(['_old_input',]);
        
        // 二重送信を防ぐためリダイレクト
        return redirect()->route('users.complete');
    }

    public function editBack(Request $request, $id)
    {
        return redirect()
            ->route('users.edit', $id)
            ->withInput();
    }

    public function edit($id)
    {
        // データを取得する
        $user = User::findOrFail($id);

        return view('users.edit',compact('user'));
    }

    public function confirmEdit(Request $request, $id)
    {

        // バリデーション
       $userData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                'in:admin,manager,staff',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'name.required' => '名前を入力してください',
            'name.max' => '名前は255文字以内で入力してください',

            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレスを入力してください',
            'email.unique' => 'このメールアドレスはすでに使用されています',
            'email.max' => 'メールアドレスは255文字以内で入力してください',

            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワードが一致しません',

            'role.required' => '権限を選択してください',
            'is_active.required' => '利用状態を選択してください',
        ]);

        return view('users.confirm', [
            'mode' => 'edit',
            'userData' => $userData,
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $userData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
            'role' => [
                'required',
                'in:admin,manager,staff',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'name.required' => '名前を入力してください',
            'name.max' => '名前は255文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレスを入力してください',
            'email.unique' => 'このメールアドレスはすでに使用されています',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'role.required' => '権限を選択してください',
            'is_active.required' => '利用状態を選択してください',
        ]);

        $user = User::findOrFail($id);

        $updateData = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role'],
            'is_active' => $userData['is_active'],
        ];

        if (!empty($userData['password'])) {
            $updateData['password'] = Hash::make($userData['password']);
        }

        $user->update($updateData);

        // セッションを削除
        $request->session()->forget(['_old_input',]);

        return redirect()->route('users.complete');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->email = 'deleted_' . $user->id . '_' . $user->email;
        $user->save();

        $user->delete();

        return redirect()->route('users.index');
    }
}