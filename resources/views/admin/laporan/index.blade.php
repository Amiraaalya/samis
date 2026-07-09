@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Rekap tugas dan mahasiswa sistem')

@section('content')

<div class="flex justify-end mb-4">
    <a href="{{ route('admin.laporan.exportPdf') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 flex items-center gap-2">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>

{{-- Rekap Tugas --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Rekap Tugas</h2>
    </div>
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Kelas</th>
                <th class="px-6 py-3">Pembuat</th>
                <th class="px-6 py-3">Deadline</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Dikumpulkan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rekapTugas as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $t->judul }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $t->kelas?->nama_kelas ?? '-' }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $t->pembuat->name }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $t->deadline->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $t->status === 'selesai' ? 'bg-green-100 text-green-700' : ($t->status === 'terlambat' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $t->labelStatus() }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $t->pengumpulan_count }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada data tugas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $rekapTugas->links() }}</div>
</div>

{{-- Rekap Mahasiswa --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Rekap Mahasiswa</h2>
    </div>
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">NIM</th>
                <th class="px-6 py-3">Jumlah Kelas</th>
                <th class="px-6 py-3">Tugas Dikumpulkan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rekapMahasiswa as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $m->name }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $m->nim_nip }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $m->kelas_count }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $m->pengumpulan_count }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Tidak ada data mahasiswa.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $rekapMahasiswa->links() }}</div>
</div>

@endsection
