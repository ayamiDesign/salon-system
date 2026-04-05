<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\FaqHistoriesController;

// カテゴリー
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

// FAQ
Route::post('/faqs/confirm', [FaqsController::class, 'confirm'])
    ->name('faqs.confirm');
Route::post('/faqs/{id}/confirm', [FaqsController::class, 'confirmEdit'])
    ->name('faqs.confirmEdit');
Route::get('/faqs/complete', function () {
    return view('faqs.complete');
})->name('faqs.complete');
Route::post('/faqs/order', [FaqsController::class, 'updateOrder'])
    ->name('faqs.updateOrder');
Route::resource('faqs', FaqsController::class);

// FAQ履歴
Route::get('/faqs/{id}/histories', [FaqHistoriesController::class, 'histories'])
    ->name('faqs.histories.index');
Route::delete('/faq-histories/{id}', [FaqHistoriesController::class, 'destroyHistory'])
    ->name('faq-histories.destroy');