<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{

    public function index()
    {
        $rooms = DB::table('rooms')->get();

        return response()->json($rooms, 200);
    }

    public function store(RoomRequests $request)
    {
        $data = $request->validated();

        $roomAdd = DB::table('rooms')->insert([
            'hotelCode' => $data['hotelCode'],
            'name' => $data['name'],
            'availability' => $data['availability'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (!$roomAdd) {
            return response()->json([
                'message' => 'Falha ao criar quarto'
            ], 500);
        }
    }


    public function show(string $id)
    {
        $room = DB::table('rooms')->where('id', $id)->first();

        if (!$room) {
            return response()->json([
                'message' => 'Quarto não encontrado'
            ], 404);
        }

        return response()->json($room, 200);
    }


    public function update(RoomRequests $request, string $id)
    {
        $data = $request->validated();

        $roomPut = DB::table('rooms')->where('id', $id)->update([
            'hotelCode' => $data['hotelCode'],
            'name' => $data['name'],
            'availability' => $data['availability'],
            'updated_at' => now()
        ]);

        if (!$roomPut) {
            return response()->json([
                'message' => 'Falha ao atualizar quarto'
            ], 500);
        }

        return response()->json([
            'message' => 'Quarto atualizado com sucesso'
        ]);
    }


    public function destroy(string $id)
    {
        //
    }
}
