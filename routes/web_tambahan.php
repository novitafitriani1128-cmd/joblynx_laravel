<?php

/*
|--------------------------------------------------------------------------
| TAMBAHAN ROUTES – Kelola Notifikasi & Export Admin
|--------------------------------------------------------------------------
| Salin blok ini ke dalam grup Route::prefix('admin')->name('admin.')->group(...)
| yang sudah ada di routes/web.php
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\NotificationBroadcastController;
use App\Http\Controllers\AdminExportController;

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
