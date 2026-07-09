@extends('layouts.app')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')
@section('page-subtitle', $kelas->nama_kelas)

@section('content')

<div class="max-w-3xl space-y-6">

    {{-- Form Data Kelas --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Data Kelas</h3>
        <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('nama_kelas') border-red-400 @enderror">
                    @error('nama_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kelas</label>
                    <input type="text" name="kode_kelas" value="{{ old('kode_kelas', $kelas->kode_kelas) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('kode_kelas') border-red-400 @enderror">
                    @error('kode_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                    <input type="text" name="mata_kuliah" value="{{ old('mata_kuliah', $kelas->mata_kuliah) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('mata_kuliah') border-red-400 @enderror">
                    @error('mata_kuliah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                    <input type="text" name="semester" value="{{ old('semester', $kelas->semester) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('semester') border-red-400 @enderror">
                    @error('semester') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pengampu</label>
                    <select name="dosen_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id', $kelas->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.kelas.index') }}"
                   class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>

    {{-- Anggota Mahasiswa --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Anggota Mahasiswa ({{ $kelas->mahasiswa->count() }})</h3>

        {{-- Tambah mahasiswa --}}
        <form method="POST" action="{{ route('admin.kelas.assignMahasiswa', $kelas) }}" class="mb-5">
            @csrf

            @php
                $mahasiswaTersedia = $mahasiswaAll->filter(fn($mhs) => !$kelas->mahasiswa->contains($mhs->id));
            @endphp

            @if($mahasiswaTersedia->isEmpty())
                <p class="text-sm text-gray-400 bg-gray-50 rounded-lg p-3">
                    Semua mahasiswa sudah menjadi anggota kelas ini.
                </p>
            @else
                <p class="text-xs font-medium text-gray-600 mb-2">Pilih mahasiswa yang akan ditambahkan:</p>

                <div class="border border-gray-300 rounded-lg max-h-56 overflow-y-auto divide-y divide-gray-100 mb-3 @error('mahasiswa_ids') border-red-400 @enderror">
                    @foreach($mahasiswaTersedia as $mhs)
                        <label class="flex items-center gap-3 px-3 py-2 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}"
                                {{ in_array($mhs->id, old('mahasiswa_ids', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 rounded flex-shrink-0">
                            <div>
                                <p class="text-sm text-gray-800">{{ $mhs->name }}</p>
                                <p class="text-xs text-gray-500">{{ $mhs->nim_nip }} — {{ $mhs->email }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('mahasiswa_ids')
                    <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                @enderror

                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    <i class="fas fa-user-plus mr-1"></i> Tambah ke Kelas
                </button>
            @endif
        </form>

        {{-- Daftar anggota --}}
        <div class="divide-y divide-gray-100">
            @forelse($kelas->mahasiswa as $mhs)
                <div class="flex items-center justify-between py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $mhs->name }}</p>
                        <p class="text-xs text-gray-500">{{ $mhs->nim_nip }} • {{ $mhs->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.kelas.removeMahasiswa', [$kelas, $mhs]) }}"
                          onsubmit="return confirm('Keluarkan mahasiswa ini dari kelas?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                            <i class="fas fa-user-minus mr-1"></i> Keluarkan
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada mahasiswa di kelas ini.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection