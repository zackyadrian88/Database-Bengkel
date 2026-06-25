<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterJasa;
use Illuminate\Support\Facades\DB;

class MasterJasaSeeder extends Seeder
{
    public function run()
    {
        // Bersihkan data lama terlebih dahulu agar tidak menumpuk
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('master_jasas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 7 Daftar Layanan & Harga Standar AHASS
        $layanan = [
            ['id' => 1, 'nama_layanan' => 'Servis Ganti Oli', 'biaya_standar' => 20000, 'id_mekanik_default' => 2],
            ['id' => 2, 'nama_layanan' => 'Ganti Oli Gardan', 'biaya_standar' => 10000, 'id_mekanik_default' => 2],
            ['id' => 3, 'nama_layanan' => 'Servis Injeksi', 'biaya_standar' => 65000, 'id_mekanik_default' => 1],
            ['id' => 4, 'nama_layanan' => 'Servis CVT', 'biaya_standar' => 60000, 'id_mekanik_default' => 1],
            ['id' => 5, 'nama_layanan' => 'Ganti Kampas Rem', 'biaya_standar' => 30000, 'id_mekanik_default' => 2],
            ['id' => 6, 'nama_layanan' => 'Bongkar Pasang Ban', 'biaya_standar' => 25000, 'id_mekanik_default' => 2],
            ['id' => 7, 'nama_layanan' => 'Servis Ringan', 'biaya_standar' => 75000, 'id_mekanik_default' => 1],
        ];

        // Masukkan data baru ke database
        foreach ($layanan as $l) {
            MasterJasa::create($l);
        }
    }
}
