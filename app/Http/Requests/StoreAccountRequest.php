<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email',
            'role' => 'required|in:COMPANY,INTERN',
            'password' => 'nullable|string|min:6',
        ];
    }
}
