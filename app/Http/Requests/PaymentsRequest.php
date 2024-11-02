<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'reserveId' => 'required|exists:reserves,id',
            'value' => 'required|numeric|min:0|',
            'method' => 'required|string',
            'paid' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'reserveId.required' => 'O campo ID da reserva é obrigatório.',
            'reserveId.exists' => 'A reserva fornecida não existe.',
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O valor deve ser numérico.',
            'value.min' => 'O valor deve ser no mínimo 0.',
            'method.required' => 'O campo método é obrigatório.',
            'method.string' => 'O método deve ser uma string.',
            'paid.required' => 'O campo pago é obrigatório.',
            'paid.boolean' => 'O campo pago deve ser verdadeiro ou falso.',
        ];
    }

}
