<x-guest-layout>

    <h1 class="text-2xl font-bold text-gray-900 mb-1">Buat akun mahasiswa</h1>
    <p class="text-sm text-gray-500 mb-8">
        Pendaftaran terbuka untuk mahasiswa. Akun dosen dibuat oleh admin.
    </p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Nama --}}
        <div>
            <x-input-label for="name" value="Nama Lengkap" class="mb-1" />
            <x-text-input id="name" class="block w-full" type="text" name="name"
                           :value="old('name')" required autofocus autocomplete="name"
                           placeholder="Nama sesuai KTM" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        {{-- NIM --}}
        <div>
            <x-input-label for="nim_nip" value="NIM" class="mb-1" />
            <x-text-input id="nim_nip" class="block w-full" type="text" name="nim_nip"
                           :value="old('nim_nip')" required
                           placeholder="cth: 2024001001" />
            <x-input-error :messages="$errors->get('nim_nip')" class="mt-1" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Email" class="mb-1" />
            <x-text-input id="email" class="block w-full" type="email" name="email"
                           :value="old('email')" required autocomplete="username"
                           placeholder="nama@samis.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label for="password" value="Password" class="mb-1" />
            <x-text-input id="password" class="block w-full" type="password" name="password"
                           required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" class="mb-1" />
            <x-text-input id="password_confirmation" class="block w-full" type="password"
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <x-primary-button class="w-full justify-center">
            Buat Akun
        </x-primary-button>
    </form>

    <p class="text-sm text-gray-500 text-center mt-8">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:text-indigo-800">
            Masuk
        </a>
    </p>

</x-guest-layout>