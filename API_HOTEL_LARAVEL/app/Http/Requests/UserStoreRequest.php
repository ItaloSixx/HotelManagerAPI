<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        return [
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$userId},id",
            'password' => 'required|string|min:8',
            'role' => 'required'
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'O campo nome é obrigatório.',
        'email.required' => 'O campo email é obrigatório.',
        'email.email' => 'O email fornecido deve ser um endereço de email válido.',
        'email.unique' => 'Este email já está em uso.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.string' => 'A senha deve ser uma string.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        'role.required' => 'O campo papel (role) é obrigatório.'
    ];
}


}
