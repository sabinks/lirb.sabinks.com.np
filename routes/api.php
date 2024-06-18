<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/product', ProductController::class);
Route::resource('/product/{product}/reviews', ReviewController::class);
