<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveGuestsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveGuestsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reserves/{reserveId}/addGuest",
     *     tags={"Reserve Guests"},
     *     summary="Adiciona um hóspede a uma reserva",
     *     @OA\Parameter(
     *         name="reserveId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"guestId"},
     *             @OA\Property(property="guestId", type="string", example="G789")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Hóspede adicionado a reserva"),
     *     @OA\Response(response=404, description="Hóspede ou reserva não existem")
     * )
     */
    public function addGuest(ReserveGuestsRequest $request, $reserveId)
    {
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

    /**
     * @OA\Delete(
     *     path="/api/reserves/{reserveId}/{guestId}/rmvGuest",
     *     tags={"Reserve Guests"},
     *     summary="Remove um hóspede de uma reserva",
     *     @OA\Parameter(
     *         name="reserveId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="guestId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=201, description="Hóspede removido"),
     *     @OA\Response(response=500, description="Hóspede ou reserva não existem")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/reserves/{reserveId}/getGuest",
     *     tags={"Reserve Guests"},
     *     summary="Lista todos os hóspedes de uma reserva",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Lista de hóspedes")
     * )
     */
    public function getGuests($id)
    {
        $guests = DB::table('reserve_guests')
                    ->join('guests', 'reserve_guests.guestId', '=', 'guests.id')
                    ->where('reserve_guests.reserveId', $id)
                    ->get();

        return response()->json($guests, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/reserves/{guestId}/getReserve",
     *     tags={"Reserve Guests"},
     *     summary="Lista todas as reservas de um hóspede",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Lista de reservas")
     * )
     */
    public function getReserves($id)
    {
        $reserves = DB::table('reserve_guests')
                    ->join('reserves', 'reserve_guests.reserveId', '=', 'reserves.id')
                    ->where('reserve_guests.guestId', $id)
                    ->get();

        return response()->json($reserves, 200);
    }
}
