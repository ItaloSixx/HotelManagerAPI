<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservesRequests extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
            return [
                'hotelCode' => 'required|exists:hotels,id',
                'roomCode' => 'required|exists:rooms,id',
                'checkIn' => 'required|date|after_or_equal:today',
                'checkOut' => 'nullable|date|after:checkIn',
                'total' => 'required|numeric|min:0',
                'discounts' => 'nullable|string',
                'additional_charges' => 'nullable|numeric|min:0'
            ];
    }

    public function messages(): array
{
    return [
        'hotelCode.required' => 'O campo código do hotel é obrigatório.',
        'hotelCode.exists' => 'O hotel fornecido não existe.',
        'roomCode.required' => 'O campo código do quarto é obrigatório.',
        'roomCode.exists' => 'O quarto fornecido não existe.',
        'checkIn.required' => 'O campo data de check-in é obrigatório.',
        'checkIn.date' => 'A data de check-in fornecida não é válida.',
        'checkIn.after_or_equal' => 'A data de check-in deve ser hoje ou posterior.',
        'checkOut.required' => 'O campo data de check-out é obrigatório.',
        'checkOut.date' => 'A data de check-out fornecida não é válida.',
        'checkOut.after' => 'A data de check-out deve ser posterior à data de check-in.',
        'total.required' => 'O campo total é obrigatório.',
        'total.numeric' => 'O total deve ser numérico.',
        'total.min' => 'O total deve ser no mínimo 0.',
        'discounts.string' => 'O campo descontos deve ser uma string.',
        'additional_charges.numeric' => 'As cobranças adicionais devem ser numéricas.',
        'additional_charges.min' => 'As cobranças adicionais devem ser no mínimo 0.'
    ];
}

}
