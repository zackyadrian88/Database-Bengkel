<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_servis', function (Blueprint $table) {
            $table->id();

            // Relasi ke nota (id_servis) dan barang (id_sparepart)
            $table->unsignedBigInteger('id_servis');
            $table->unsignedBigInteger('id_sparepart');

            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_servis');
    }
};

