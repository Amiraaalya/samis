@extends('layouts.app')

@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas')
@section('page-subtitle', 'Buat kelas baru dan tentukan dosen pengampu')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.kelas.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}"
                           placeholder="cth: Pemrograman Web A"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('nama_kelas') border-red-400 @enderror">
                    @error('nama_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kelas</label>
                    <input type="text" name="kode_kelas" value="{{ old('kode_kelas') }}"
                           placeholder="cth: PWA-2025"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('kode_kelas') border-red-400 @enderror">
                    @error('kode_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                    <input type="text" name="mata_kuliah" value="{{ old('mata_kuliah') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('mata_kuliah') border-red-400 @enderror">
                    @error('mata_kuliah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                    <input type="text" name="semester" value="{{ old('semester') }}"
                           placeholder="cth: Genap 2024/2025"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('semester') border-red-400 @enderror">
                    @error('semester') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pengampu</label>
                    <select name="dosen_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('dosen_id') border-red-400 @enderror">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('dosen_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.kelas.index') }}"
                   class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection