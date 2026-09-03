<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'is_published' => 'boolean',
        ];
    }
}
