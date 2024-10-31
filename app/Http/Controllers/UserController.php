<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class UserController extends Controller
{

    public function index()
    {
        $users = DB::table('users')->whereNull('deleted_at')->get();

        return response()->json($users, 200);
    }


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



    public function destroy(string $id)
    {
        
    }
}
