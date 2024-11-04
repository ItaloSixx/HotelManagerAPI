<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Lista todos os usuários",
     *     description="Acesso permitido para administradores",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(response=200, description="Lista de usuários")
     * )
     */
    public function index()
    {
        try {
            $users = DB::table('users')->whereNull('deleted_at')->get();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao listar usuários: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar usuários'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Cadastra um novo usuário",
     *     description="Acesso permitido para administradores e recepcionistas",
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cadastrado com sucesso"),
     *     @OA\Response(response=500, description="Usuário não cadastrado")
     * )
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $data = $request->validated();

            $user = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => $data['role'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($user === 0) {
                Log::error('Usuário não cadastrado');
                return response()->json(['message' => 'Usuário não cadastrado'], 500);
            }

            return response()->json(['message' => 'Cadastrado com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar usuário: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao cadastrar usuário'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Exibe os detalhes de um usuário específico",
     *     description="Acesso permitido para administradores",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do usuário"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function show(string $id)
    {
        try {
            $user = DB::table('users')
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->first();

            if (!$user) {
                Log::error("Usuário com ID {$id} não encontrado");
                return response()->json(['message' => 'Usuário não encontrado'], 404);
            }

            return response()->json($user, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao exibir usuário com ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao exibir usuário'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Atualiza um usuário",
     *     description="Acesso permitido para administradores",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Atualizado com sucesso"),
     *     @OA\Response(response=500, description="Usuário não encontrado ou não atualizado")
     * )
     */
    public function update(UserStoreRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $userPut = DB::table('users')->where('id', $id)
                        ->update([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => bcrypt($data['password']),
                            'role' => $data['role'],
                            'updated_at' => now()
                        ]);

            if ($userPut === 0) {
                Log::error("Usuário com ID {$id} não encontrado ou não atualizado");
                return response()->json(['message' => 'Usuário não encontrado ou não atualizado'], 500);
            }

            return response()->json(['message' => 'Atualizado com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar usuário com ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar usuário'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Exclui um usuário",
     *     description="Acesso permitido para administradores",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=201, description="Usuário excluído com sucesso"),
     *     @OA\Response(response=500, description="Usuário não encontrado/excluído")
     * )
     */
    public function destroy(string $id)
    {
        try {
            $userExist = DB::table('users')->where('id', $id)->update(['deleted_at' => now()]);

            if (!$userExist) {
                Log::error("Usuário com ID {$id} não encontrado/excluído");
                return response()->json(['message' => 'Usuário não encontrado/excluído'], 500);
            }

            return response()->json(['message' => 'Usuário excluído com sucesso'], 201);
        } catch (\Exception $e) {
            Log::error("Erro ao excluir usuário com ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao excluir usuário'], 500);
        }
    }
}
