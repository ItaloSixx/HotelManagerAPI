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
        $data = $request->validated();//em discounts eu preciso estar passando um cupom, verificar se ele é valido e subtrair o valor

        $data['total'] -= $data['discounts'];
        $data['total'] += $data['additional_charges'];

        $reserve = DB::table('reserves')->insert([
            'hotelCode' => $data['hotelCode'],
            'roomCode' => $data['roomCode'],
            'checkIn' => $data['checkIn'],
            'checkOut' => $data['checkOut'],
            'total' => $data['total'],
            'discounts' => $data['discounts'],
            'additional_charges' => $data['additional_charges'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if(!$reserve){
            return response()->json([
                'message' => 'Reserva não realizada'
            ], 500);
        }

        return response()->json([
            'message' => 'Reserva realizada'
        ], 201);
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

        $reservePut = DB::table('reserves')->where('id', $id)->update([
            'hotelCode' => $data['hotelCode'],
            'roomCode' => $data['roomCode'],
            'checkIn' => $data['checkIn'],
            'checkOut' => $data['checkOut'],
            'total' => $data['total'],
            'discounts' => $data['discounts'],
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

    public function getGuests($id)
    {
        $guests = DB::table('reserve_guests')
                    ->join('guests', 'reserve_guests.guestId', '=', 'guests.id')
                    ->where('reserve_guests.reserveId', $id)
                    ->select('guests.*')
                    ->get();

        return response()->json($guests, 200);
    }

}
