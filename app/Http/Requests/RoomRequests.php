<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequests extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'hotelCode' => 'required|integer|exists:hotels,id',
            'name' => 'required|string',
            'availability' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'hotelCode.required' => 'O campo código do hotel é obrigatório.',
            'hotelCode.integer' => 'O código do hotel deve ser um número inteiro.',
            'hotelCode.exists' => 'O hotel fornecido não existe.',
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'availability.required' => 'O campo disponibilidade é obrigatório.',
            'availability.integer' => 'A disponibilidade deve ser um número inteiro.',
            'availability.min' => 'A disponibilidade deve ser no mínimo 0.',
    ];
    }

}
