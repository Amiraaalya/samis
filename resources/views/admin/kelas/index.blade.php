@extends('layouts.app')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')
@section('page-subtitle', 'Kelola kelas dan penempatan dosen')

@section('content')

<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama kelas / kode / mata kuliah..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            <i class="fas fa-search mr-1"></i> Cari
        </button>
    </form>
    <a href="{{ route('admin.kelas.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Kelas
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Nama Kelas</th>
                <th class="px-6 py-3">Kode</th>
                <th class="px-6 py-3">Mata Kuliah</th>
                <th class="px-6 py-3">Dosen Pengampu</th>
                <th class="px-6 py-3">Mahasiswa</th>
                <th class="px-6 py-3">Tugas</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($kelas as $k)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $k->nama_kelas }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        <span class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $k->kode_kelas }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $k->mata_kuliah }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $k->dosen->name }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        <i class="fas fa-user-graduate text-violet-400 mr-1"></i> {{ $k->mahasiswa_count }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <i class="fas fa-tasks text-amber-400 mr-1"></i> {{ $k->tugas_count }}
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('admin.kelas.edit', $k) }}"
                           class="text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.kelas.destroy', $k) }}"
                              onsubmit="return confirm('Hapus kelas ini? Semua tugas terkait juga akan terhapus.')">
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
                        <i class="fas fa-school text-3xl mb-2 block"></i>
                        Belum ada kelas. <a href="{{ route('admin.kelas.create') }}" class="text-indigo-600 underline">Tambah sekarang</a>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $kelas->links() }}</div>
</div>

@endsection