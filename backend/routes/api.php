<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReplyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
    Route::post('/books/{id}/like', [BookController::class, 'like']);
    Route::post('/books/{id}/unlike', [BookController::class, 'unlike']);

    Route::get('/books/{bookId}/replies', [ReplyController::class, 'index']);
    Route::post('replies', [ReplyController::class, 'store']);
    Route::get('/replies/{id}', [ReplyController::class, 'show']);
    Route::put('/replies/{id}', [ReplyController::class, 'update']);
    Route::delete('/replies/{id}', [ReplyController::class, 'destroy']);
    Route::post('/replies/{id}/like', [ReplyController::class, 'like']);
    Route::post('/replies/{id}/unlike', [ReplyController::class, 'unlike']);
});
