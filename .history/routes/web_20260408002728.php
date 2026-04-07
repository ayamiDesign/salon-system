<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\FaqHistoriesController;
use App\Http\Controllers\UsersController;

// ログイン
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// ログアウト
Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');

// カテゴリー
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/categories/confirm', [CategoriesController::class, 'confirm'])
        ->name('categories.confirm');
    Route::post('/categories/{id}/confirm', [CategoriesController::class, 'confirmEdit'])
        ->name('categories.confirmEdit');
    Route::get('/categories/complete', function () {
        return view('categories.complete');
    })->name('categories.complete');
    Route::post('/categories/order', [CategoriesController::class, 'updateOrder'])
        ->name('categories.updateOrder');
    Route::resource('categories', CategoriesController::class);
});

// FAQ
Route::middleware('auth')->get('/faqs', [FaqsController::class, 'index']);
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/faqs/confirm', [FaqsController::class, 'confirm'])
        ->name('faqs.confirm');
    Route::post('/faqs/{id}/confirm', [FaqsController::class, 'confirmEdit'])
        ->name('faqs.confirmEdit');
    Route::get('/faqs/complete', function () {
        return view('faqs.complete');
    })->name('faqs.complete');
    Route::post('/faqs/order', [FaqsController::class, 'updateOrder'])
        ->name('faqs.updateOrder');
    Route::resource('faqs', FaqsController::class)->except(['index']);
});


// FAQ履歴
Route::get('/faqs/{id}/histories', [FaqHistoriesController::class, 'histories'])
    ->name('faqs.histories.index');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::delete('/faq-histories/{id}', [FaqHistoriesController::class, 'destroyHistory'])
        ->name('faq-histories.destroy');
});

// アカウント
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/users/confirm', [UsersController::class, 'confirm'])
    ->name('users.confirm');
    Route::post('/users/{id}/confirm', [UsersController::class, 'confirmEdit'])
        ->name('users.confirmEdit');
    Route::get('/users/complete', function () {
        return view('users.complete');
    })->name('users.complete');
    Route::resource('users', UsersController::class);
});
