<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nilai'    => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.min'      => 'Nilai minimal 0.',
            'nilai.max'      => 'Nilai maksimal 100.',
        ];
    }
}