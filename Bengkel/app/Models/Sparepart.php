<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'spareparts';
    protected $guarded = [];
}
