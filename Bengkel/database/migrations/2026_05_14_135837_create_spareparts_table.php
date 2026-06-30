<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::connection('mongodb')->create('spareparts', function ($collection) {
            $collection->index('nama_sparepart');
            $collection->index('stok');
            $collection->index('jenis_sparepart');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('spareparts');
    }
};
