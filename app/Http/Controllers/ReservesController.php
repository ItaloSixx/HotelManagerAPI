<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservesController extends Controller
{

    public function index()
    {
        //tratar com if se der false
        $reserves = DB::table('reserves')->whereNull('deleted_at')->get();

        return response()->json($reserves, 201);
    }

    public function store(ReservesRequests $request)
    {
        $data = $request->validated();

        $discountValue = $this->validatedCoupon($data['coupon'] ?? null);

        $data['total'] = $this->calcTotal($data, $discountValue);

        $room = DB::table('rooms')->where('id', $data['roomCode'])->first();
        if(!$room) {
            return response()->json(['message' => 'Quarto não encontrado'], 404);
        }

        if($room->availability > 0)
        {
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
                return response()->json(['message' => 'Falha ao inserir a reserva'], 500);
            }

            DB::table('rooms')->where('id', $data['roomCode'])->decrement('availability', 1);

            return response()->json(['message' => 'Reserva criada com sucesso'], 201);
        } else {
            return response()->json(['message' => 'Quarto indisponível'], 500);

        }
    }



    public function show(string $id)
    {
        $reserve = DB::table('reserves')
                       ->where('id', $id)
                       ->whereNull('deleted_at')
                       ->first();

        if(!$reserve){
            return response()->json([
                'message' => 'Reserva não encontrada'
            ], 404);
        }

        return response()->json($reserve, 200);
    }


    public function update(ReservesRequests $request, string $id)
    {
        $data = $request->validated();

        $discountValue = $this->validatedCoupon($data['coupon'] ?? null);

        $data['total'] = $this->calcTotal($data, $discountValue);

        $reservePut = DB::table('reserves')->where('id', $id)->update([
            'hotelCode' => $data['hotelCode'],
            'roomCode' => $data['roomCode'],
            'checkIn' => $data['checkIn'],
            'checkOut' => $data['checkOut'],
            'total' => $data['total'],
            'discounts' => $data['coupon'],
            'additional_charges' => $data['additional_charges'],
            'updated_at' => now()
        ]);

        if($reservePut === 0){
            return response()->json([
                'message' => 'Reserva não encontrada ou não atualizada'
            ], 500);
        }

        return response()->json([
            'message' => 'Atualizado com sucesso'
        ], 201);
    }


    public function destroy(string $id)
    {
        $reserve = DB::table('reserves')->where('id', $id)->update(['deleted_at'=> now()]);

        if(!$reserve){
            return response()->json([
                'message'=> 'Reserva não encontrada ou não excluída'
            ], 500);
        }

        return response()->json([
            'message' => 'Reserva excluída'
        ], 201);
    }

    private function validatedCoupon($cuponCode){
        $discountValue = 0;
        if($cuponCode){
            $coupon = DB::table('coupons')->where('code', $cuponCode)->first();
            if(!$coupon){
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


