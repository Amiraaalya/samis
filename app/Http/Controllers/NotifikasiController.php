<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->with('tugas')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markRead(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $notifikasi->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function markAllRead(Request $request)
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $notifikasi->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        Notifikasi::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}