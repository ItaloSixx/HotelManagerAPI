<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="Lista todos os pagamentos",
     *     @OA\Response(response=200, description="Lista de pagamentos")
     * )
     */
    public function index()
    {
        $payments = DB::table('payments')->get();

        return response()->json($payments, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="Cadastra um novo pagamento",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reserveId","value","method","paid"},
     *             @OA\Property(property="reserveId", type="string", example="R123"),
     *             @OA\Property(property="value", type="number", format="float", example=200.00),
     *             @OA\Property(property="method", type="string", example="Credit Card"),
     *             @OA\Property(property="paid", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pagamento criado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao criar pagamento")
     * )
     */
    public function store(PaymentsRequest $request)
    {
        $data = $request->validated();

        $paymentAdd = DB::table('payments')->insert([
            'reserveId' => $data['reserveId'],
            'value' => $data['value'],
            'method' => $data['method'],
            'paid' => $data['paid']
        ]);

        if(!$paymentAdd){
            return response()->json([
                'message' => 'Falha ao criar pagamento'
            ], 500);
        }

        return response()->json([
            'message' => 'Pagamento criado com sucesso'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Exibe os detalhes de um pagamento específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do pagamento"),
     *     @OA\Response(response=404, description="Falha ao buscar pagamento")
     * )
     */
    public function show(string $id)
    {
        $payment = DB::table('payments')->where('id', $id)->first();

        if(!$payment){
            return response()->json([
                'message' => 'Falha ao buscar pagamento'
            ], 500);
        }

        return response()->json($payment, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Atualiza um pagamento",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reserveId","value","method","paid"},
     *             @OA\Property(property="reserveId", type="string", example="R123"),
     *             @OA\Property(property="value", type="number", format="float", example=200.00),
     *             @OA\Property(property="method", type="string", example="Credit Card"),
     *             @OA\Property(property="paid", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pagamento atualizado com sucesso"),
     *     @OA\Response(response=500, description="Falha ao atualizar pagamento")
     * )
     */
    public function update(PaymentsRequest $request, string $id)
    {
        $data = $request->validated();

        $paymentPut = DB::table('payments')->where('id', $id)->update([
            'reserveId' => $data['reserveId'],
            'value' => $data['value'],
            'method' => $data['method'],
            'paid' => $data['paid']
        ]);

        if($paymentPut === 0){
            return response()->json([
                'message' => 'Falha ao atualizar pagamento'
            ], 500);
        }

        return response()->json([
            'message' => 'Pagamento atualizado com sucesso'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Exclui um pagamento",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Pagamento excluído com sucesso"),
     *     @OA\Response(response=500, description="Falha ao excluir pagamento")
     * )
     */
    public function destroy(string $id)
    {
        $paymentDel = DB::table('payments')->where('id', $id)->delete();

        if(!$paymentDel){
            return response()->json([
                'message' => 'Falha ao excluir pagamento'
            ], 500);
        }

        return response()->json([
            'message' => 'Pagamento excluído com sucesso'
        ]);
    }
}
