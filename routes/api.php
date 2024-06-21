<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
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
    Route::get('role-list', [RoleController::class, 'roleList']);
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);

    Route::post('assign-role', [UserRoleController::class, 'assignRole']);
    Route::post('revoke-role', [UserRoleController::class, 'revokeRole']);
    Route::post('user-roles', [UserRoleController::class, 'userRoles']);

    Route::post('assign-permission', [RolePermissionController::class, 'assignPermission']);
    Route::post('revoke-permission', [RolePermissionController::class, 'revokePermission']);
    // Route::post('role-permissions', [RolePermissionController::class, 'rolePermissions']);
});
