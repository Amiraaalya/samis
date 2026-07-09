@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Reminder dan pemberitahuan tugas')

@section('content')

<div class="flex items-center justify-between mb-4 flex-wrap gap-2">
    <p class="text-sm text-gray-500">
        {{ $notifikasi->total() }} notifikasi
    </p>
    <div class="flex gap-2">
        {{-- Tandai semua dibaca --}}
        <form method="POST" action="{{ route('notifikasi.markAllRead') }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded-lg flex items-center gap-1.5">
                <i class="fas fa-check-double text-xs"></i> Tandai Semua Dibaca
            </button>
        </form>

        {{-- Hapus semua --}}
        <form method="POST" action="{{ route('notifikasi.destroyAll') }}"
              onsubmit="return confirm('Hapus semua notifikasi? Tindakan ini tidak bisa dibatalkan.')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="bg-red-50 hover:bg-red-100 text-red-600 text-sm px-3 py-2 rounded-lg flex items-center gap-1.5">
                <i class="fas fa-trash text-xs"></i> Hapus Semua
            </button>
        </form>
    </div>
</div>

<div class="space-y-3">
    @forelse($notifikasi as $n)
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-start gap-4 {{ !$n->is_read ? 'border-l-4 border-violet-500' : '' }}">

            {{-- Icon --}}
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                {{ $n->jenis === 'terlambat' ? 'bg-red-100' : ($n->jenis === 'selesai' ? 'bg-green-100' : ($n->jenis === 'hari_h' ? 'bg-amber-100' : 'bg-blue-100')) }}">
                <i class="fas {{ $n->jenis === 'selesai' ? 'fa-star' : 'fa-bell' }} text-sm
                    {{ $n->jenis === 'terlambat' ? 'text-red-500' : ($n->jenis === 'selesai' ? 'text-green-500' : ($n->jenis === 'hari_h' ? 'text-amber-500' : 'text-blue-500')) }}"></i>
            </div>

            {{-- Konten --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    @php
                        $labelBadge = $n->getLabelJenis();
                        if (in_array($n->jenis, ['h7', 'h3', 'h1'])) {
                            preg_match('/dalam (\d+) hari/', $n->pesan, $matches);
                            if (!empty($matches[1])) {
                                $labelBadge = $matches[1] . ' hari lagi';
                            }
                        }
                    @endphp
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $n->jenis === 'terlambat' ? 'bg-red-100 text-red-700' : ($n->jenis === 'selesai' ? 'bg-green-100 text-green-700' : ($n->jenis === 'hari_h' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ $labelBadge }}
                    </span>
                    @if(!$n->is_read)
                        <span class="text-xs bg-violet-100 text-violet-700 px-2 py-0.5 rounded-full">Baru</span>
                    @endif
                </div>
                <p class="text-sm text-gray-700">{{ $n->pesan }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
            </div>

            {{-- Aksi --}}
            <div class="flex items-center gap-1 flex-shrink-0">
                {{-- Tandai dibaca --}}
                @if(!$n->is_read)
                    <form method="POST" action="{{ route('notifikasi.markRead', $n) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                title="Tandai dibaca"
                                class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                    </form>
                @endif

                {{-- Hapus --}}
                <form method="POST" action="{{ route('notifikasi.destroy', $n) }}"
                      onsubmit="return confirm('Hapus notifikasi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            title="Hapus notifikasi"
                            class="w-8 h-8 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-bell text-4xl mb-3 block"></i>
            <p class="font-medium">Tidak ada notifikasi.</p>
            <p class="text-xs mt-1">Notifikasi reminder tugas akan muncul di sini.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $notifikasi->links() }}</div>

@endsection