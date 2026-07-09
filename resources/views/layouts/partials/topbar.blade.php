<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">
    <div>
        <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        <p class="text-xs text-gray-500">@yield('page-subtitle', '')</p>
    </div>

    <div class="flex items-center gap-4">
        {{-- Notifikasi bell --}}
        @if(auth()->user()->isAdmin())
            @php $unreadAdmin = \App\Models\NotifikasiAdmin::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                        class="text-gray-500 hover:text-gray-800 relative p-1">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadAdmin > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">
                            {{ $unreadAdmin > 9 ? '9+' : $unreadAdmin }}
                        </span>
                    @endif
                </button>

                {{-- Dropdown --}}
                <div x-show="open" x-transition
                     class="absolute right-0 top-10 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">Notifikasi</p>
                        @if($unreadAdmin > 0)
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">
                                {{ $unreadAdmin }} belum dibaca
                            </span>
                        @endif
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                        @php
                            $notifDropdown = \App\Models\NotifikasiAdmin::where('user_id', auth()->id())
                                ->orderByDesc('created_at')->take(5)->get();
                        @endphp

                        @forelse($notifDropdown as $n)
                            <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 {{ !$n->is_read ? 'bg-blue-50/50' : '' }}">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $n->warnaIcon() }}">
                                    <i class="fas {{ $n->icon }} text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 truncate">{{ $n->judul }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ $n->pesan }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                                </div>
                                @if(!$n->is_read)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1"></div>
                                @endif
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center text-gray-400 text-sm">
                                Tidak ada notifikasi.
                            </div>
                        @endforelse
                    </div>

                    <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50">
                        <a href="{{ route('admin.notifikasi.index') }}"
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat semua notifikasi →
                        </a>
                    </div>
                </div>
            </div>

        @else
            {{-- Bell untuk dosen & mahasiswa --}}
            <div class="relative">
                <a href="{{ route('notifikasi.index') }}" class="text-gray-500 hover:text-gray-800 relative">
                    <i class="fas fa-bell text-lg"></i>
                    @php $unread = auth()->user()->notifikasiUnread()->count(); @endphp
                    @if($unread > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        {{-- User info --}}
        @if(auth()->user()->isAdmin())
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        @else
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:bg-gray-50 rounded-lg px-2 py-1 -mx-2 -my-1 transition">
                <img src="{{ auth()->user()->avatarUrl() }}"
                     alt="{{ auth()->user()->name }}"
                     class="w-8 h-8 rounded-full object-cover border border-gray-200">
                <div class="hidden sm:block">
                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </a>
        @endif
    </div>
</header>