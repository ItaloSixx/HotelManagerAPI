<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelsRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelsController extends Controller
{

    public function index()
    {
        $hotels = DB::table('hotels')->get();
    }


    public function store(HotelsRequests $request)
    {
        $data = $request->validate();

        $hotelAdd = DB::table('hotels')->insert([
            'name' => $data['name']
        ]);

        if(!$hotelAdd){
            return response()->json([
                'message' => 'Falha ao cadastrar hotel'
            ],500);
        }

        return response()->json([
            'message' => 'Hotel cadastrado com sucesso'
        ],201);
    }

    public function show(string $id)
    {
        $hotel = DB::table('hotels')->where('id', $id)->first();

        if(!$hotel){
            return response()->json([
                'message' => 'Hotel não encontrado'
            ], 404);
        }

        return response()->json($hotel);
    }


    public function update(HotelsRequests $request, string $id)
    {
        
    }


    public function destroy(string $id)
    {
        //
    }
}
