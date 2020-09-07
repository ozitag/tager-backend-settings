<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'middleware' => 'api.cache'], function () {
    Route::get('/tager/settings', [\OZiTAG\Tager\Backend\Settings\Controllers\PublicController::class, 'view']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/settings', [\OZiTAG\Tager\Backend\Settings\Controllers\AdminController::class, 'index']);
    Route::get('/settings/{id}', [\OZiTAG\Tager\Backend\Settings\Controllers\AdminController::class, 'view']);
    Route::put('/settings/{id}', [\OZiTAG\Tager\Backend\Settings\Controllers\AdminController::class, 'update']);
});
