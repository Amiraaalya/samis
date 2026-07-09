<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelasRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $kelasId = $this->route('kelas')->id;

        return [
            'nama_kelas'  => ['required', 'string', 'max:100'],
            'kode_kelas'  => ['required', 'string', 'max:20', Rule::unique('kelas', 'kode_kelas')->ignore($kelasId)],
            'mata_kuliah' => ['required', 'string', 'max:100'],
            'semester'    => ['required', 'string', 'max:20'],
            'dosen_id'    => ['required', 'exists:users,id'],
        ];
    }
}