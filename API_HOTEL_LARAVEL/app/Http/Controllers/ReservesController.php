<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reserves",
     *     tags={"Reserves"},
     *     summary="Lista todas as reservas",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Response(response=200, description="Lista de reservas")
     * )
     */
    public function index()
    {
        try {
            $reserves = DB::table('reserves')->whereNull('deleted_at')->get();
            return response()->json($reserves, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao listar reservas: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao listar reservas'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reserves",
     *     tags={"Reserves"},
     *     summary="Cadastra uma nova reserva",
     *     description="Acesso permitido para administradores",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hotelCode","roomCode","checkIn","checkOut","total"},
     *             @OA\Property(property="hotelCode", type="string", example="H123"),
     *             @OA\Property(property="roomCode", type="string", example="R456"),
     *             @OA\Property(property="checkIn", type="string", format="date", example="2023-04-01"),
     *             @OA\Property(property="checkOut", type="string", format="date", example="2023-04-05"),
     *             @OA\Property(property="total", type="number", format="float", example=1000.00),
     *             @OA\Property(property="coupon", type="string", example="DISCOUNT10"),
     *             @OA\Property(property="additional_charges", type="number", format="float", example=50.00)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reserva criada com sucesso"),
     *     @OA\Response(response=500, description="Falha ao criar a reserva"),
     *     @OA\Response(response=404, description="Quarto não encontrado")
     * )
     */
    public function store(ReservesRequests $request)
    {
        try {
            $data = $request->validated();
            $discountValue = $this->validatedCoupon($data['coupon'] ?? null);
            $data['total'] = $this->calcTotal($data, $discountValue);

            $room = DB::table('rooms')->where('id', $data['roomCode'])->first();
            if (!$room) {
                return response()->json(['message' => 'Quarto não encontrado'], 404);
            }

            if ($room->availability > 0) {
                $reserve = DB::table('reserves')->insert([
                    'hotelCode' => $data['hotelCode'],
                    'roomCode' => $data['roomCode'],
                    'checkIn' => $data['checkIn'],
                    'checkOut' => $data['checkOut'],
                    'total' => $data['total'],
                    'discounts' => $discountValue,
                    'additional_charges' => $data['additional_charges'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                if (!$reserve) {
                    throw new \Exception('Falha ao inserir a reserva');
                }

                DB::table('rooms')->where('id', $data['roomCode'])->decrement('availability', 1);
                return response()->json(['message' => 'Reserva criada com sucesso'], 201);
            } else {
                return response()->json(['message' => 'Quarto indisponível'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Erro ao criar reserva: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao criar reserva'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reserves/{id}",
     *     tags={"Reserves"},
     *     summary="Exibe os detalhes de uma reserva específica",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes da reserva"),
     *     @OA\Response(response=404, description="Reserva não encontrada")
     * )
     */
    public function show(string $id)
    {
        try {
            $reserve = DB::table('reserves')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$reserve) {
                return response()->json(['message' => 'Reserva não encontrada'], 404);
            }

            return response()->json($reserve, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao exibir reserva: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao exibir reserva'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/reserves/{id}",
     *     tags={"Reserves"},
     *     summary="Atualiza uma reserva",
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
     *             required={"hotelCode","roomCode","checkIn","checkOut","total"},
     *             @OA\Property(property="hotelCode", type="string", example="H123"),
     *             @OA\Property(property="roomCode", type="string", example="R456"),
     *             @OA\Property(property="checkIn", type="string", format="date", example="2023-04-01"),
     *             @OA\Property(property="checkOut", type="string", format="date", example="2023-04-05"),
     *             @OA\Property(property="total", type="number", format="float", example=1000.00),
     *             @OA\Property(property="coupon", type="string", example="DISCOUNT10"),
     *             @OA\Property(property="additional_charges", type="number", format="float", example=50.00)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Reserva atualizada com sucesso"),
     *     @OA\Response(response=500, description="Falha ao atualizar a reserva")
     * )
     */
    public function update(ReservesRequests $request, string $id)
    {
        try {
            $data = $request->validated();
            $discountValue = $this->validatedCoupon($data['coupon'] ?? null);
            $data['total'] = $this->calcTotal($data, $discountValue);

            $reservePut = DB::table('reserves')->where('id', $id)->update([
                'hotelCode' => $data['hotelCode'],
                'roomCode' => $data['roomCode'],
                'checkIn' => $data['checkIn'],
                'checkOut' => $data['checkOut'],
                'total' => $data['total'],
                'discounts' => $discountValue,
                'additional_charges' => $data['additional_charges'],
                'updated_at' => now()
            ]);

            if ($reservePut === 0) {
                return response()->json(['message' => 'Reserva não encontrada ou não atualizada'], 500);
            }

            return response()->json(['message' => 'Atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar reserva: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao atualizar reserva'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/reserves/{id}",
     *     tags={"Reserves"},
     *     summary="Exclui uma reserva",
     *     description="Acesso permitido para administradores",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Reserva excluída com sucesso"),
     *     @OA\Response(response=500, description="Falha ao excluir a reserva")
     * )
     */
    public function destroy(string $id)
    {
        try {
            $reserve = DB::table('reserves')->where('id', $id)->update(['deleted_at' => now()]);

            if (!$reserve) {
                return response()->json(['message' => 'Reserva não encontrada ou não excluída'], 500);
            }

            return response()->json(['message' => 'Reserva excluída com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error("Erro ao excluir reserva: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao excluir reserva'], 500);
        }
    }

    private function validatedCoupon($couponCode)
    {
        $discountValue = 0;
        if ($couponCode) {
            $coupon = DB::table('coupons')->where('code', $couponCode)->first();
            if (!$coupon) {
                abort(404, 'Cupom inválido');
            }
            $discountValue = $coupon->discount_value;
        }

        return $discountValue;
    }

    private function calcTotal(array $data, $discountValue)
    {
        $total = $data['total'] - $discountValue;
        $total += $data['additional_charges'];

        return $total;
    }
}
