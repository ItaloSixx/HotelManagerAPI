<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestsRequests extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required|numeric'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'lastname.required' => 'O campo sobrenome é obrigatório.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.numeric' => 'O telefone deve ser numérico.',
        ];
    }

}
