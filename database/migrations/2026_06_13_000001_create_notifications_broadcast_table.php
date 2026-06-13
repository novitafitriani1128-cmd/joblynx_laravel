<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsBroadcastTable extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | UP – Buat tabel notifications_broadcast
    |--------------------------------------------------------------------------
    */
    public function up()
    {
        Schema::create('notifications_broadcast', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('pesan');

            $table->enum('target_role', [
                'all',
                'pelamar',
                'perusahaan'
            ])->default('all');

            $table->enum('status', [
                'draft',
                'published'
            ])->default('draft');

            $table->unsignedBigInteger('created_by');

            $table->timestamps();

            // Soft Delete
            $table->softDeletes();

            // Foreign key ke users (admin yang membuat)
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DOWN – Hapus tabel
    |--------------------------------------------------------------------------
    */
    public function down()
    {
        Schema::dropIfExists('notifications_broadcast');
    }
}