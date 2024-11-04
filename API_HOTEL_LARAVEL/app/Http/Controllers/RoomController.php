<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Lista todos os quartos",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Response(response=200, description="Lista de quartos")
     * )
     */
    public function index()
    {
        try {
            $rooms = DB::table('rooms')->get();
            return response()->json($rooms, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao listar quartos: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao listar quartos'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Cadastra um novo quarto",
     *     description="Acesso permitido para administradores",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hotelCode","name","availability"},
     *             @OA\Property(property="hotelCode", type="string", example="H123"),
     *             @OA\Property(property="name", type="string", example="Suite Deluxe"),
     *             @OA\Property(property="availability", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Quarto cadastrado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao criar quarto")
     * )
     */
    public function store(RoomRequests $request)
    {
        $data = $request->validated();

        try {
            $roomAdd = DB::table('rooms')->insert([
                'hotelCode' => $data['hotelCode'],
                'name' => $data['name'],
                'availability' => $data['availability'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (!$roomAdd) {
                return response()->json(['message' => 'Falha ao criar quarto'], 500);
            }

            return response()->json(['message' => 'Quarto cadastrado com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error("Erro ao criar quarto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao criar quarto'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Exibe os detalhes de um quarto específico",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do quarto"),
     *     @OA\Response(response=404, description="Quarto não encontrado")
     * )
     */
    public function show(string $id)
    {
        try {
            $room = DB::table('rooms')->where('id', $id)->first();

            if (!$room) {
                return response()->json(['message' => 'Quarto não encontrado'], 404);
            }

            return response()->json($room, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao exibir quarto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao exibir quarto'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Atualiza um quarto",
     *     description="Acesso permitido para administradores",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hotelCode","name","availability"},
     *             @OA\Property(property="hotelCode", type="string", example="H123"),
     *             @OA\Property(property="name", type="string", example="Suite Deluxe"),
     *             @OA\Property(property="availability", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Quarto atualizado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao atualizar quarto")
     * )
     */
    public function update(RoomRequests $request, string $id)
    {
        $data = $request->validated();

        try {
            $roomPut = DB::table('rooms')->where('id', $id)->update([
                'hotelCode' => $data['hotelCode'],
                'name' => $data['name'],
                'availability' => $data['availability'],
                'updated_at' => now()
            ]);

            if ($roomPut === 0) {
                return response()->json(['message' => 'Falha ao atualizar quarto'], 500);
            }

            return response()->json(['message' => 'Quarto atualizado com sucesso']);
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar quarto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao atualizar quarto'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Exclui um quarto",
     *     description="Acesso permitido para administradores",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Quarto excluído com sucesso"),
     *     @OA\Response(response=500, description="Falha ao excluir quarto")
     * )
     */
    public function destroy(string $id)
    {
        try {
            $roomDel = DB::table('rooms')->where('id', $id)->delete();

            if (!$roomDel) {
                return response()->json(['message' => 'Falha ao excluir quarto'], 500);
            }

            return response()->json(['message' => 'Quarto excluído com sucesso']);
        } catch (\Exception $e) {
            Log::error("Erro ao excluir quarto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao excluir quarto'], 500);
        }
    }
}
