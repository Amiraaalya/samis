@extends('layouts.app')

@section('title', 'Tugas Pribadi')
@section('page-title', 'Tugas Pribadi')
@section('page-subtitle', 'Kelola tugas mandiri Anda sendiri')

@section('content')

<div class="flex justify-end mb-4">
    <a href="{{ route('mahasiswa.tugas-pribadi.create') }}"
       class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-violet-700 flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Tugas Pribadi
    </a>
</div>

<div class="grid grid-cols-1 gap-4">
    @forelse($tugas as $t)
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-1 self-stretch rounded-full
                {{ $t->prioritas === 'tinggi' ? 'bg-red-500' : ($t->prioritas === 'sedang' ? 'bg-amber-400' : 'bg-green-400') }}">
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-medium text-gray-800">{{ $t->judul }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $t->status === 'selesai' ? 'bg-green-100 text-green-700' : ($t->status === 'terlambat' ? 'bg-red-100 text-red-700' : ($t->status === 'sedang_dikerjakan' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $t->labelStatus() }}
                    </span>
                </div>
                @if($t->deskripsi)
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $t->deskripsi }}</p>
                @endif

                <div class="mt-2 flex items-center gap-2 max-w-xs">
                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $t->progres === 100 ? 'bg-green-500' : 'bg-violet-500' }}"
                             style="width: {{ $t->progres }}%"></div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $t->progres }}%</span>
                </div>
            </div>

            <div class="text-right flex-shrink-0">
                <p class="text-xs text-gray-500">Deadline</p>
                <p class="text-sm font-medium {{ $t->sudahTerlambat() ? 'text-red-600' : 'text-gray-700' }}">
                    {{ $t->deadline->format('d M Y') }}
                </p>
            </div>

            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('mahasiswa.tugas-pribadi.edit', $t) }}"
                   class="text-violet-600 hover:text-violet-800 px-2">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('mahasiswa.tugas-pribadi.destroy', $t) }}"
                      onsubmit="return confirm('Hapus tugas pribadi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 px-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-user-edit text-4xl mb-3 block"></i>
            <p>Belum ada tugas pribadi.</p>
            <a href="{{ route('mahasiswa.tugas-pribadi.create') }}" class="text-violet-600 underline text-sm">
                Tambah sekarang
            </a>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $tugas->links() }}</div>

@endsection