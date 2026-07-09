<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\NotifikasiAdminService;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'nim_nip'  => ['required', 'string', 'max:30', 'unique:users,nim_nip'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nim_nip.required' => 'NIM wajib diisi.',
            'nim_nip.unique'   => 'NIM sudah terdaftar.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'mahasiswa',
            'nim_nip'   => $request->nim_nip,
            'is_active' => true,
        ]);

        NotifikasiAdminService::mahasiswaBaru($user->name, $user->nim_nip ?? '-');

        // Di dalam method store(), setelah User::create():
        NotifikasiAdminService::info(
            'Mahasiswa Baru Terdaftar',
            "{$user->name} (NIM: {$user->nim_nip}) baru saja mendaftar sebagai mahasiswa.",
            'fa-user-plus',
            route('admin.users.index')
        );

        event(new Registered($user));

        return redirect()->route('login')
            ->with('status', 'Akun berhasil dibuat. Silakan masuk dengan email dan password Anda.');
    }
}