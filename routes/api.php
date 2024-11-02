<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\DailiesController;
use App\Http\Controllers\GuestsController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReserveGuestsController;
use App\Http\Controllers\ReservesController;
use App\Http\Controllers\RoomController;
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
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);
Route::apiResource('coupons', CouponsController::class);
Route::apiResource('reserves', ReservesController::class);
Route::apiResource('dailies', DailiesController::class);
Route::apiResource('guests', GuestsController::class);
Route::apiResource('hotels', HotelsController::class);
Route::apiResource('payments', PaymentsController::class);
Route::apiResource('rooms', RoomController::class);
Route::post('/reserves/{reserveId}/addGuest', [ReserveGuestsController::class, 'addGuest'])->name('addGuest');
Route::get('/reserves/{reserveId}/getGuest', [ReserveGuestsController::class, 'getGuests'])->name('getGuests');
Route::get('/reserves/{guestId}/getReserve', [ReserveGuestsController::class, 'getReserves'])->name('getReserve');
Route::delete('/reserves/{reserveId}/{guestId}/rmvGuest', [ReserveGuestsController::class, 'rmvGuest'])->name('rmvGuest');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


