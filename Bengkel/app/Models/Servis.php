<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Servis extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'servis';
    protected $guarded = [];

    /**
     * Relasi: Servis milik satu kendaraan (referenced)
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id', '_id');
    }

    /**
     * detail_parts disimpan sebagai embedded array (field biasa di document ini).
     * Contoh struktur:
     * [
     *   ['sparepart_id' => '...', 'nama_sparepart' => '...', 'jumlah' => 2, 'subtotal' => 90000],
     *   ...
     * ]
     *
     * Untuk menambah part: $servis->push('detail_parts', [...])
     */
}
