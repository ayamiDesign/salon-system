<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CategoryController;

// カテゴリー
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

// FAQ
Route::post('/faqs/confirm', [FaqController::class, 'confirm'])
    ->name('faqs.confirm');
Route::post('/faqs/{id}/confirm', [FaqController::class, 'confirmEdit'])
    ->name('faqs.confirmEdit');
Route::get('/faqs/complete', function () {
    return view('faqs.complete');
})->name('faqs.complete');
Route::post('/faqs/order', [FaqController::class, 'updateOrder'])
    ->name('faqs.updateOrder');
Route::get('/faqs/{id}/histories', [FaqController::class, 'histories'])
    ->name('faqs.histories.index');
Route::resource('faqs', FaqController::class);