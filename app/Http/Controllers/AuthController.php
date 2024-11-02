<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(AuthRequests $request)
    {

        $data = $request->validated();

        $email = $data['email'];
        $password = $data['password'];
        $attempt = auth()->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if(!$attempt){
            return response()->json([
                'message' => 'Falha ao realizar login'
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json(['token' => $token], 201);

    }

    public function logout(AuthRequests $request)
    {
        $request->user()->tokens()->delete();

        return response()->json('Deslogado com sucesso');
    }
}
