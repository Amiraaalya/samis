<aside class="w-64 bg-violet-900 text-white flex flex-col flex-shrink-0 h-full overflow-y-auto">
    <div class="flex items-center justify-center h-16 bg-violet-950 px-4">
        <span class="text-xl font-bold tracking-wide">SAMIS</span>
        <span class="ml-2 text-xs bg-violet-600 px-2 py-0.5 rounded-full">Mahasiswa</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-violet-700 text-white' : 'text-violet-200 hover:bg-violet-800 hover:text-white' }}">
            <i class="fas fa-home w-5 mr-3"></i> Dashboard
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-violet-400 uppercase tracking-wider px-3">Tugas</p>
        </div>

        <a href="{{ route('mahasiswa.tugas.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.tugas.*') ? 'bg-violet-700 text-white' : 'text-violet-200 hover:bg-violet-800 hover:text-white' }}">
            <i class="fas fa-book w-5 mr-3"></i> Tugas Kelas
        </a>

        <a href="{{ route('mahasiswa.tugas-pribadi.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.tugas-pribadi.*') ? 'bg-violet-700 text-white' : 'text-violet-200 hover:bg-violet-800 hover:text-white' }}">
            <i class="fas fa-user-edit w-5 mr-3"></i> Tugas Pribadi
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-violet-400 uppercase tracking-wider px-3">Penilaian</p>
        </div>

        <a href="{{ route('mahasiswa.nilai.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.nilai.*') ? 'bg-violet-700 text-white' : 'text-violet-200 hover:bg-violet-800 hover:text-white' }}">
            <i class="fas fa-graduation-cap w-5 mr-3"></i> Nilai Saya
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-violet-400 uppercase tracking-wider px-3">Notifikasi</p>
        </div>

        <a href="{{ route('notifikasi.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('notifikasi.*') ? 'bg-violet-700 text-white' : 'text-violet-200 hover:bg-violet-800 hover:text-white' }}">
            <i class="fas fa-bell w-5 mr-3"></i> Notifikasi
            @if(auth()->user()->notifikasiUnread()->count() > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                    {{ auth()->user()->notifikasiUnread()->count() }}
                </span>
            @endif
        </a>
        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-violet-700 text-white' : 'text-violet-200 hover:bg-violet-800 hover:text-white' }}">
                <i class="fas fa-user-circle w-5 mr-3"></i> Profil Saya
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-violet-800">
        <div class="flex items-center gap-2 mb-2">
            <img src="{{ auth()->user()->avatarUrl() }}"
                alt="{{ auth()->user()->name }}"
                class="w-8 h-8 rounded-full object-cover border border-violet-600">
            <div>
                <p class="text-xs text-violet-300 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-violet-400">NIM: {{ auth()->user()->nim_nip }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-1 text-xs text-violet-400 hover:text-white flex items-center">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
            </button>
        </form>
    </div>
</aside>