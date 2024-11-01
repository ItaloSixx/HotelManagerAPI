<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestsRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestsController extends Controller
{

    public function index()
    {
        $guests = DB::table('guests')->whereNull('deleted_at')->get();

        return response()->json($guests, 201);
    }


    public function store(GuestsRequests $request)
    {
        $data = $request->validated();

        $guestAdd = DB::table('guests')->insert([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if(!$guestAdd){
            return response()->json([
                'message' => 'Hospede não cadastrado'
            ], 500);
        }

        return response()->json([
            'message' => 'Hospede cadastrado com sucesso'
        ], 201);
    }


    public function show(string $id)
    {
        $client = DB::table('guests')->where('id', $id)->whereNull('deleted_at')->first();

        if(!$client){
            return response()->json([
                'message' => 'Hospede não encontrado'
            ], 404);
        }

        return response()->json($client, 201);
    }


    public function update(GuestsRequests $request, string $id)
    {
        $data = $request->validated();

        $guestPut = DB::table('guests')->where('id', $id)->update([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'],
            'updated_at' => now()
        ]);

        if($guestPut === 0){
            return response()->json([
                'message' => 'Falha ao atualizar o hospede'
            ], 500);
        }

        return response()->json([
            'message' => 'Hospede atualizado'
        ], 201);
    }


    public function destroy(string $id)
    {
        
    }
}
