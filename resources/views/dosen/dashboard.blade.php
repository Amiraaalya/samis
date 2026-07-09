@extends('layouts.app')

@section('title', 'Dashboard Dosen')
@section('page-title', 'Dashboard Dosen')
@section('page-subtitle', 'Ringkasan kelas dan tugas Anda')

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
            <i class="fas fa-school text-teal-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Kelas</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalKelas }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
            <i class="fas fa-tasks text-indigo-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Tugas</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalTugas }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
            <i class="fas fa-user-graduate text-violet-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Mahasiswa</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
            <i class="fas fa-bolt text-amber-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Tugas Aktif</p>
            <p class="text-xl font-bold text-gray-800">{{ $tugasAktif->count() }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Chart --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Statistik Penyelesaian Tugas</h2>
        <canvas id="statusChart" height="200"></canvas>
    </div>

    {{-- Tugas Aktif --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Tugas Mendekati Deadline</h2>
            <a href="{{ route('dosen.tugas.index') }}" class="text-xs text-teal-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-3">
            @forelse($tugasAktif as $tugas)
                <div class="flex items-center justify-between border border-gray-100 rounded-lg p-3 hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $tugas->judul }}</p>
                        <p class="text-xs text-gray-500">{{ $tugas->kelas->nama_kelas }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-700">{{ $tugas->deadline->format('d M') }}</p>
                        <p class="text-xs {{ $tugas->sisaHari() <= 3 ? 'text-red-500' : 'text-gray-400' }}">
                            {{ $tugas->sisaHari() }} hari
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada tugas aktif.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai', 'Terlambat'],
            datasets: [{
                label: 'Jumlah Tugas',
                data: [
                    {{ $statusChart['belum_dikerjakan'] }},
                    {{ $statusChart['sedang_dikerjakan'] }},
                    {{ $statusChart['selesai'] }},
                    {{ $statusChart['terlambat'] }},
                ],
                backgroundColor: ['#94a3b8', '#f59e0b', '#22c55e', '#ef4444'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endpush