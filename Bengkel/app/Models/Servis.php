<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model {
    protected $table = 'servis'; // Mengunci nama tabel tanpa 's'
    protected $primaryKey = 'id_servis';
    protected $guarded = [];
}
