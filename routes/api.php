<?php

use App\Http\Controllers\Api\V1\TransactionApiController;
use App\Http\Controllers\Api\V1\UserApiController;


use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1', 'middleware' => 'api', 'namespace' => 'Api/V1'], function () {

    Route::post('user', [UserApiController::class, 'store']);

    Route::post('transactions', [TransactionApiController::class, 'store']);
});
