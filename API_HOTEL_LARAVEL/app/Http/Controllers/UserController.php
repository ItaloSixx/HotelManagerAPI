<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Lista todos os usuários",
     *     @OA\Response(response=200, description="Lista de usuários")
     * )
     */
    public function index()
    {
        $users = DB::table('users')->whereNull('deleted_at')->get();

        return response()->json($users, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Cadastra um novo usuário",
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
            return response()->json([
                'message' => 'Usuário não cadastrado'
            ], 500);
        }

        return response()->json([
            'message' => 'Cadastrado com sucesso'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Exibe os detalhes de um usuário específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalhes do usuário"),
     *     @OA\Response(response=404, description="usuário não encontrado")
     * )
     */
    public function show(string $id)
    {
        $user = DB::table('users')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

        if(!$user){
            return response()->json([
                'message' => 'usuário não encontrado'
            ], 404);
        }

        return response()->json($user,200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Atualiza um usuário",
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
            return response()->json([
                'message' => 'Usuário não encontrado ou não atualizado'
            ], 500);
        }

        return response()->json([
            'message' => 'Atualizado com sucesso'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Exclui um usuário",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=201, description="Usuário excluído com sucesso"),
     *     @OA\Response(response=500, description="Usuário não encontrado/excluido")
     * )
     */
    public function destroy(string $id)
    {
        $userExist = DB::table('users')->where('id', $id)->update(['deleted_at'=> now()]);

        if(!$userExist){
            return response()->json([
                'message' => 'Usuário não encontrado/excluido'
            ], 500);
        }

        return response()->json([
            'Message' => 'Usuário excluído com sucesso'
        ], 201);
    }
}
