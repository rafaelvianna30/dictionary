<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedisCache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json(["message"=>"Fullstack Challenge 🏅 - Dictionary"]);
});

Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
});

Route::middleware([RedisCache::class])->group(function () {
    Route::middleware('auth:api')->prefix('/entries/en')->group(function () {
        Route::get('', [EntryController::class, 'index']);
        Route::prefix('/{word}')->group(function () {

            Route::get('', [EntryController::class, 'find']);
            Route::post('/favorite', [EntryController::class, 'saveFavorite']);
            Route::delete('/unfavorite', [EntryController::class, 'deleteFavorite']);
        });
    });

    Route::middleware('auth:api')->prefix('/user/me')->group(function () {

        Route::get('', [UserController::class, 'showUser']);
        Route::get('/history', [UserController::class, 'showHistory']);
        Route::get('/favorites', [UserController::class, 'showFavorites']);
    });
});
