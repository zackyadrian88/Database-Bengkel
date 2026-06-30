<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::connection('mongodb')->create('kendaraans', function ($collection) {
            $collection->index('created_at');
            $collection->index('nama_pelanggan');
            $collection->index('nomor_polisi');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('kendaraans');
    }
};
