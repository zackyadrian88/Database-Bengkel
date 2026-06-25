<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id('id_servis');

            // Gunakan unsignedBigInteger karena ini akan merujuk ke ID tabel lain
            $table->unsignedBigInteger('id_kendaraan');
            $table->integer('id_mekanik');
            $table->string('jenis_servis');
            $table->decimal('biaya_jasa', 15, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servis');
    }
};
