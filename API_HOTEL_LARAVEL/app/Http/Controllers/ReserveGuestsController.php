<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveGuestsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReserveGuestsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reserves/{reserveId}/addGuest",
     *     tags={"Reserve Guests"},
     *     summary="Adiciona um hóspede a uma reserva",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     security={{ "sanctum": {} }},
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
     *     @OA\Response(response=200, description="Hóspede adicionado à reserva"),
     *     @OA\Response(response=404, description="Hóspede ou reserva não existem")
     * )
     */
    public function addGuest(ReserveGuestsRequest $request, $reserveId)
    {
        try {
            $data = $request->validated();

            $guestAdd = DB::table('reserve_guests')->insert([
                'reserveId' => $reserveId,
                'guestId' => $data['guestId'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (!$guestAdd) {
                Log::error("Hóspede ou reserva não existem");
                return response()->json(['message' => 'Hóspede ou reserva não existem'], 404);
            }

            return response()->json(['message' => 'Hóspede adicionado à reserva'], 200);
        } catch (\Exception $e) {
            Log::error("Erro ao adicionar hóspede à reserva: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao adicionar hóspede à reserva'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/reserves/{reserveId}/{guestId}/rmvGuest",
     *     tags={"Reserve Guests"},
     *     summary="Remove um hóspede de uma reserva",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     security={{ "sanctum": {} }},
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
     *     @OA\Response(response=200, description="Hóspede removido da reserva"),
     *     @OA\Response(response=500, description="Hóspede ou reserva não existem")
     * )
     */
    public function rmvGuest($reserveId, $guestId)
    {
        try {
            $guestRmv = DB::table('reserve_guests')
                ->where('reserveId', $reserveId)
                ->where('guestId', $guestId)
                ->delete();

            if (!$guestRmv) {
                Log::error("Hóspede ou reserva não existem");
                return response()->json(['message' => 'Hóspede ou reserva não existem'], 500);
            }

            return response()->json(['message' => 'Hóspede removido da reserva'], 200);
        } catch (\Exception $e) {
            Log::error("Erro ao remover hóspede da reserva: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao remover hóspede da reserva'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reserves/{reserveId}/getGuests",
     *     tags={"Reserve Guests"},
     *     summary="Lista todos os hóspedes de uma reserva",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="reserveId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Lista de hóspedes")
     * )
     */
    public function getGuests($reserveId)
    {
        try {
            $guests = DB::table('reserve_guests')
                ->join('guests', 'reserve_guests.guestId', '=', 'guests.id')
                ->where('reserve_guests.reserveId', $reserveId)
                ->get();

            return response()->json($guests, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao listar hóspedes da reserva: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar hóspedes'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reserves/{guestId}/getReserves",
     *     tags={"Reserve Guests"},
     *     summary="Lista todas as reservas de um hóspede",
     *     description="Acesso permitido para usuários autenticados",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="guestId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Lista de reservas")
     * )
     */
    public function getReserves($guestId)
    {
        try {
            $reserves = DB::table('reserve_guests')
                ->join('reserves', 'reserve_guests.reserveId', '=', 'reserves.id')
                ->where('reserve_guests.guestId', $guestId)
                ->get();

            return response()->json($reserves, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao listar reservas do hóspede: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar reservas'], 500);
        }
    }
}
