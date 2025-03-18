<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // Return authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Image resource routes
    Route::apiResource('/images', ImageController::class)
        ->only(['index', 'store', 'destroy'])
        ->names([
            'index' => 'images.index',
            'store' => 'images.store',
            'destroy' => 'images.destroy',
        ]);

    // Custom route for images by date
    Route::get('/images/date/{date}', [ImageController::class, 'byDate'])
        ->name('images.byDate');
});
