<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:internship_programs,id',
            'position_id' => 'required|exists:internship_positions,id',
            'cover_letter' => 'nullable|string|max:2000',
        ];
    }
}
