<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('master_jasas', function (Blueprint $table) {
            $table->id(); // Memakai nama default 'id'

            $table->string('nama_layanan');
            $table->decimal('biaya_standar', 15, 2);
            $table->integer('id_mekanik_default')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_jasas');
    }
};
