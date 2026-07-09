<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTugasRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'kelas_id'  => ['required', 'exists:kelas,id'],
            'judul'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'deadline'  => ['required', 'date'],
        ];
    }
}