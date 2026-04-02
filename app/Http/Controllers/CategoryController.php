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
        $lastId = Category::max('id');
        $displayOrder = $lastId + 1;

        $sessionInput = session('category_input');

        return view('categories.create',compact('displayOrder', 'sessionInput'));
    }

    public function confirm(Request $request)
    {

        // バリデーション
        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
        ]);

        // データを形成
        $categoryNames = $validated['name'];

        // セッションに保存
        session(['category_input' => $validated]);

        return view('categories.confirm', compact('categoryNames'));
    }

    public function store()
    {
        return view('categories.complete');
    }
}