<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\ProductController;
use Workbench\App\Http\Controllers\SiteController;
use Workbench\App\Http\Controllers\UserController;

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::post('register', 'register');

    Route::middleware('auth')->group(function () {
        Route::get('me', 'me');
    });
});

Route::prefix('sites')->controller(SiteController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', 'index');
        Route::middleware('tenancy.scope.bypass')->get('/all', 'index');
        Route::get('/{site}', 'show');
        Route::post('/', 'store');
    });
});

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', 'index');
        Route::middleware('tenancy.scope.bypass')->get('/all', 'index');
        Route::get('/{product}', 'show');
        Route::post('/', 'store');
    });
});
