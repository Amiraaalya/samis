@extends('layouts.app')

@section('title', 'Detail Penilaian')
@section('page-title', 'Penilaian Tugas')
@section('page-subtitle', $pengumpulan->tugas->judul)

@section('content')

<div class="max-w-2xl space-y-6">

    {{-- Info pengumpulan --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Detail Pengumpulan</h3>
        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <p class="text-gray-500 text-xs">Mahasiswa</p>
                <p class="font-medium text-gray-800">{{ $pengumpulan->mahasiswa->name }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">NIM</p>
                <p class="font-medium text-gray-800">{{ $pengumpulan->mahasiswa->nim_nip }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Kelas</p>
                <p class="font-medium text-gray-800">{{ $pengumpulan->tugas->kelas->nama_kelas }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Dikumpulkan</p>
                <p class="font-medium text-gray-800">{{ $pengumpulan->submitted_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-3 mb-3">
            <i class="fas fa-file text-gray-500 text-lg"></i>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $pengumpulan->file_name }}</p>
                <p class="text-xs text-gray-500">{{ $pengumpulan->fileSizeFormatted() }}</p>
            </div>
            <a href="{{ route('dosen.penilaian.download', $pengumpulan) }}"
               target="_blank"
               class="ml-auto text-teal-600 hover:text-teal-800 text-sm">
                <i class="fas fa-download mr-1"></i> Unduh
            </a>
        </div>

        @if($pengumpulan->catatan)
            <div class="bg-blue-50 rounded-lg p-3">
                <p class="text-xs font-medium text-blue-700 mb-1">Catatan Mahasiswa:</p>
                <p class="text-sm text-blue-800">{{ $pengumpulan->catatan }}</p>
            </div>
        @endif
    </div>

    {{-- Form Penilaian --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            {{ $pengumpulan->penilaian ? 'Ubah Nilai' : 'Beri Nilai' }}
        </h3>

        <form method="POST"
              action="{{ $pengumpulan->penilaian
                    ? route('dosen.penilaian.update', $pengumpulan->penilaian)
                    : route('dosen.penilaian.store', $pengumpulan) }}">
            @csrf
            @if($pengumpulan->penilaian) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai (0–100)</label>
                    <input type="number" name="nilai" min="0" max="100" step="0.01"
                           value="{{ old('nilai', $pengumpulan->penilaian?->nilai) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('nilai') border-red-400 @enderror">
                    @error('nilai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                    <textarea name="feedback" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">{{ old('feedback', $pengumpulan->penilaian?->feedback) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('dosen.penilaian.index') }}"
                   class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                    <i class="fas fa-save mr-1"></i> Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

@endsection