@extends('layouts.app')

@section('title', 'Monitoring Sistem')
@section('page-title', 'Monitoring Sistem')
@section('page-subtitle', 'Pantau aktivitas dan statistik platform secara real-time')

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Pengguna</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Kelas</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_kelas'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Tugas</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_tugas'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Pengumpulan</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_pengumpulan'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Chart status tugas --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Distribusi Status Tugas</h2>
        <canvas id="statusChart" height="220"></canvas>
    </div>

    {{-- Per kelas --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Ringkasan per Kelas</h2>
        <div class="overflow-y-auto max-h-64">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase border-b">
                    <tr>
                        <th class="py-2 pr-2">Kelas</th>
                        <th class="py-2 pr-2">Dosen</th>
                        <th class="py-2 pr-2">Mhs</th>
                        <th class="py-2">Tugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($kelasData as $k)
                        <tr>
                            <td class="py-2 pr-2 font-medium text-gray-800">{{ $k->nama_kelas }}</td>
                            <td class="py-2 pr-2 text-gray-600">{{ $k->dosen->name }}</td>
                            <td class="py-2 pr-2 text-gray-600">{{ $k->mahasiswa_count }}</td>
                            <td class="py-2 text-gray-600">{{ $k->tugas_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Aktivitas terbaru --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Aktivitas Terbaru</h2>
    <div class="space-y-2 max-h-96 overflow-y-auto">
        @forelse($aktivitasTerbaru as $log)
            <div class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-0">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bolt text-indigo-500 text-xs"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800">
                        <strong>{{ $log->user?->name ?? 'Sistem' }}</strong>
                        — {{ str_replace('_', ' ', $log->aksi) }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas tercatat.</p>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai', 'Terlambat'],
            datasets: [{
                data: [
                    {{ $statusChart['belum_dikerjakan'] }},
                    {{ $statusChart['sedang_dikerjakan'] }},
                    {{ $statusChart['selesai'] }},
                    {{ $statusChart['terlambat'] }},
                ],
                backgroundColor: ['#94a3b8', '#f59e0b', '#22c55e', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush