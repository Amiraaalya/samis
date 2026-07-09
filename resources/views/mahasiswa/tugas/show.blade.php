@extends('layouts.app')

@section('title', $tugas->judul)
@section('page-title', $tugas->judul)
@section('page-subtitle', $tugas->kelas->nama_kelas)

@section('content')

<div class="max-w-3xl space-y-6">

    {{-- Detail Tugas --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $tugas->status === 'selesai' ? 'bg-green-100 text-green-700' : ($tugas->status === 'terlambat' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    {{ $tugas->labelStatus() }}
                </span>
                <span class="ml-2 text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $tugas->prioritas === 'tinggi' ? 'bg-red-50 text-red-600' : ($tugas->prioritas === 'sedang' ? 'bg-yellow-50 text-yellow-600' : 'bg-green-50 text-green-600') }}">
                    Prioritas {{ ucfirst($tugas->prioritas) }}
                </span>
            </div>
            <p class="text-sm text-gray-500">
                Dosen: <strong>{{ $tugas->pembuat->name }}</strong>
            </p>
        </div>

        @if($tugas->deskripsi)
            <p class="text-sm text-gray-700 leading-relaxed mb-4">{{ $tugas->deskripsi }}</p>
        @endif

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs">Deadline</p>
                <p class="font-medium {{ $tugas->sudahTerlambat() ? 'text-red-600' : 'text-gray-800' }}">
                    {{ $tugas->deadline->format('d M Y, H:i') }}
                </p>
                <p class="text-xs {{ $tugas->sisaHari() < 0 ? 'text-red-500' : 'text-gray-400' }}">
                    {{ $tugas->sisaHari() >= 0 ? $tugas->sisaHari() . ' hari lagi' : abs($tugas->sisaHari()) . ' hari terlewat' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Progres</p>
                <div class="flex items-center gap-2 mt-1">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full bg-violet-500" style="width: {{ $tugas->progres }}%"></div>
                    </div>
                    <span class="text-sm font-medium">{{ $tugas->progres }}%</span>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        @if($tugas->status !== 'selesai' || !$pengumpulan)
        <form method="POST" action="{{ route('mahasiswa.tugas.updateStatus', $tugas) }}" class="mt-5 pt-5 border-t border-gray-100">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Update Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <option value="belum_dikerjakan" {{ $tugas->status === 'belum_dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                        <option value="sedang_dikerjakan" {{ $tugas->status === 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="selesai" {{ $tugas->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Progres</label>
                    <select name="progres"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <option value="0"   {{ $tugas->progres == 0   ? 'selected' : '' }}>0%</option>
                        <option value="25"  {{ $tugas->progres == 25  ? 'selected' : '' }}>25%</option>
                        <option value="50"  {{ $tugas->progres == 50  ? 'selected' : '' }}>50%</option>
                        <option value="75"  {{ $tugas->progres == 75  ? 'selected' : '' }}>75%</option>
                        <option value="100" {{ $tugas->progres == 100 ? 'selected' : '' }}>100%</option>
                    </select>
                </div>
            </div>
            <button type="submit"
                    class="mt-3 bg-violet-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-violet-700">
                <i class="fas fa-sync mr-1"></i> Update Status
            </button>
        </form>
        @endif
    </div>

    {{-- Pengumpulan --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Pengumpulan Tugas</h3>

        @if($pengumpulan)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-check text-green-600 text-lg"></i>
                    <div>
                        <p class="text-sm font-medium text-green-800">{{ $pengumpulan->file_name }}</p>
                        <p class="text-xs text-green-600">
                            {{ $pengumpulan->fileSizeFormatted() }} •
                            Dikumpulkan {{ $pengumpulan->submitted_at->format('d M Y H:i') }}
                        </p>
                        @if($pengumpulan->catatan)
                            <p class="text-xs text-green-700 mt-1">{{ $pengumpulan->catatan }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Update file --}}
            <form method="POST"
                  action="{{ route('mahasiswa.pengumpulan.update', $pengumpulan) }}"
                  enctype="multipart/form-data">
                @csrf
                <p class="text-xs font-medium text-gray-600 mb-2">Update File Pengumpulan</p>
                <div class="grid grid-cols-1 gap-3">
                    <input type="file" name="file"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                           accept=".pdf,.doc,.docx,.zip,.rar,.png,.jpg,.jpeg">
                    <textarea name="catatan" rows="2" placeholder="Catatan (opsional)..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">{{ $pengumpulan->catatan }}</textarea>
                </div>
                <button type="submit"
                        class="mt-3 bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600">
                    <i class="fas fa-upload mr-1"></i> Update File
                </button>
            </form>

            {{-- Nilai --}}
            @if($pengumpulan->penilaian)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-600 mb-2">Penilaian Dosen</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-xl font-bold text-indigo-700">
                                {{ $pengumpulan->penilaian->getGradeLabel() }}
                            </span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $pengumpulan->penilaian->nilai }}</p>
                            <p class="text-xs text-gray-500">Dinilai oleh {{ $pengumpulan->penilaian->dosen->name }}</p>
                        </div>
                    </div>
                    @if($pengumpulan->penilaian->feedback)
                        <div class="mt-3 bg-blue-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-blue-700 mb-1">Feedback:</p>
                            <p class="text-sm text-blue-800">{{ $pengumpulan->penilaian->feedback }}</p>
                        </div>
                    @endif
                </div>
            @endif

        @else
            {{-- Upload pertama --}}
            <form method="POST"
                  action="{{ route('mahasiswa.pengumpulan.store', $tugas) }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">File Tugas</label>
                        <input type="file" name="file" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                               accept=".pdf,.doc,.docx,.zip,.rar,.png,.jpg,.jpeg">
                        <p class="text-xs text-gray-400 mt-0.5">Maks. 10MB. Format: PDF, DOC, DOCX, ZIP, RAR, PNG, JPG</p>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Tambahkan catatan untuk dosen..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"></textarea>
                    </div>
                </div>
                <button type="submit"
                        class="mt-3 bg-violet-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-violet-700">
                    <i class="fas fa-upload mr-1"></i> Kumpulkan Tugas
                </button>
            </form>
        @endif
    </div>
</div>

@endsection