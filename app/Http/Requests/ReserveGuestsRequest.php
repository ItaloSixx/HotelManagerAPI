<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReserveGuestsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'guestId' => 'required|exists:guests,id'
        ];
    }

    public function messages(): array
    {
        return [
            'guestId.required' => 'O campo ID do hóspede é obrigatório.',
            'guestId.exists' => 'O hóspede fornecido não existe.'
        ];
    }

}
