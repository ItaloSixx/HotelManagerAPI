<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservesController extends Controller
{

    public function index()
    {
        $reserves = DB::table('reserves')->whereNull('deleted_at')->get();

        return response()->json($reserves, 201);
    }


    public function store(ReservesRequests $request)
    {
        $data = $request->validated();

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
        $reserve = DB::table('reserves')->where('id', $id)->whereNull('deleted_at')->first();

        if(!$reserve){
            return response()->json([
                'message' => 'Reserva não encontrada'
            ], 500);
        }

        return response()->json($reserve, 200);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
