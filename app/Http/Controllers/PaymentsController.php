<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{

    public function index()
    {
        $payments = DB::table('payments')->get();

        return response()->json($payments, 201);
    }


    public function store(PaymentsRequest $request)
    {
        $data = $request->validated();

        $paymentAdd = DB::table('payments')->insert([
            'reserveId' => $data['reserveId'],
            'value' => $data['value'],
            'method' => $data['method'],
            'paid' => $data['paid']
        ]);

        if(!$paymentAdd){
            return response()->json([
                'message' => 'Falha ao criar pagamento'
            ], 500);
        }

        return response()->json([
            'message' => 'Pagamento criado com sucesso'
        ], 201);
    }


    public function show(string $id)
    {
        //
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
