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

        return response()->json($daily, 201);
    }


    public function update(DailiesRequest $request, string $id)
    {
        $data = $request->validated();

        $dailyPut = DB::table('dailies')->where('id', $id)->update([
            'reserveId' => $data['reserveId'],
            'date' => $data['date'],
            'value' => $data['value'],
            'updated_at' => now()
        ]);

        if($dailyPut === 0){
            return response()->json([
                'message' => 'Diária não atualizada'
            ], 500);
        }

        return response()->json([
            'message' => 'Diária atualizada com sucesso'
        ], 201);
    }


    public function destroy(string $id)
    {
        $dailyDel = DB::table('dailies')
                        ->where('id', $id)
                        ->update(['deleted_at' => now()]);

        if(!$dailyDel){
            return response()->json([
                'message' => 'Diária não excluída'
            ], 500);
        }

        return response()->json([
            'message' => 'Diária excluída com sucesso'
        ]);
    }
}
