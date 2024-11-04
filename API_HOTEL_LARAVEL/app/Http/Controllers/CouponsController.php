<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coupons",
     *     tags={"Coupons"},
     *     summary="Lista todos os cupons",
     *     @OA\Response(response=200, description="Lista de cupons"),
     *     description="Acesso permitido para administradores e recepcionistas"
     * )
     */
    public function index()
    {
        try {
            $coupons = DB::table('coupons')->whereNull('deleted_at')->get();
            return response()->json($coupons, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao listar cupons: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar cupons'], 500);
        }
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
     *     @OA\Response(response=500, description="Erro ao criar o cupom"),
     *     description="Acesso permitido para administradores e recepcionistas"
     * )
     */
    public function store(CouponRequest $request)
    {
        try {
            $data = $request->validated();

            $coupon = DB::table('coupons')->insert([
                'code' => $data['code'],
                'discount_value' => $data['discount_value'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (!$coupon) {
                return response()->json(['message' => 'Erro ao criar o cupom'], 500);
            }

            return response()->json(['message' => 'Cupom criado com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar cupom: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao criar o cupom'], 500);
        }
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
     *     @OA\Response(response=404, description="Cupom não encontrado"),
     *     description="Acesso permitido para administradores e recepcionistas"
     * )
     */
    public function show(string $id)
    {
        try {
            $coupon = DB::table('coupons')
                        ->where('id', $id)
                        ->whereNull('deleted_at')
                        ->first();

            if (!$coupon) {
                return response()->json(['message' => 'Cupom não encontrado'], 404);
            }

            return response()->json($coupon, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar cupom: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao buscar o cupom'], 500);
        }
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
     *     @OA\Response(response=500, description="Cupom não encontrado ou não atualizado"),
     *     description="Acesso permitido para administradores e recepcionistas"
     * )
     */
    public function update(CouponRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $couponPut = DB::table('coupons')->where('id', $id)->update([
                'code' => $data['code'],
                'discount_value' => $data['discount_value'],
                'updated_at' => now()
            ]);

            if ($couponPut === 0) {
                return response()->json(['message' => 'Cupom não encontrado ou não atualizado'], 500);
            }

            return response()->json(['message' => 'Atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cupom: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar o cupom'], 500);
        }
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
     *     @OA\Response(response=200, description="Cupom excluído com sucesso"),
     *     @OA\Response(response=500, description="Cupom não excluído"),
     *     description="Acesso permitido para administradores e recepcionistas"
     * )
     */
    public function destroy(string $id)
    {
        try {
            $couponDel = DB::table('coupons')->where('id', $id)->update(['deleted_at' => now()]);

            if (!$couponDel) {
                return response()->json(['message' => 'Cupom não excluído'], 500);
            }

            return response()->json(['message' => 'Excluído com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir cupom: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao excluir o cupom'], 500);
        }
    }
}
