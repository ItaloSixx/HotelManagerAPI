<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $userId = $this->route('user');
    return [
        'name' => 'required',
        'email' => "required|email|unique:users,email,{$userId},id",
        'password' => 'required|string|min:8',
        'role' => 'required'
    ];
}

}
