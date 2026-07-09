@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun Anda')

@section('content')

<div class="max-w-2xl space-y-6">

    {{-- Foto Profil --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Foto Profil</h3>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="avatarForm">
            @csrf @method('PUT')

            {{-- field tersembunyi agar data profil lain tidak terhapus saat hanya ganti foto --}}
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="nim_nip" value="{{ $user->nim_nip }}">

            <div class="flex items-center gap-5">
                <img id="avatarPreview" src="{{ $user->avatarUrl() }}" alt="Foto profil"
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">

                <div class="flex-1">
                    <label class="inline-block cursor-pointer bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium px-4 py-2 rounded-lg">
                        <i class="fas fa-upload mr-1"></i> Pilih Foto Baru
                        <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp"
                               class="hidden" onchange="previewAvatar(event)">
                    </label>
                    <p class="text-xs text-gray-400 mt-2">JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                    @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                @if($user->avatar)
                    <button type="submit" form="removeAvatarForm"
                            class="px-4 py-2 text-sm border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                        <i class="fas fa-trash mr-1"></i> Hapus Foto
                    </button>
                @endif
                <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-1"></i> Simpan Foto
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.removeAvatar') }}" id="removeAvatarForm" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>

    {{-- Update Profil --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Informasi Profil</h3>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $user->role === 'dosen' ? 'NIP' : 'NIM' }}
                    </label>
                    <input type="text" name="nim_nip" value="{{ old('nim_nip', $user->nim_nip) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('nim_nip') border-red-400 @enderror">
                    @error('nim_nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Ubah Password</h3>

        <form method="POST" action="{{ route('profile.updatePassword') }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('current_password') border-red-400 @enderror">
                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="submit"
                        class="px-4 py-2 text-sm bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    <i class="fas fa-key mr-1"></i> Ubah Password
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush