<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailiesRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'reserveId' => 'required|exists:reserves,Id',
            'date' => 'required|date',
            'value' => 'required|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'reserveId.required' => 'O campo ID da reserva é obrigatório.',
            'reserveId.exists' => 'A reserva fornecida não existe.',
            'date.required' => 'O campo data é obrigatório.',
            'date.date' => 'A data fornecida não é válida.',
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O valor deve ser numérico.',
            'value.min' => 'O valor deve ser no mínimo 0.',
        ];
    }

}
