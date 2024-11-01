<?php

use App\Http\Controllers\CouponsController;
use App\Http\Controllers\DailiesController;
use App\Http\Controllers\ReserveGuestsController;
use App\Http\Controllers\ReservesController;
use App\Http\Controllers\UserController;
use App\Http\Requests\ReserveGuestsRequest;
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
Route::apiResource('dailies', DailiesController::class);
Route::post('/reserves/{reserveId}/addGuest', [ReserveGuestsController::class, 'addGuest'])->name('addGuest');
Route::get('/reserves/{reserveId}/getGuest', [ReserveGuestsController::class, 'getGuests'])->name('getGuests');
Route::delete('/reserves/{reserveId}/{guestId}/rmvGuest', [ReserveGuestsController::class, 'rmvGuest'])->name('rmvGuest');


