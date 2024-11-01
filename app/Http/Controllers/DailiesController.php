<?php

namespace App\Http\Controllers;

use App\Http\Requests\DailiesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailiesController extends Controller
{

    public function index()
    {
        $Dailies = DB::table('dailies')->get();

        return response()->json($Dailies, 201);
    }


    public function store(DailiesRequest $request)
    {
        $data = $request->validated();

        $daily = DB::table('dailies')->insert([
            'reserveId' => $data['reserveId'],
            'date' => $data['date'],
            'value' => $data['value'],
            'created_at' => now(),
            'updated_at' => now()
        ], 500);

        if(!$daily){
            return response()->json([
                'message' => 'Diária não cadastrada'
            ]);
        }

        return response()->json([
            'message' => 'Diária cadastrada com sucesso'
        ], 201);
    }


    public function show(string $id)
    {
        $daily = DB::table('dailies')->where('id', $id)->first();

        if(!$daily){
            return response()->json([
                'message' => 'Daily não encontrada'
            ], 404);
        }

        return response()->json($id, 201);
    }


    public function update(DailiesRequest $request, string $id)
    {
        
    }


    public function destroy(string $id)
    {
        //
    }
}
