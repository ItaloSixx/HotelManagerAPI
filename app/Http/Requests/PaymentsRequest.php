<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return false;
    }


    public function rules(): array
    {
        return [
            'reserveId' => 'require|exists:reserves,id',
            'value' => 'required|numeric|min:0|',
            'method' => 'required|string',
            'paid' => 'required|boolean'
        ];
    }
}
