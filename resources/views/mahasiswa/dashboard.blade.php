@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    Selamat datang, {{ auth()->user()->name }}
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fas fa-tasks text-blue-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Tugas</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['total_tugas'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-check-circle text-green-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Selesai</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['tugas_selesai'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
            <i class="fas fa-clock text-amber-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Belum Selesai</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['tugas_belum'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
            <i class="fas fa-exclamation-circle text-red-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Terlambat</p>
            <p class="text-xl font-bold text-gray-800">{{ $stats['tugas_terlambat'] }}</p>
        </div>
    </div>
</div>

{{-- Deadline terdekat --}}
@if($deadlineTerdekat)
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center gap-4">
    <i class="fas fa-bell text-amber-500 text-xl"></i>
    <div>
        <p class="text-sm font-medium text-amber-800">Deadline Terdekat</p>
        <p class="text-sm text-amber-700">
            <strong>{{ $deadlineTerdekat->judul }}</strong>
            — {{ $deadlineTerdekat->deadline->format('d M Y H:i') }}
            ({{ $deadlineTerdekat->sisaHari() }} hari lagi)
        </p>
    </div>
    <a href="{{ route('mahasiswa.tugas.show', $deadlineTerdekat) }}"
       class="ml-auto text-sm bg-amber-500 text-white px-3 py-1.5 rounded-lg hover:bg-amber-600">
        Lihat
    </a>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Pie Chart Status --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Status Tugas</h2>
        <canvas id="statusChart" height="200"></canvas>
    </div>

    {{-- Notifikasi --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Notifikasi Terbaru</h2>
            <a href="{{ route('notifikasi.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        @forelse($notifikasiUnread as $notif)
            <div class="flex items-start gap-3 py-2 border-b border-gray-100 last:border-0">
                <div class="w-2 h-2 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></div>
                <div class="flex-1">
                    <p class="text-xs text-gray-800">{{ $notif->pesan }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Tidak ada notifikasi baru.</p>
        @endforelse
    </div>
</div>

{{-- FullCalendar --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Kalender Deadline</h2>
    <div id="kalender"></div>
</div>

@endsection

@push('scripts')
<script>
    // Pie Chart
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($statusChart['labels']),
            datasets: [{
                data: @json($statusChart['data']),
                backgroundColor: ['#94a3b8', '#f59e0b', '#22c55e', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 } } }
            }
        }
    });

    // FullCalendar
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('kalender');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: @json($tugasUntukKalender),
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            }
        });
        calendar.render();
    });
</script>
@endpush