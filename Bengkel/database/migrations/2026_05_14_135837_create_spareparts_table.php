<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id('id_sparepart');

            $table->string('nama_sparepart');
            // Decimal dengan 15 digit total, 2 digit di belakang koma (untuk uang)
            $table->decimal('harga', 15, 2);
            $table->integer('stok');
            $table->string('jenis_sparepart')->default('Umum');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spareparts');
    }
};
