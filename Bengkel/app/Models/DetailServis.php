<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailServis extends Model
{
    // Mengunci nama tabel yang benar (sudah diperbaiki dari 'lable' ke 'table')
    protected $table = 'detail_servis';

    // Mengizinkan pengisian data otomatis (sudah diperbaiki dari 'protecled' ke 'protected')
    protected $guarded = [];
}
