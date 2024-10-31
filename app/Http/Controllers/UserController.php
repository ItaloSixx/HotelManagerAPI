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
        $users = DB::table('users')->get();

        return response()->json($users, 200);
    }


    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        $userId = DB::table('users')->insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'created_at' => now(),
            'updated_at' => now()

        ]);

        return response()->json([
            'message' => 'usuário criado com sucesso'
        ]);
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
