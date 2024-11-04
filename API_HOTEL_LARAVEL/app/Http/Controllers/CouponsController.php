<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coupons",
     *     tags={"Coupons"},
     *     summary="Lista todos os cupons",
     *     @OA\Response(response=200, description="Lista de cupons")
     * )
     */
    public function index()
    {
        $coupons = DB::table('coupons')->whereNull('deleted_at')->get();

        return response()->json($coupons, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/coupons",
     *     tags={"Coupons"},
     *     summary="Cadastra um novo cupom",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code","discount_value"},
     *             @OA\Property(property="code", type="string", example="DISCOUNT2023"),
     *             @OA\Property(property="discount_value", type="number", format="float", example=20.00)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cupom criado com sucesso"),
     *     @OA\Response(response=500, description="Erro ao criar o cupom")
     * )
     */
    public function store(CouponRequest $request)
    {
        $data = $request->validated();

        $coupon = DB::table('coupons')->insert([
            'code' => $data['code'],
            'discount_value' => $data['discount_value'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (!$coupon) {
            return response()->json([
                'message' => 'Erro ao criar o cupom'
            ], 500);
        }

        return response()->json([
            'message' => 'Cupom criado com sucesso'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/coupons/{id}",
     *     tags={"Coupons"},
     *     summary="Exibe os detalhes de um cupom específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do cupom"),
     *     @OA\Response(response=404, description="Cupom não encontrado")
     * )
     */
    public function show(string $id)
    {
        $coupon = DB::table('coupons')
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->first();

        if(!$coupon){
            return response()->json([
                'message' => 'Cupom não encontrado'
            ], 404);
        }

        return response()->json($coupon, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/coupons/{id}",
     *     tags={"Coupons"},
     *     summary="Atualiza um cupom",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code","discount_value"},
     *             @OA\Property(property="code", type="string", example="DISCOUNT2023"),
     *             @OA\Property(property="discount_value", type="number", format="float", example=20.00)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cupom atualizado com sucesso"),
     *     @OA\Response(response=500, description="Cupom não encontrado ou não atualizado")
     * )
     */
    
    public function update(CouponRequest $request, string $id)
    {
        $data = $request->validated();

        $couponPut = DB::table('coupons')->where('id', $id)->update([
            'code' => $data['code'],
            'discount_value' => $data['discount_value'],
            'updated_at' => now()
        ]);

        if(!$couponPut === 0){
            return response()->json([
                'message' => 'Cupom não encontrado ou não atualizado'
            ], 500);
        }

        return response()->json([
            'message' => 'Atualizado com sucesso'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/coupons/{id}",
     *     tags={"Coupons"},
     *     summary="Exclui um cupom",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=201, description="Cupom excluído com sucesso"),
     *     @OA\Response(response=500, description="Cupom não excluído")
     * )
     */

    public function destroy(string $id)
    {
        $couponDel = DB::table('coupons')->where('id', $id)->update(['deleted_at' => now()]);

        if(!$couponDel){
            return response()->json([
                'message' => 'Cupom não excluído'
            ], 500);
        }

        return response()->json([
            'message' => 'Excluído com sucesso'
        ], 201);
    }
}
