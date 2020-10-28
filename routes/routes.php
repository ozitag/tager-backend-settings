<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Rbac\Facades\AccessControlMiddleware;
use OZiTAG\Tager\Backend\Settings\Enums\SettingScope;


Route::group(['middleware' => 'api.cache'], function () {
    Route::get('/tager/settings', [\OZiTAG\Tager\Backend\Settings\Controllers\PublicController::class, 'view']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::group(['middleware' => [AccessControlMiddleware::scopes(SettingScope::View)]], function () {
        Route::get('/settings', [\OZiTAG\Tager\Backend\Settings\Controllers\AdminController::class, 'index']);
        Route::get('/settings/{id}', [\OZiTAG\Tager\Backend\Settings\Controllers\AdminController::class, 'view']);
    });

    Route::group(['middleware' => [AccessControlMiddleware::scopes(SettingScope::Edit)]], function () {
        Route::put('/settings/{id}', [\OZiTAG\Tager\Backend\Settings\Controllers\AdminController::class, 'update']);
    });
});
