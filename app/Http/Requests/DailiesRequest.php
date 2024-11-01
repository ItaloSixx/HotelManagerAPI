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
            'reserveId' => 'required|exists:reserves.,Id',
            'date' => 'required|date',
            'value' => 'required|numeric|min:0'
        ];
    }
}
