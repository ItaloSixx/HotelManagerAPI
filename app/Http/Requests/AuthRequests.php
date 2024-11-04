<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequests extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => "required|email|exists:users,email",
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O campo de email é obrigatório.',
            'email.email' => 'O email fornecido deve ser um endereço de email válido.',
            'email.exists' => 'O email fornecido não foi encontrado em nossos registros.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        ];
    }
}
