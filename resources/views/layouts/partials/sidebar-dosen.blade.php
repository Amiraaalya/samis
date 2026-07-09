<aside class="w-64 bg-teal-900 text-white flex flex-col flex-shrink-0 h-full overflow-y-auto">
    <div class="flex items-center justify-center h-16 bg-teal-950 px-4">
        <span class="text-xl font-bold tracking-wide">SAMIS</span>
        <span class="ml-2 text-xs bg-teal-600 px-2 py-0.5 rounded-full">Dosen</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        <a href="{{ route('dosen.dashboard') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dosen.dashboard') ? 'bg-teal-700 text-white' : 'text-teal-200 hover:bg-teal-800 hover:text-white' }}">
            <i class="fas fa-home w-5 mr-3"></i> Dashboard
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-wider px-3">Tugas</p>
        </div>

        <a href="{{ route('dosen.tugas.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dosen.tugas.*') ? 'bg-teal-700 text-white' : 'text-teal-200 hover:bg-teal-800 hover:text-white' }}">
            <i class="fas fa-tasks w-5 mr-3"></i> Manajemen Tugas
        </a>

        <a href="{{ route('dosen.penilaian.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dosen.penilaian.*') ? 'bg-teal-700 text-white' : 'text-teal-200 hover:bg-teal-800 hover:text-white' }}">
            <i class="fas fa-star w-5 mr-3"></i> Penilaian
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-wider px-3">Monitoring</p>
        </div>

        <a href="{{ route('dosen.monitoring') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dosen.monitoring*') ? 'bg-teal-700 text-white' : 'text-teal-200 hover:bg-teal-800 hover:text-white' }}">
            <i class="fas fa-chart-line w-5 mr-3"></i> Monitoring Mahasiswa
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-wider px-3">Notifikasi</p>
        </div>

        <a href="{{ route('notifikasi.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('notifikasi.*') ? 'bg-teal-700 text-white' : 'text-teal-200 hover:bg-teal-800 hover:text-white' }}">
            <i class="fas fa-bell w-5 mr-3"></i> Notifikasi
            @if(auth()->user()->notifikasiUnread()->count() > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                    {{ auth()->user()->notifikasiUnread()->count() }}
                </span>
            @endif
        </a>
        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-teal-700 text-white' : 'text-teal-200 hover:bg-teal-800 hover:text-white' }}">
                <i class="fas fa-user-circle w-5 mr-3"></i> Profil Saya
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-teal-800">
        <div class="flex items-center gap-2 mb-2">
            <img src="{{ auth()->user()->avatarUrl() }}"
                alt="{{ auth()->user()->name }}"
                class="w-8 h-8 rounded-full object-cover border border-teal-600">
            <div>
                <p class="text-xs text-teal-300 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-teal-400">NIP: {{ auth()->user()->nim_nip }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-1 text-xs text-teal-400 hover:text-white flex items-center">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
            </button>
        </form>
    </div>
</aside>