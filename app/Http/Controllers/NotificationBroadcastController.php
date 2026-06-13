<?php

namespace App\Http\Controllers;

use App\Models\NotificationBroadcast;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationBroadcastController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HELPER: Ambil data notifikasi untuk topbar (konsisten dengan modul lain)
    |--------------------------------------------------------------------------
    */
    private function getNotifData()
    {
        $user_id = auth()->id();

        return [
            'unread_count' => DB::table('notifications')
                ->where('user_id', $user_id)
                ->where('is_read', 0)
                ->count(),
            'notif_result' => DB::table('notifications')
                ->where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX – Daftar semua notifikasi broadcast
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search  = $request->search;
        $status  = $request->status;
        $sort    = $request->sort ?? 'created_at';
        $order   = $request->order ?? 'desc';

        $broadcasts = NotificationBroadcast::with('creator')
            ->when($search, function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('pesan', 'like', "%$search%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy($sort, $order)
            ->paginate(10)
            ->appends($request->all());

        // Statistik untuk header card
        $total_broadcast   = NotificationBroadcast::count();
        $total_draft       = NotificationBroadcast::where('status', 'draft')->count();
        $total_published   = NotificationBroadcast::where('status', 'published')->count();

        $notif = $this->getNotifData();

        return view('admin.notifications', compact(
            'broadcasts',
            'search',
            'status',
            'sort',
            'order',
            'total_broadcast',
            'total_draft',
            'total_published',
        ) + $notif);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE – Simpan notifikasi baru
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'pesan'       => 'required|string',
            'target_role' => 'required|in:all,pelamar,perusahaan',
            'status'      => 'required|in:draft,published',
        ], [
            'judul.required'       => 'Judul notifikasi wajib diisi.',
            'pesan.required'       => 'Isi pesan wajib diisi.',
            'target_role.required' => 'Target pengguna wajib dipilih.',
            'status.required'      => 'Status wajib dipilih.',
        ]);

        $broadcast = NotificationBroadcast::create([
            'judul'       => $request->judul,
            'pesan'       => $request->pesan,
            'target_role' => $request->target_role,
            'status'      => $request->status,
            'created_by'  => Auth::id(),
        ]);

        // Jika langsung publish, kirim ke user
        if ($request->status === 'published') {
            $this->kirimNotifikasi($broadcast);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil disimpan.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE – Edit notifikasi
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $broadcast = NotificationBroadcast::findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'pesan'       => 'required|string',
            'target_role' => 'required|in:all,pelamar,perusahaan',
            'status'      => 'required|in:draft,published',
        ], [
            'judul.required'       => 'Judul notifikasi wajib diisi.',
            'pesan.required'       => 'Isi pesan wajib diisi.',
            'target_role.required' => 'Target pengguna wajib dipilih.',
            'status.required'      => 'Status wajib dipilih.',
        ]);

        $statusSebelumnya = $broadcast->status;

        $broadcast->update([
            'judul'       => $request->judul,
            'pesan'       => $request->pesan,
            'target_role' => $request->target_role,
            'status'      => $request->status,
        ]);

        // Kirim notifikasi jika status baru published dan sebelumnya masih draft
        if ($request->status === 'published' && $statusSebelumnya === 'draft') {
            $this->kirimNotifikasi($broadcast);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY – Hapus notifikasi
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $broadcast = NotificationBroadcast::findOrFail($id);
        $broadcast->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLISH – Publish notifikasi (dari tombol di tabel)
    |--------------------------------------------------------------------------
    */
    public function publish($id)
    {
        $broadcast = NotificationBroadcast::findOrFail($id);

        if ($broadcast->status === 'published') {
            return redirect()->route('admin.notifications.index')
                ->with('info', 'Notifikasi sudah dipublish sebelumnya.');
        }

        $broadcast->update(['status' => 'published']);
        $this->kirimNotifikasi($broadcast);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dipublish dan dikirim ke pengguna.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE: Kirim notifikasi ke tabel notifications (existing system)
    |--------------------------------------------------------------------------
    | Menggunakan tabel notifications yang sudah ada agar muncul di dropdown
    | notifikasi yang sudah digunakan oleh JobLynx.
    |--------------------------------------------------------------------------
    */
    private function kirimNotifikasi(NotificationBroadcast $broadcast)
    {
        $now     = now();
        $records = [];

        // Tentukan target user berdasarkan target_role
        if ($broadcast->target_role === 'all') {
            // Semua user aktif (tidak termasuk soft deleted)
            $userIds = User::whereNull('deleted_at')->pluck('id');

        } elseif ($broadcast->target_role === 'pelamar') {
            // Hanya user yang memiliki data pelamar
            $userIds = Pelamar::join('users', 'pelamars.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->pluck('pelamars.user_id');

        } elseif ($broadcast->target_role === 'perusahaan') {
            // Hanya user yang memiliki data perusahaan
            $userIds = Perusahaan::join('users', 'perusahaans.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->pluck('perusahaans.user_id');

        } else {
            $userIds = collect();
        }

        // Buat record notifikasi untuk setiap user
        foreach ($userIds as $userId) {
            $records[] = [
                'user_id'    => $userId,
                'pesan'      => "[Pengumuman] {$broadcast->judul}: {$broadcast->pesan}",
                'is_read'    => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert batch untuk performa lebih baik
        if (!empty($records)) {
            DB::table('notifications')->insert($records);
        }
    }
}
