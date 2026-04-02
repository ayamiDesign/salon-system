<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CategoryController;

Route::get('/faqs', [FaqController::class, 'index']);

Route::resource('categories', CategoryController::class);
Route::post('/categories/confirm', [CategoryController::class, 'confirm'])
    ->name('categories.confirm');