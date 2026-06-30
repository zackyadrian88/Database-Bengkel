<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::connection('mongodb')->create('master_jasas', function ($collection) {
            $collection->index('nama_layanan');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('master_jasas');
    }
};
