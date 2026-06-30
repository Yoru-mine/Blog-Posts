<?php

use App\Http\Controllers\ReactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::resource('posts', App\Http\Controllers\Api\V1\ApiPostController::class);
    Route::resource('comments', App\Http\Controllers\Api\V1\CommentController::class);

});

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('comments/{comment}/reaction', [ReactionController::class, 'toggle'])->name('comments.reaction.toggle');
    Route::delete('comments/{comment}/reaction', [ReactionController::class, 'remove'])->name('comments.reaction.remove');
});
