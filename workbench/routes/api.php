<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\SomethingController;
use Workbench\App\Http\Controllers\UserController;

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::post('register', 'register');

    Route::middleware('auth')->group(function () {
       Route::get('me', 'me');
    });
});

Route::prefix('somethings')->controller(SomethingController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', 'index');
        Route::middleware('tenancy.scope.bypass')->get('/all', 'index');
        Route::get('/{something}', 'show');
        Route::post('/', 'store');
    });
});

