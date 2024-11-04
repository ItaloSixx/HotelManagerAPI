<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Realiza o login do usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Token de autenticação"),
     *     @OA\Response(response=401, description="Falha ao realizar login")
     * )
     */
    public function login(AuthRequests $request)
    {
        $data = $request->validated();

        $email = $data['email'];
        $password = $data['password'];
        $attempt = auth()->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$attempt) {
            return response()->json([
                'message' => 'Falha ao realizar login'
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth  "},
     *     summary="Realiza o logout do usuário",
     *     @OA\Response(response=200, description="Deslogado com sucesso")
     * )
     */
    public function logout(AuthRequests $request)
    {
        $request->user()->tokens()->delete();

        return response()->json('Deslogado com sucesso');
    }
}
