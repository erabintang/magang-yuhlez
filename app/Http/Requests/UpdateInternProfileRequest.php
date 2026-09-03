<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInternProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'whatsapp' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'address' => 'nullable|string|max:500',
            'gmail_access' => 'nullable|string|max:255',
            'profile_photo_file_id' => 'nullable|string|max:36',
            'cv_file_id' => 'nullable|string|max:36',
        ];
    }
}
