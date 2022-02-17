<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])
        ->name('auth.register');

    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    Route::post('verify/email', [AuthController::class, 'emailVerify'])
        ->name('auth.emailVerify');
});


Route::resource('article', ArticleController::class)
    ->only(['index', 'show']);

Route::resource('author', AuthorController::class)
    ->only(['index', 'show']);

Route::resource('category', CategoryController::class)
    ->only(['index', 'show']);

Route::resource('subscription', SubscriptionController::class)
    ->only(['store']);

Route::resource('tag', TagController::class)
    ->only(['index', 'show']);


Route::middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::resource('article', ArticleController::class)
            ->only(['update', 'store', 'destroy']);

        Route::resource('author', AuthorController::class)
            ->only(['update', 'store', 'destroy']);

        Route::resource('category', CategoryController::class)
            ->only(['update', 'store', 'destroy']);

        Route::resource('subscription', SubscriptionController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('tag', TagController::class)
            ->only(['update', 'store', 'destroy']);
    });
