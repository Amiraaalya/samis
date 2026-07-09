@extends('layouts.app')

@section('title', 'Nilai Saya')
@section('page-title', 'Nilai Saya')
@section('page-subtitle', 'Rekap penilaian dari seluruh tugas')

@section('content')

@if($rataRata)
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 mb-6 flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
            <span class="text-2xl font-bold text-indigo-700">{{ number_format($rataRata, 1) }}</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-indigo-800">Rata-rata Nilai</p>
            <p class="text-xs text-indigo-600">Dari {{ $pengumpulan->total() }} tugas yang sudah dinilai</p>
        </div>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Tugas</th>
                <th class="px-6 py-3">Mata Kuliah</th>
                <th class="px-6 py-3">Dikumpulkan</th>
                <th class="px-6 py-3">Nilai</th>
                <th class="px-6 py-3">Grade</th>
                <th class="px-6 py-3">Feedback</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pengumpulan as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $p->tugas->judul }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $p->tugas->kelas->mata_kuliah }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $p->submitted_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $p->penilaian->nilai }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-0.5 rounded-full text-sm font-bold
                            {{ $p->penilaian->getGradeLabel() === 'A' ? 'bg-green-100 text-green-700' :
                               ($p->penilaian->getGradeLabel() === 'B' ? 'bg-blue-100 text-blue-700' :
                               ($p->penilaian->getGradeLabel() === 'C' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $p->penilaian->getGradeLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                        {{ $p->penilaian->feedback ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                        <i class="fas fa-graduation-cap text-3xl mb-2 block"></i>
                        Belum ada nilai yang masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $pengumpulan->links() }}</div>
</div>

@endsection