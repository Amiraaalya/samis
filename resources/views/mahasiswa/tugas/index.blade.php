@extends('layouts.app')

@section('title', 'Tugas Kelas')
@section('page-title', 'Tugas Kelas')
@section('page-subtitle', 'Daftar tugas dari seluruh kelas Anda')

@section('content')

{{-- Filter --}}
<div class="flex gap-3 mb-4 flex-wrap">
    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="kelas_id"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            <option value="">Semua Kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
        <select name="status"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            <option value="">Semua Status</option>
            <option value="belum_dikerjakan" {{ request('status') === 'belum_dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
            <option value="sedang_dikerjakan" {{ request('status') === 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
        </select>
        <button type="submit"
                class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-violet-700">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-1 gap-4">
    @forelse($tugas as $t)
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition">
            {{-- Prioritas indicator --}}
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
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $t->kelas->nama_kelas }} • {{ $t->kelas->mata_kuliah }}
                </p>

                {{-- Progress bar --}}
                <div class="mt-2 flex items-center gap-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full
                            {{ $t->progres === 100 ? 'bg-green-500' : 'bg-violet-500' }}"
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
                @if($t->sudah_dikumpulkan)
                    <span class="text-xs text-green-600 font-medium"><i class="fas fa-check mr-1"></i>Terkumpul</span>
                @endif
            </div>

            <a href="{{ route('mahasiswa.tugas.show', $t) }}"
               class="ml-2 bg-violet-50 hover:bg-violet-100 text-violet-700 px-3 py-2 rounded-lg text-sm">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-book text-4xl mb-3 block"></i>
            <p>Tidak ada tugas ditemukan.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $tugas->links() }}</div>

@endsection