<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponsController extends Controller
{

    public function index()
    {
        $coupons = DB::table('coupons')->whereNull('deleted_at')->get();

        return response()->json($coupons, 200);
    }


    public function store(CouponRequest $request)
    {
    $data = $request->validated();

    $coupon = DB::table('coupons')->insert([
        'code' => $data['code'],
        'discount_value' => $data['discount_value'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

    if (!$coupon) {
        return response()->json([
            'message' => 'Erro ao criar o cupom'
        ], 500);
    }

    return response()->json([
        'message' => 'Cupom criado com sucesso'
    ], 201);
    }


    public function show(string $id)
    {
        $coupon = DB::table('coupons')
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->first();

        if(!$coupon){
            return response()->json([
                'message' => 'Cupom não encontrado'
            ], 404);
        }

        return response()->json($coupon, 201);
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
