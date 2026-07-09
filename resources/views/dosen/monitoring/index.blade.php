@extends('layouts.app')

@section('title', 'Monitoring Mahasiswa')
@section('page-title', 'Monitoring Mahasiswa')
@section('page-subtitle', 'Pilih kelas untuk melihat progres mahasiswa')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($kelas as $k)
        <a href="{{ route('dosen.monitoring.show', $k) }}"
           class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition block">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-chalkboard text-teal-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $k->nama_kelas }}</h3>
                    <p class="text-xs text-gray-500">{{ $k->mata_kuliah }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-500 pt-3 border-t border-gray-100">
                <span><i class="fas fa-user-graduate mr-1"></i> {{ $k->mahasiswa_count }} mahasiswa</span>
                <span><i class="fas fa-tasks mr-1"></i> {{ $k->tugas_count }} tugas</span>
            </div>
        </a>
    @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-chalkboard text-4xl mb-3 block"></i>
            <p>Anda belum mengampu kelas apapun.</p>
        </div>
    @endforelse
</div>

@endsection