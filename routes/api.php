<?php

use App\Http\Controllers\Api\V1\TransactionApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => "laravel framework: " . app()->version()
    ]);
});


Route::group(['prefix' => 'v1', 'middleware' => 'api', 'namespace' => 'Api/V1'], function () {

    Route::post('user', [UserApiController::class, 'store']);

    Route::post('transactions', [TransactionApiController::class, 'store']);
});
