<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = NotifikasiAdmin::where('user_id', Auth::id())
            ->orderByDesc('created_at');

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('status')) {
            $query->where('is_read', $request->status === 'dibaca');
        }

        $notifikasi = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => NotifikasiAdmin::where('user_id', Auth::id())->count(),
            'unread'   => NotifikasiAdmin::where('user_id', Auth::id())->where('is_read', false)->count(),
            'today'    => NotifikasiAdmin::where('user_id', Auth::id())->whereDate('created_at', today())->count(),
        ];

        return view('admin.notifikasi.index', compact('notifikasi', 'stats'));
    }

    public function markRead(NotifikasiAdmin $notifikasi)
    {
        $this->authorize($notifikasi);
        $notifikasi->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function markAllRead()
    {
        NotifikasiAdmin::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }

    public function destroy(NotifikasiAdmin $notifikasi)
    {
        $this->authorize($notifikasi);
        $notifikasi->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    public function destroyAll()
    {
        NotifikasiAdmin::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => NotifikasiAdmin::where('user_id', Auth::id())
                        ->where('is_read', false)
                        ->count(),
        ]);
    }

    public function getDropdown()
    {
        $notifikasi = NotifikasiAdmin::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return response()->json($notifikasi);
    }

    private function authorize(NotifikasiAdmin $notifikasi): void
    {
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }
    }
}