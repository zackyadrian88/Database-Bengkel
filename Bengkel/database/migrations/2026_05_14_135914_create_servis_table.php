<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::connection('mongodb')->create('servis', function ($collection) {
            $collection->index('created_at');
            $collection->index('kendaraan_id');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('servis');
    }
};
