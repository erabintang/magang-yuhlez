<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
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
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'program_start' => 'required|date|after:registration_end',
            'program_end' => 'required|date|after:program_start',
            'positions' => 'required|array|min:1',
            'positions.*.name' => 'required|string|max:255',
            'positions.*.description' => 'nullable|string',
            'positions.*.quota' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'registration_end.after' => 'Tanggal tutup pendaftaran harus setelah tanggal buka pendaftaran.',
            'program_start.after' => 'Tanggal mulai program harus setelah tanggal tutup pendaftaran.',
            'program_end.after' => 'Tanggal selesai program harus setelah tanggal mulai program.',
            'positions.required' => 'Minimal harus ada 1 posisi.',
            'positions.min' => 'Minimal harus ada 1 posisi.',
        ];
    }
}
