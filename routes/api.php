<?php

use App\Http\Controllers\CouponsController;
use App\Http\Controllers\ReservesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/status', function(){
    return response()->json(
    [
        'status' => 'ok',
        'message' => 'API running'

    ],
    200);
});

Route::apiResource('users', UserController::class);
Route::apiResource('coupons', CouponsController::class);
Route::apiResource('reserves', ReservesController::class);
