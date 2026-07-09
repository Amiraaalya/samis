@extends('layouts.app')

@section('title', 'Penilaian')
@section('page-title', 'Penilaian Tugas')
@section('page-subtitle', 'Berikan nilai dan feedback kepada mahasiswa')

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Mahasiswa</th>
                <th class="px-6 py-3">Tugas</th>
                <th class="px-6 py-3">Kelas</th>
                <th class="px-6 py-3">Dikumpulkan</th>
                <th class="px-6 py-3">Status Nilai</th>
                <th class="px-6 py-3">Nilai</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pengumpulan as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $p->mahasiswa->name }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $p->tugas->judul }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $p->tugas->kelas->nama_kelas }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $p->submitted_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @if($p->penilaian)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Sudah Dinilai</span>
                        @else
                            <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-xs">Belum Dinilai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        {{ $p->penilaian ? $p->penilaian->nilai . ' (' . $p->penilaian->getGradeLabel() . ')' : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('dosen.penilaian.show', $p) }}"
                           class="text-teal-600 hover:text-teal-800 text-sm">
                            <i class="fas fa-eye"></i> {{ $p->penilaian ? 'Detail' : 'Nilai' }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                        Belum ada pengumpulan tugas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $pengumpulan->links() }}</div>
</div>

@endsection
