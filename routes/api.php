<?php

use App\Http\Controllers\Api\V1\ApiPostController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/posts/search', [PostController::class, 'search'])->middleware('throttle:30,1');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login');


    Route::middleware('throttle:60,1')->group(function () {
        Route::apiResource('posts', ApiPostController::class)->only(['index', 'show']);
        Route::apiResource('comments', CommentController::class)->only(['index', 'show']);
    });

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::apiResource('posts', ApiPostController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('comments', CommentController::class)->only(['store', 'update', 'destroy']);
    });
});

Route::middleware(['web', 'auth', 'throttle:60,1'])->group(function () {
    Route::post('comments/{comment}/reaction', [ReactionController::class, 'toggle'])->name('comments.reaction.toggle');
    Route::delete('comments/{comment}/reaction', [ReactionController::class, 'remove'])->name('comments.reaction.remove');
});
