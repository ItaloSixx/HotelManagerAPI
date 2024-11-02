<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestsRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/guests",
     *     tags={"Guests"},
     *     summary="Lista todos os hóspedes",
     *     @OA\Response(response=200, description="Lista de hóspedes")
     * )
     */
    public function index()
    {
        $guests = DB::table('guests')->whereNull('deleted_at')->get();

        return response()->json($guests, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/guests",
     *     tags={"Guests"},
     *     summary="Cadastra um novo hóspede",
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

        $guestAdd = DB::table('guests')->insert([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if(!$guestAdd){
            return response()->json([
                'message' => 'Hospede não cadastrado'
            ], 500);
        }

        return response()->json([
            'message' => 'Hospede cadastrado com sucesso'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Exibe os detalhes de um hóspede específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do hóspede"),
     *     @OA\Response(response=404, description="Hóspede não encontrado")
     * )
     */
    public function show(string $id)
    {
        $client = DB::table('guests')->where('id', $id)->whereNull('deleted_at')->first();

        if(!$client){
            return response()->json([
                'message' => 'Hospede não encontrado'
            ], 404);
        }

        return response()->json($client, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Atualiza um hóspede",
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
     *     @OA\Response(response=201, description="Hóspede atualizado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao atualizar o hóspede")
     * )
     */
    public function update(GuestsRequests $request, string $id)
    {
        $data = $request->validated();

        $guestPut = DB::table('guests')->where('id', $id)->update([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'],
            'updated_at' => now()
        ]);

        if($guestPut === 0){
            return response()->json([
                'message' => 'Falha ao atualizar o hospede'
            ], 500);
        }

        return response()->json([
            'message' => 'Hospede atualizado'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Exclui um hóspede",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=201, description="Hóspede excluído com sucesso"),
     *     @OA\Response(response=500, description="Falha ao excluir o hóspede")
     * )
     */
    public function destroy(string $id)
    {
        $guest = DB::table('guests')->where('id', $id)->update(['deleted_at' => now()]);

        if(!$guest){
            return response()->json([
                'message' => 'Falha ao excluir o hospede'
            ], 500);
        }

        return response()->json([
            'message' => 'Hospede exclúido com sucesso'
        ], 201);
    }
}
