@extends('layouts.app')

@section('title', 'Manajemen Tugas')
@section('page-title', 'Manajemen Tugas')
@section('page-subtitle', 'Daftar tugas yang Anda buat')

@section('content')

<div class="flex justify-end mb-4">
    <a href="{{ route('dosen.tugas.create') }}"
       class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 flex items-center gap-2">
        <i class="fas fa-plus"></i> Buat Tugas Baru
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Kelas</th>
                <th class="px-6 py-3">Prioritas</th>
                <th class="px-6 py-3">Deadline</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Dikumpulkan</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($tugas as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $t->judul }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $t->kelas->nama_kelas }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $t->prioritas === 'tinggi' ? 'bg-red-100 text-red-700' : ($t->prioritas === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($t->prioritas) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $t->deadline->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $t->status === 'selesai' ? 'bg-green-100 text-green-700' : ($t->status === 'terlambat' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $t->labelStatus() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $t->pengumpulan_count }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('dosen.tugas.edit', $t) }}"
                           class="text-teal-600 hover:text-teal-800"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('dosen.tugas.destroy', $t) }}"
                              onsubmit="return confirm('Hapus tugas ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                        Belum ada tugas. <a href="{{ route('dosen.tugas.create') }}" class="text-teal-600 underline">Buat sekarang</a>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $tugas->links() }}</div>
</div>

@endsection