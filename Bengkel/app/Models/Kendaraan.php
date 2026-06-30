<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Kendaraan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'kendaraans';
    protected $guarded = [];

    /**
     * Relasi: Satu kendaraan memiliki banyak servis
     */
    public function servis()
    {
        return $this->hasMany(Servis::class, 'kendaraan_id', '_id');
    }
}
