<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_kelas'  => ['required', 'string', 'max:100'],
            'kode_kelas'  => ['required', 'string', 'max:20', 'unique:kelas,kode_kelas'],
            'mata_kuliah' => ['required', 'string', 'max:100'],
            'semester'    => ['required', 'string', 'max:20'],
            'dosen_id'    => ['required', 'exists:users,id'],
        ];
    }
}