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

Route::prefix('sites')->middleware('auth')->controller(SiteController::class)->group(function () {
    Route::get('/', 'index');
    Route::middleware('tenancy.scope.bypass')->get('/all', 'index');
    Route::get('/{site}', 'show');
    Route::middleware('tenancy.scope.bypass')->get('/{site}/by-pass-example', 'show');
    Route::post('/', 'store');
    Route::post('/no-auto-assign', 'storeNoAutoAssign');
});

Route::prefix('products')->middleware('auth')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::middleware('tenancy.scope.bypass')->get('/all', 'index');
    Route::get('/{product}', 'show');
    Route::post('/', 'store');
});
