<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FaqHistoryController;
use App\Http\Controllers\FaqImportController;
use App\Http\Controllers\UserController;

// ログイン
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// ログアウト
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// カテゴリー
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/categories/back', [CategoryController::class, 'back'])
        ->name('categories.create.back');
    Route::post('/categories/{id}/editBack', [CategoryController::class, 'editBack'])
        ->name('categories.edit.back');
    Route::post('/categories/confirm', [CategoryController::class, 'confirm'])
        ->name('categories.confirm');
    Route::post('/categories/{id}/confirm', [CategoryController::class, 'confirmEdit'])
        ->name('categories.confirmEdit');
    Route::get('/categories/complete', function () {
        return view('categories.complete');
    })->name('categories.complete');
    Route::post('/categories/order', [CategoryController::class, 'updateOrder'])
        ->name('categories.updateOrder');
    Route::resource('categories', CategoryController::class);
});

// FAQ
Route::middleware('auth')->get('/faqs', [FaqController::class, 'index'])
    ->name('faqs.index');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/faqs/back', [FaqController::class, 'back'])
        ->name('faqs.create.back');
    Route::post('/faqs/{id}/editBack', [FaqController::class, 'editBack'])
        ->name('faqs.edit.back');
    Route::post('/faqs/confirm', [FaqController::class, 'confirm'])
        ->name('faqs.confirm');
    Route::post('/faqs/{id}/confirm', [FaqController::class, 'confirmEdit'])
        ->name('faqs.confirmEdit');
    Route::get('/faqs/complete', function () {
        return view('faqs.complete');
    })->name('faqs.complete');
    Route::post('/faqs/order', [FaqController::class, 'updateOrder'])
        ->name('faqs.updateOrder');
    Route::resource('faqs', FaqController::class)->except(['index']);
    Route::post('/faqs/import', [FaqImportController::class, 'store'])->name('faqs.import.store');
});


// FAQ履歴
Route::middleware('auth')->get('/faqs/{id}/histories', [FaqHistoryController::class, 'histories'])
    ->name('faqs.histories.index');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::delete('/faq-histories/{id}', [FaqHistoryController::class, 'destroyHistory'])
        ->name('faq-histories.destroy');
});

// アカウント
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/users/back', [FaqController::class, 'back'])
        ->name('users.create.back');
    Route::post('/users/{id}/editBack', [FaqController::class, 'editBack'])
        ->name('users.edit.back');
    Route::post('/users/confirm', [UserController::class, 'confirm'])
    ->name('users.confirm');
    Route::post('/users/{id}/confirm', [UserController::class, 'confirmEdit'])
        ->name('users.confirmEdit');
    Route::get('/users/complete', function () {
        return view('users.complete');
    })->name('users.complete');
    Route::resource('users', UserController::class);
});
