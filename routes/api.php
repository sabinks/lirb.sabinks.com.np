<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/product', ProductController::class);
    Route::resource('/product.reviews', ReviewController::class)->shallow();
    Route::post('/review/{review}/publish', [ReviewController::class, 'reviewPublish'])->name('review.publish');
});
