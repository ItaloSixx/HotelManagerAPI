<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:coupons,code',
            'discount_value' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'O campo código é obrigatório.',
            'code.string' => 'O código deve ser uma string.',
            'code.unique' => 'Este código já está em uso.',
            'discount_value.required' => 'O campo valor do desconto é obrigatório.',
            'discount_value.numeric' => 'O valor do desconto deve ser numérico.',
            'discount_value.min' => 'O valor do desconto deve ser no mínimo 0.'
        ];
    }
}
