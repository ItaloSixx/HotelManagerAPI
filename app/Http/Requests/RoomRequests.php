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
}
