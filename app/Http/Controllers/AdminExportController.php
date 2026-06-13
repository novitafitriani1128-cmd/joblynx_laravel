<?php

namespace App\Http\Controllers;

use App\Models\ExportLog;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\UsersExport;
use App\Exports\PerusahaanExport;

class AdminExportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EXPORT USERS – EXCEL
    |--------------------------------------------------------------------------
    */
    public function exportUsersExcel(Request $request)
    {
        // Hanya admin
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');

        // Simpan log aktivitas
        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'users',
            'format'     => 'excel',
        ]);

        $filename = 'users_' . date('Ymd') . '.xlsx';

        return Excel::download(new UsersExport($request), $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT USERS – PDF
    |--------------------------------------------------------------------------
    */
    public function exportUsersPdf(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');

        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'users',
            'format'     => 'pdf',
        ]);

        $search = $request->search;

        $users = User::when($search, function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhere('nama_lengkap', 'like', "%$search%");
            })
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.exports.users_pdf', compact('users'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('users_' . date('Ymd') . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PERUSAHAAN – EXCEL
    |--------------------------------------------------------------------------
    */
    public function exportPerusahaanExcel(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');
        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'perusahaan',
            'format'     => 'excel',
        ]);

        $filename = 'perusahaan_' . date('Ymd') . '.xlsx';

        return Excel::download(new PerusahaanExport($request), $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PERUSAHAAN – PDF
    |--------------------------------------------------------------------------
    */
    public function exportPerusahaanPdf(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');
        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'perusahaan',
            'format'     => 'pdf',
        ]);

        $search = $request->search;
        $status = $request->status;

        $perusahaan = Perusahaan::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.exports.perusahaan_pdf', compact('perusahaan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('perusahaan_' . date('Ymd') . '.pdf');
    }
}
