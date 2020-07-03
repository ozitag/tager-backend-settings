<?php

use Illuminate\Support\Facades\Route;

Route::get('/tager/settings', \OZiTAG\Tager\Backend\Settings\Controllers\PublicController::class . '@view');

