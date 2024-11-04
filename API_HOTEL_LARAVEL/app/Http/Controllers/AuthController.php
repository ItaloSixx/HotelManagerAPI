<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests;
use Illuminate\Support\Facades\Log;

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
     *     @OA\Response(response=401, description="Falha ao realizar login"),
     *     description="Acesso permitido para todos os usuários"
     * )
     */
    public function login(AuthRequests $request)
    {
        $data = $request->validated();

        $email = $data['email'];
        $password = $data['password'];

        try {
            $attempt = auth()->attempt([
                'email' => $email,
                'password' => $password,
            ]);

            if (!$attempt) {
                Log::warning('Falha ao realizar login para o usuário: ' . $email);
                return response()->json(['message' => 'Falha ao realizar login'], 401);
            }

            $user = auth()->user();
            $token = $user->createToken($user->name)->plainTextToken;

            return response()->json(['token' => $token], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao realizar login: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao realizar login'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Realiza o logout do usuário",
     *     @OA\Response(response=200, description="Deslogado com sucesso"),
     *     description="Acesso permitido para usuários logados",
     * )
     */
    public function logout(AuthRequests $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json('Deslogado com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro ao realizar logout: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao realizar logout'], 500);
        }
    }
}
