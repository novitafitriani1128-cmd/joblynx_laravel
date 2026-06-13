<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPelamarController;
use App\Http\Controllers\LokerController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationBroadcastController;
use App\Http\Controllers\AdminExportController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('beranda');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| AUTH AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/notifications/read-all', [DashboardController::class, 'readAll'])
    ->name('notifications.readAll');

    /*
    |--------------------------------------------------------------------------
    | DETAIL PELAMAR
    |--------------------------------------------------------------------------
    */
    Route::get('/detail_pelamar/{id}', [DetailPelamarController::class, 'index'])
        ->name('detail.pelamar');

    /*
    |--------------------------------------------------------------------------
    | LOKER
    |--------------------------------------------------------------------------
    */
    Route::get('/pasang_lowongan', [LokerController::class, 'create'])->name('create.loker');
    Route::post('/pasang_lowongan', [LokerController::class, 'store'])->name('store.loker');

    Route::get('/edit_loker/{id}', [LokerController::class, 'edit'])->name('edit.loker');
    Route::put('/edit_loker/{id}', [LokerController::class, 'update'])->name('update.loker');

    Route::get('/hapus_loker/{id}', [LokerController::class, 'hapus'])->name('hapus.loker');
    Route::get('/toggle_status_loker/{id}', [LokerController::class, 'toggleStatus'])->name('toggle.status.loker');
    Route::get('/restore_loker/{id}', [LokerController::class, 'restore'])->name('restore.loker');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profil_admin', [AdminProfileController::class, 'index'])->name('profil.admin');
    Route::post('/profil_admin', [AdminProfileController::class, 'update'])->name('profil.admin.update');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profil.update');

    /*
    |--------------------------------------------------------------------------
    | SKILL
    |--------------------------------------------------------------------------
    */
    Route::get('/skill', [SkillController::class, 'index'])->name('skill');
    Route::post('/simpan_skill', [SkillController::class, 'store'])->name('simpan.skill');

    /*
    |--------------------------------------------------------------------------
    | APPLICATION
    |--------------------------------------------------------------------------
    */
    Route::post('/proses_lamar', [ApplicationController::class, 'store'])->name('proses.lamar');
    Route::get('/batal_lamar/{id}', [ApplicationController::class, 'batalLamar'])->name('batal.lamar');
    Route::post('/update-status', [ApplicationController::class, 'updateStatus'])->name('update.status');

    /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */
    Route::get('/export_pelamar', [ExportController::class, 'exportCsv'])
        ->name('export.pelamar');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/update-password/{id}', [AdminController::class, 'updateAdminPassword'])->name('users.update.password');

        /*
        |--------------------------------------------------------------------------
        | TRASH USERS
        |--------------------------------------------------------------------------
        */
        Route::get('/trash/users', [AdminController::class, 'trashUsers'])->name('trash.users');
        Route::get('/users/restore/{id}', [AdminController::class, 'restoreUser'])->name('users.restore');

        /*
        |--------------------------------------------------------------------------
        | JOBS (FIX METHOD ADA DI CONTROLLER)
        |--------------------------------------------------------------------------
        */
        Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
        Route::get('/jobs/status/{id}', [AdminController::class, 'toggleJobStatus'])->name('jobs.status');
        Route::get('/jobs/delete/{id}', [AdminController::class, 'deleteJob'])->name('jobs.delete');

        /*
        |--------------------------------------------------------------------------
        | TRASH JOBS
        |--------------------------------------------------------------------------
        */
        Route::get('/trash/jobs', [AdminController::class, 'trashJobs'])->name('trash.jobs');
        Route::get('/jobs/restore/{id}', [AdminController::class, 'restoreJob'])->name('jobs.restore');

        /*
        |--------------------------------------------------------------------------
        | APPLICATIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
        Route::get('/applications/detail/{id}', [AdminController::class, 'detailApplication'])->name('applications.detail');
        Route::get('/applications/delete/{id}', [AdminController::class, 'deleteApplication'])->name('applications.delete');

        /*
        |--------------------------------------------------------------------------
        | TRASH APPLICATIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/trash/applications', [AdminController::class, 'trashApplications'])->name('trash.applications');
        Route::get('/applications/restore/{id}', [AdminController::class, 'restoreApplication'])->name('applications.restore');

        /*
        |--------------------------------------------------------------------------
        | PERUSAHAAN
        |--------------------------------------------------------------------------
        */
        Route::get('/perusahaan', [AdminController::class, 'perusahaan'])->name('perusahaan');
        Route::get('/perusahaan/status/{id}', [AdminController::class, 'togglePerusahaanStatus'])->name('perusahaan.status');
        Route::get('/perusahaan/delete/{id}', [AdminController::class, 'deletePerusahaan'])->name('perusahaan.delete');

        /*
        |--------------------------------------------------------------------------
        | TRASH PERUSAHAAN
        |--------------------------------------------------------------------------
        */
        Route::get('/trash/perusahaan', [AdminController::class, 'trashPerusahaan'])->name('trash.perusahaan');
        Route::get('/perusahaan/restore/{id}', [AdminController::class, 'restorePerusahaan'])->name('perusahaan.restore');

        Route::get('/notifications/read-all', [DashboardController::class, 'readAll'])->name('notifications.readAll');    });


