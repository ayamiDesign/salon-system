<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CategoryController;

Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/categorys', [CategoryController::class, 'index']);