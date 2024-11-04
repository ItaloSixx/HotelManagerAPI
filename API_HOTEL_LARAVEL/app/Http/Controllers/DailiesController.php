<?php

namespace App\Http\Controllers;

use App\Http\Requests\DailiesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailiesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dailies",
     *     tags={"Dailies"},
     *     summary="Lista todas as diárias",
     *     @OA\Response(response=200, description="Lista de diárias")
     * )
     */
    public function index()
    {
        $Dailies = DB::table('dailies')->get();

        return response()->json($Dailies, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/dailies",
     *     tags={"Dailies"},
     *     summary="Cadastra uma nova diária",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reserveId","date","value"},
     *             @OA\Property(property="reserveId", type="string", example="R123"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-04-01"),
     *             @OA\Property(property="value", type="number", format="float", example=200.00)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Diária cadastrada com sucesso"),
     *     @OA\Response(response=500, description="Diária não cadastrada")
     * )
     */
    public function store(DailiesRequest $request)
    {
        $data = $request->validated();

        $daily = DB::table('dailies')->insert([
            'reserveId' => $data['reserveId'],
            'date' => $data['date'],
            'value' => $data['value'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (!$daily) {
            return response()->json([
                'message' => 'Diária não cadastrada'
            ], 500);
        }

        return response()->json([
            'message' => 'Diária cadastrada com sucesso'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/dailies/{id}",
     *     tags={"Dailies"},
     *     summary="Exibe os detalhes de uma diária específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes da diária"),
     *     @OA\Response(response=404, description="Diária não encontrada")
     * )
     */
    public function show(string $id)
    {
        $daily = DB::table('dailies')->where('id', $id)->first();

        if(!$daily){
            return response()->json([
                'message' => 'Daily não encontrada'
            ], 404);
        }

        return response()->json($daily, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/dailies/{id}",
     *     tags={"Dailies"},
     *     summary="Atualiza uma diária",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reserveId","date","value"},
     *             @OA\Property(property="reserveId", type="string", example="R123"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-04-01"),
     *             @OA\Property(property="value", type="number", format="float", example=200.00)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Diária atualizada com sucesso"),
     *     @OA\Response(response=500, description="Diária não atualizada")
     * )
     */
    public function update(DailiesRequest $request, string $id)
    {
        $data = $request->validated();

        $dailyPut = DB::table('dailies')->where('id', $id)->update([
            'reserveId' => $data['reserveId'],
            'date' => $data['date'],
            'value' => $data['value'],
            'updated_at' => now()
        ]);

        if($dailyPut === 0){
            return response()->json([
                'message' => 'Diária não atualizada'
            ], 500);
        }

        return response()->json([
            'message' => 'Diária atualizada com sucesso'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/dailies/{id}",
     *     tags={"Dailies"},
     *     summary="Exclui uma diária",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Diária excluída com sucesso"),
     *     @OA\Response(response=500, description="Diária não excluída")
     * )
     */
    public function destroy(string $id)
    {
        $dailyDel = DB::table('dailies')
                        ->where('id', $id)
                        ->delete();

        if(!$dailyDel){
            return response()->json([
                'message' => 'Diária não excluída'
            ], 500);
        }

        return response()->json([
            'message' => 'Diária excluída com sucesso'
        ]);
    }
}
