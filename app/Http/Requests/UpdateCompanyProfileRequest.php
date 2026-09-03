<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyProfileRequest extends FormRequest
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
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'gmap_embed' => 'nullable|string',
            'gmail_access' => 'required|string|max:255',
            'logo_file_id' => 'nullable|string|max:36',
        ];
    }
}
