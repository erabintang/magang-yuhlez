<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:internship_programs,id',
            'intern_ids' => 'required|array|min:1',
            'intern_ids.*' => 'exists:intern_profiles,id',
            'certificate_file' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'certificate_file.mimes' => 'File sertifikat harus berformat PDF.',
            'certificate_file.max' => 'Ukuran file sertifikat maksimal 5 MB.',
        ];
    }
}
