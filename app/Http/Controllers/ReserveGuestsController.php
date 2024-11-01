<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveGuestsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveGuestsController extends Controller
{
    public function addGuest(ReserveGuestsRequest $request, $reserveId){
        $data = $request->validated();

        $guestAdd = DB::table('reserve_guests')->insert([
            'reserveId' => $reserveId,
            'guestId' => $data['guestId'],
            'created_at' => now(),
            'updated_at' => now()
        ], 201);

        if(!$guestAdd){
            return response()->json([
                'message' => 'Hospede ou reserva náo existem'
            ], 404);
        }

        return response()->json([
            'message' => 'Hospede adicionado a reserva'
        ], 201);
    }

    public function rmvGuest($reserveId, $guestId)
    {
        $guestRmv = DB::table('reserve_guests')
                ->where('reserveId', $reserveId)
                ->where('guestId', $guestId)
                ->delete();

        if(!$guestRmv){
            return response()->json([
                'message' => 'Hospede ou reserva não existem'
            ], 500);
        }

        return response()->json([
            'message' => 'Hospede removido'
        ], 201);
    }

    public function getGuests($id)
    {
        $guests = DB::table('reserve_guests')
                    ->join('guests', 'reserve_guests.guestId', '=', 'guests.id')
                    ->where('reserve_guests.reserveId', $id)
                    ->get();

        return response()->json($guests, 200);
    }

    public function getReserves($id)
    {
    $reserves = DB::table('reserve_guests')
                ->join('reserves', 'reserve_guests.reserveId', '=', 'reserves.id')
                ->where('reserve_guests.guestId', $id)
                ->get();

    return response()->json($reserves, 200);
    }
}
