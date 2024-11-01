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
