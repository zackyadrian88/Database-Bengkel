<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJasa extends Model
{
    use HasFactory;

    // Tabel ini juga menggunakan nama ID bawaan Laravel yaitu 'id'

    // Mengizinkan semua kolom diisi secara massal
    protected $guarded = [];
}
