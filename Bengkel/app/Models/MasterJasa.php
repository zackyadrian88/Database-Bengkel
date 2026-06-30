<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MasterJasa extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'master_jasas';

    // Mengizinkan semua kolom diisi secara massal
    protected $guarded = [];
}
