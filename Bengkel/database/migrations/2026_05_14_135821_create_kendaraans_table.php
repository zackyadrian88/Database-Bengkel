<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            // Primary key khusus bernama id_kendaraan
            $table->id('id_kendaraan');

            // Kolom data pelanggan
            $table->string('nama_pelanggan');
            $table->string('nomor_polisi');
            $table->string('merk_kendaraan');

            // Timestamps otomatis membuat kolom 'created_at' dan 'updated_at'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kendaraans');
    }
};
