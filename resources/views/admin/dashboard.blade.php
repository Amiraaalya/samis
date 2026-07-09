@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan sistem SAMIS')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
            <i class="fas fa-users text-indigo-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pengguna Aktif</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center">
            <i class="fas fa-chalkboard-teacher text-teal-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Dosen</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_dosen'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center">
            <i class="fas fa-user-graduate text-violet-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Mahasiswa</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_mahasiswa'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fas fa-school text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Kelas</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_kelas'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
            <i class="fas fa-tasks text-amber-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Tugas</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_tugas'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-upload text-green-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pengumpulan</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_pengumpulan'] }}</p>
        </div>
    </div>
</div>

{{-- Aktivitas Terbaru --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-base font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
    @if($aktivitasTerbaru->isEmpty())
        <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase border-b">
                        <th class="pb-2 pr-4">Pengguna</th>
                        <th class="pb-2 pr-4">Aksi</th>
                        <th class="pb-2 pr-4">Waktu</th>
                        <th class="pb-2">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($aktivitasTerbaru as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 pr-4 font-medium text-gray-800">
                                {{ $log->user?->name ?? 'Sistem' }}
                            </td>
                            <td class="py-2 pr-4">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-xs">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="py-2 pr-4 text-gray-500">
                                {{ $log->created_at->diffForHumans() }}
                            </td>
                            <td class="py-2 text-gray-400 text-xs">{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection