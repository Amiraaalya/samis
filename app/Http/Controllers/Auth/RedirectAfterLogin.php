<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAfterLogin extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\RedirectResponse
    {
        return match(Auth::user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'dosen'     => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default     => redirect()->route('login'),
        };
    }
}