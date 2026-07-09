<x-guest-layout>

    <h1 class="text-2xl font-bold text-gray-900 mb-1">Masuk ke SAMIS</h1>
    <p class="text-sm text-gray-500 mb-8">Pantau tugas akademik Anda di sini.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Email" class="mb-1" />
            <x-text-input id="email" class="block w-full" type="email" name="email"
                           :value="old('email')" required autofocus autocomplete="username"
                           placeholder="nama@samis.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full" type="password" name="password"
                           required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 text-indigo-600 rounded border-gray-300">
            <label for="remember_me" class="text-sm text-gray-600">Ingat saya</label>
        </div>

        <x-primary-button class="w-full justify-center">
            Masuk
        </x-primary-button>
    </form>

    <p class="text-sm text-gray-500 text-center mt-8">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:text-indigo-800">
            Daftar sebagai mahasiswa
        </a>
    </p>

</x-guest-layout>