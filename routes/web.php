<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CategoryController;

// カテゴリー
Route::post('/categories/confirm', [CategoryController::class, 'confirm'])
    ->name('categories.confirm');
Route::get('/categories/complete', function () {
    return view('categories.complete');
})->name('categories.complete');
Route::resource('categories', CategoryController::class);

// FAQ
Route::get('/faqs', [FaqController::class, 'index']);