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
            'checkOut' => 'required|date|after:checkIn',
            'total' => 'required|numeric|min:0',
            'discounts' => 'nullable|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0'
        ];
    }
}
