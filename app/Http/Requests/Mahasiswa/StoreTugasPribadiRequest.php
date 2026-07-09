<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreTugasPribadiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'judul'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'deadline'  => ['required', 'date'],
            'status'    => ['sometimes', 'in:belum_dikerjakan,sedang_dikerjakan,selesai'],
            'progres'   => ['sometimes', 'integer', 'in:0,25,50,75,100'],
        ];
    }
}