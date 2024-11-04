<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelsRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HotelsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Lista todos os hotéis",
     *     @OA\Response(response=200, description="Lista de hotéis"),
     *     description="Acesso permitido para administradores",
     * )
     */
    public function index()
    {
        try {
            $hotels = DB::table('hotels')->get();
            return response()->json($hotels, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao listar hotéis: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar hotéis'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Cadastra um novo hotel",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Hotel Example")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Hotel cadastrado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao cadastrar hotel"),
     *     description="Acesso permitido para administradores",
     * )
     */
    public function store(HotelsRequests $request)
    {
        $data = $request->validated();

        try {
            $hotelAdd = DB::table('hotels')->insert([
                'name' => $data['name'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (!$hotelAdd) {
                return response()->json(['message' => 'Falha ao cadastrar hotel'], 500);
            }

            return response()->json(['message' => 'Hotel cadastrado com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar hotel: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao cadastrar hotel'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Exibe os detalhes de um hotel específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do hotel"),
     *     @OA\Response(response=404, description="Hotel não encontrado"),
     *     description="Acesso permitido para administradores",
     * )
     */
    public function show(string $id)
    {
        try {
            $hotel = DB::table('hotels')->where('id', $id)->first();

            if (!$hotel) {
                return response()->json(['message' => 'Hotel não encontrado'], 404);
            }

            return response()->json($hotel, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar hotel: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao buscar hotel'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Atualiza um hotel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Hotel Updated Example")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Hotel atualizado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao atualizar hotel"),
     *     description="Acesso permitido para administradores",
     * )
     */
    public function update(HotelsRequests $request, string $id)
    {
        $data = $request->validated();

        try {
            $hotelPut = DB::table('hotels')->where('id', $id)->update([
                'name' => $data['name'],
                'updated_at' => now()
            ]);

            if ($hotelPut === 0) {
                return response()->json(['message' => 'Falha ao atualizar hotel'], 500);
            }

            return response()->json(['message' => 'Hotel atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar hotel: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar hotel'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Exclui um hotel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Hotel excluído com sucesso"),
     *     @OA\Response(response=500, description="Falha ao apagar hotel"),
     *     description="Acesso permitido para administradores",
     * )
     */
    public function destroy(string $id)
    {
        try {
            $hotelDel = DB::table('hotels')->where('id', $id)->delete();

            if (!$hotelDel) {
                return response()->json(['message' => 'Falha ao apagar hotel'], 500);
            }

            return response()->json(['message' => 'Hotel excluído com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir hotel: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao excluir hotel'], 500);
        }
    }
}
