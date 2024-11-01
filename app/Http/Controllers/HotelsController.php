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