/*
|--------------------------------------------------------------------------
| TAMBAHAN ROUTES – Kelola Notifikasi & Export Admin
|--------------------------------------------------------------------------
| Salin blok ini ke dalam grup Route::prefix('admin')->name('admin.')->group(...)
| yang sudah ada di routes/web.php
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| KELOLA NOTIFIKASI BROADCAST (hanya admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Index – Daftar notifikasi
    Route::get('/notifications', [NotificationBroadcastController::class, 'index'])
        ->name('notifications.index');

    // Store – Simpan notifikasi baru
    Route::post('/notifications', [NotificationBroadcastController::class, 'store'])
        ->name('notifications.store');

    // Update – Edit notifikasi
    Route::put('/notifications/{id}', [NotificationBroadcastController::class, 'update'])
        ->name('notifications.update');

    // Destroy – Hapus notifikasi
    Route::get('/notifications/delete/{id}', [NotificationBroadcastController::class, 'destroy'])
        ->name('notifications.delete');

    // Publish – Publish notifikasi (dari tombol tabel)
    Route::get('/notifications/publish/{id}', [NotificationBroadcastController::class, 'publish'])
        ->name('notifications.publish');

    /*
    |--------------------------------------------------------------------------
    | EXPORT USERS
    |--------------------------------------------------------------------------
    */
    Route::get('/users/export/excel', [AdminExportController::class, 'exportUsersExcel'])
        ->name('users.export.excel');

    Route::get('/users/export/pdf', [AdminExportController::class, 'exportUsersPdf'])
        ->name('users.export.pdf');

    /*
    |--------------------------------------------------------------------------
    | EXPORT PERUSAHAAN
    |--------------------------------------------------------------------------
    */
    Route::get('/perusahaan/export/excel', [AdminExportController::class, 'exportPerusahaanExcel'])
        ->name('perusahaan.export.excel');

    Route::get('/perusahaan/export/pdf', [AdminExportController::class, 'exportPerusahaanPdf'])
        ->name('perusahaan.export.pdf');

});

/*
|--------------------------------------------------------------------------
| CATATAN INTEGRASI ke routes/web.php yang sudah ada
|--------------------------------------------------------------------------
|
| 1. Tambahkan use statement di atas file web.php:
|       use App\Http\Controllers\NotificationBroadcastController;
|       use App\Http\Controllers\AdminExportController;
|
| 2. Tempelkan semua route di atas ke dalam blok:
|       Route::middleware('auth')->group(function () {
|           Route::prefix('admin')->name('admin.')->group(function () {
|               // ... route yang sudah ada ...
|               // paste di sini
|           });
|       });
|
| PENTING: Route 'notifications/delete/{id}' dan 'notifications/publish/{id}'
| harus dideklarasikan SEBELUM route 'notifications/{id}' (jika ada) agar
| tidak terjadi konflik parameter wildcard.
|--------------------------------------------------------------------------
*/

        
});