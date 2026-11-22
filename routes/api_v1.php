<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserProductsController;

// Public routes: no token needed
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes: the auth:api middleware rejects requests without a valid token (401)
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::apiResource('products', ProductController::class)->only(['index', 'show', 'store', 'destroy']);

    // PUT replaces the whole product (our own method name: replace)
    Route::put('products/{product}', [ProductController::class, 'replace']);

    // Authors are read only: list and show
    Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

    // Products of one user: GET /users/{user}/products
    Route::apiResource('users.products', UserProductsController::class)->only(['index']);
});