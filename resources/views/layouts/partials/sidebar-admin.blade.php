<aside class="w-64 bg-indigo-900 text-white flex flex-col flex-shrink-0 h-full overflow-y-auto">
    <div class="flex items-center justify-center h-16 bg-indigo-950 px-4">
        <span class="text-xl font-bold tracking-wide">SAMIS</span>
        <span class="ml-2 text-xs bg-indigo-600 px-2 py-0.5 rounded-full">Admin</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <i class="fas fa-home w-5 mr-3"></i> Dashboard
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider px-3">Manajemen</p>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <i class="fas fa-users w-5 mr-3"></i> Pengguna
        </a>

        <a href="{{ route('admin.kelas.index') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.kelas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <i class="fas fa-chalkboard-teacher w-5 mr-3"></i> Kelas
        </a>

        <div class="pt-3 pb-1">
            <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider px-3">Laporan</p>
        </div>

        <a href="{{ route('admin.monitoring') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.monitoring') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <i class="fas fa-chart-bar w-5 mr-3"></i> Monitoring
        </a>

        <a href="{{ route('admin.laporan') }}"
           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.laporan*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <i class="fas fa-file-pdf w-5 mr-3"></i> Laporan
        </a>

        <a href="{{ route('admin.notifikasi.index') }}"
        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.notifikasi.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <i class="fas fa-bell w-5 mr-3"></i> Notifikasi
            @php $unreadAdmin = \App\Models\NotifikasiAdmin::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
            @if($unreadAdmin > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                    {{ $unreadAdmin > 9 ? '9+' : $unreadAdmin }}
                </span>
            @endif
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-indigo-800">
        <p class="text-xs text-indigo-300 truncate">{{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-1 text-xs text-indigo-400 hover:text-white flex items-center">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
            </button>
        </form>
    </div>
</aside>