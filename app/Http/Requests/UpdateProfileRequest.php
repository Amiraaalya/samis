<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'nim_nip' => ['nullable', 'string', 'max:30'],
            'avatar'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan oleh pengguna lain.',
            'avatar.image'   => 'File harus berupa gambar.',
            'avatar.mimes'   => 'Format gambar harus JPG, PNG, atau WEBP.',
            'avatar.max'     => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}