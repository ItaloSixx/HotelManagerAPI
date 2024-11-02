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
        $payment = DB::table('payments')->where('id', $id)->first();

        if(!$payment){
            return response()->json([
                'message' => 'Falha ao buscar pagamento'
            ], 500);
        }

        return response()->json($payment, 201);
    }


    public function update(PaymentsRequest $request, string $id)
    {
        $$data = $request->validated();

        $paymentPut = DB::table('payments')->where('id', $id)->update([
            'reserveId' => $data['reserveId'],
            'value' => $data['value'],
            'method' => $data['method'],
            'paid' => $data['paid']
        ]);

        if(!$paymentPut){
            return response()->json([
                'message' => 'Falha ao atualizar pagamento'
            ], 500);
        }

        return response()->json([
            'message' => 'Pagamento atualizado com sucesso'
        ]);
    }

    public function destroy(string $id)
    {
        $paymentDel = DB::table('payments')->where('id', $id)->delete();

        if(!$paymentDel){
            return response()->json([
                'message' => 'Falha ao excluir pagamento'
            ], 500);
        }

        return response()->json([
            'message' => 'Pagamento excluído com sucesso'
        ]);
    }
}
