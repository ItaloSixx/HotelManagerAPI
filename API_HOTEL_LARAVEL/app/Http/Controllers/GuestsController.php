<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestsRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuestsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/guests",
     *     tags={"Guests"},
     *     summary="Lista todos os hóspedes",
     *     security={{"bearer": {}}},
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Response(response=200, description="Lista de hóspedes"),
     *     @OA\Response(response=403, description="Acesso negado")
     * )
     */
    public function index()
    {
        try {
            $guests = DB::table('guests')->whereNull('deleted_at')->get();
            return response()->json($guests, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao listar hóspedes: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar hóspedes'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/guests",
     *     tags={"Guests"},
     *     summary="Cadastra um novo hóspede",
     *     security={{"bearer": {}}},
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","lastname","phone"},
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="lastname", type="string", example="Doe"),
     *             @OA\Property(property="phone", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Hóspede cadastrado com sucesso"),
     *     @OA\Response(response=500, description="Hóspede não cadastrado")
     * )
     */
    public function store(GuestsRequests $request)
    {
        $data = $request->validated();

        try {
            $guestAdd = DB::table('guests')->insert([
                'name' => $data['name'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (!$guestAdd) {
                return response()->json(['message' => 'Hóspede não cadastrado'], 500);
            }

            return response()->json(['message' => 'Hóspede cadastrado com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar hóspede: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao cadastrar hóspede'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Exibe os detalhes de um hóspede específico",
     *     security={{"bearer": {}}},
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do hóspede"),
     *     @OA\Response(response=404, description="Hóspede não encontrado"),
     *     @OA\Response(response=403, description="Acesso negado")
     * )
     */
    public function show(string $id)
    {
        try {
            $guest = DB::table('guests')->where('id', $id)->whereNull('deleted_at')->first();

            if (!$guest) {
                return response()->json(['message' => 'Hóspede não encontrado'], 404);
            }

            return response()->json($guest, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao exibir hóspede: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao exibir hóspede'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Atualiza um hóspede",
     *     security={{"bearer": {}}},
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","lastname","phone"},
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="lastname", type="string", example="Doe"),
     *             @OA\Property(property="phone", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Hóspede atualizado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao atualizar o hóspede")
     * )
     */
    public function update(GuestsRequests $request, string $id)
    {
        $data = $request->validated();

        try {
            $guestPut = DB::table('guests')->where('id', $id)->update([
                'name' => $data['name'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone'],
                'updated_at' => now()
            ]);

            if ($guestPut === 0) {
                return response()->json(['message' => 'Falha ao atualizar o hóspede'], 500);
            }

            return response()->json(['message' => 'Hóspede atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar hóspede: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar hóspede'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Exclui um hóspede",
     *     security={{"bearer": {}}},
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Hóspede excluído com sucesso"),
     *     @OA\Response(response=500, description="Falha ao excluir o hóspede")
     * )
     */
    public function destroy(string $id)
    {
        try {
            $guest = DB::table('guests')->where('id', $id)->update(['deleted_at' => now()]);

            if (!$guest) {
                return response()->json(['message' => 'Falha ao excluir o hóspede'], 500);
            }

            return response()->json(['message' => 'Hóspede excluído com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir hóspede: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao excluir hóspede'], 500);
        }
    }
}
