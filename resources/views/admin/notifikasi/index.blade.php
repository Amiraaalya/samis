@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Aktivitas pengguna dan akademik sistem SAMIS')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
            <i class="fas fa-bell text-indigo-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Notifikasi</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
            <i class="fas fa-envelope text-red-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Belum Dibaca</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['unread'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-calendar-day text-green-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Hari Ini</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['today'] }}</p>
        </div>
    </div>
</div>

{{-- Filter & Aksi --}}
<div class="flex items-center justify-between flex-wrap gap-3 mb-4">
    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="tipe"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">Semua Tipe</option>
            <option value="info"    {{ request('tipe') === 'info'    ? 'selected' : '' }}>Info</option>
            <option value="success" {{ request('tipe') === 'success' ? 'selected' : '' }}>Berhasil</option>
            <option value="warning" {{ request('tipe') === 'warning' ? 'selected' : '' }}>Peringatan</option>
            <option value="danger"  {{ request('tipe') === 'danger'  ? 'selected' : '' }}>Bahaya</option>
        </select>
        <select name="status"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">Semua Status</option>
            <option value="belum"  {{ request('status') === 'belum'  ? 'selected' : '' }}>Belum Dibaca</option>
            <option value="dibaca" {{ request('status') === 'dibaca' ? 'selected' : '' }}>Sudah Dibaca</option>
        </select>
        <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        @if(request()->hasAny(['tipe','status']))
            <a href="{{ route('admin.notifikasi.index') }}"
               class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                Reset
            </a>
        @endif
    </form>

    <div class="flex gap-2">
        <form method="POST" action="{{ route('admin.notifikasi.markAllRead') }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded-lg flex items-center gap-1.5">
                <i class="fas fa-check-double text-xs"></i> Tandai Semua Dibaca
            </button>
        </form>
        <form method="POST" action="{{ route('admin.notifikasi.destroyAll') }}"
              onsubmit="return confirm('Hapus semua notifikasi?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="bg-red-50 hover:bg-red-100 text-red-600 text-sm px-3 py-2 rounded-lg flex items-center gap-1.5">
                <i class="fas fa-trash text-xs"></i> Hapus Semua
            </button>
        </form>
    </div>
</div>

{{-- List Notifikasi --}}
<div class="space-y-3">
    @forelse($notifikasi as $n)
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-start gap-4
            {{ !$n->is_read ? 'border-l-4 ' . match($n->tipe) {
                'success' => 'border-l-green-500',
                'warning' => 'border-l-amber-500',
                'danger'  => 'border-l-red-500',
                default   => 'border-l-blue-500'
            } : 'border border-gray-100' }}">

            {{-- Icon --}}
            <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0 {{ $n->warnaIcon() }}">
                <i class="fas {{ $n->icon }}"></i>
            </div>

            {{-- Konten --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $n->warnaBadge() }}">
                        {{ $n->labelTipe() }}
                    </span>
                    @if(!$n->is_read)
                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">
                            Baru
                        </span>
                    @endif
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ $n->judul }}</p>
                <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">{{ $n->pesan }}</p>
                <div class="flex items-center gap-3 mt-1.5">
                    <p class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $n->created_at->diffForHumans() }} — {{ $n->created_at->format('d M Y, H:i') }}
                    </p>
                    @if($n->url_tujuan)
                        <a href="{{ $n->url_tujuan }}"
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat detail <i class="fas fa-arrow-right ml-0.5 text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex items-center gap-1 flex-shrink-0">
                @if(!$n->is_read)
                    <form method="POST" action="{{ route('admin.notifikasi.markRead', $n) }}">
                        @csrf @method('PATCH')
                        <button type="submit" title="Tandai dibaca"
                                class="w-8 h-8 rounded-lg hover:bg-indigo-50 text-gray-400 hover:text-indigo-600 flex items-center justify-center transition">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.notifikasi.destroy', $n) }}"
                      onsubmit="return confirm('Hapus notifikasi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" title="Hapus"
                            class="w-8 h-8 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bell text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-600 font-medium">Tidak ada notifikasi</p>
            <p class="text-gray-400 text-sm mt-1">Notifikasi aktivitas akademik akan muncul di sini.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $notifikasi->links() }}</div>

@endsection