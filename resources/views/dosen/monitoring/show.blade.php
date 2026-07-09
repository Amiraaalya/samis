@extends('layouts.app')

@section('title', 'Monitoring ' . $kelas->nama_kelas)
@section('page-title', $kelas->nama_kelas)
@section('page-subtitle', 'Progres mahasiswa di kelas ini')

@section('content')

<a href="{{ route('dosen.monitoring') }}" class="text-sm text-teal-600 hover:underline mb-4 inline-block">
    <i class="fas fa-arrow-left mr-1"></i> Kembali ke daftar kelas
</a>

<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Mahasiswa</th>
                <th class="px-6 py-3">NIM</th>
                <th class="px-6 py-3">Tugas Selesai</th>
                <th class="px-6 py-3">Tugas Terlambat</th>
                <th class="px-6 py-3">Progres</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($mahasiswa as $mhs)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $mhs->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $mhs->nim_nip }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $mhs->tugas_selesai }} / {{ $mhs->total_tugas }}</td>
                    <td class="px-6 py-4">
                        @if($mhs->tugas_terlambat > 0)
                            <span class="text-red-600 font-medium">{{ $mhs->tugas_terlambat }}</span>
                        @else
                            <span class="text-gray-400">0</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $mhs->persentase >= 80 ? 'bg-green-500' : ($mhs->persentase >= 50 ? 'bg-amber-500' : 'bg-red-500') }}"
                                     style="width: {{ $mhs->persentase }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">{{ $mhs->persentase }}%</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada mahasiswa di kelas ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Daftar tugas kelas --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Daftar Tugas Kelas</h2>
    </div>
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Deadline</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Dikumpulkan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($tugas as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $t->judul }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $t->deadline->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $t->status === 'selesai' ? 'bg-green-100 text-green-700' : ($t->status === 'terlambat' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $t->labelStatus() }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $t->pengumpulan_count }} / {{ $mahasiswa->count() }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada tugas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection