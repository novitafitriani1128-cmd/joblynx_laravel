<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportLogsTable extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | UP – Buat tabel export_logs untuk log aktivitas export
    |--------------------------------------------------------------------------
    */
    public function up()
    {
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');         // Admin yang melakukan export
            $table->string('jenis_data');                  // users / perusahaan
            $table->string('format');                      // excel / pdf
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DOWN – Hapus tabel
    |--------------------------------------------------------------------------
    */
    public function down()
    {
        Schema::dropIfExists('export_logs');
    }
}
