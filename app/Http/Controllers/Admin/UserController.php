<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\AktivitasLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\NotifikasiAdminService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', '!=', 'admin');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('nim_nip', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'nim_nip'   => $request->nim_nip,
            'is_active' => true,
        ]);

        AktivitasLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'create_user',
            'model_type' => User::class,
            'model_id'   => $user->id,
            'data_baru'  => $user->only(['name', 'email', 'role']),
            'ip_address' => $request->ip(),
        ]);

        if ($user->role === 'dosen') {
            NotifikasiAdminService::dosenBaru($user->name, $user->nim_nip ?? '-');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $dataLama = $user->only(['name', 'email', 'role', 'is_active']);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'nim_nip'   => $request->nim_nip,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        AktivitasLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'update_user',
            'model_type' => User::class,
            'model_id'   => $user->id,
            'data_lama'  => $dataLama,
            'data_baru'  => $user->only(['name', 'email', 'role', 'is_active']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        AktivitasLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'delete_user',
            'model_type' => User::class,
            'model_id'   => $user->id,
            'data_lama'  => $user->only(['name', 'email', 'role']),
            'ip_address' => request()->ip(),
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}