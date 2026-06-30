<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterJasa;

class MasterJasaSeeder extends Seeder
{
    public function run()
    {
        // Bersihkan data lama terlebih dahulu agar tidak menumpuk
        MasterJasa::query()->delete();

        // 7 Daftar Layanan & Harga Standar AHASS
        $layanan = [
            ['nama_layanan' => 'Servis Ganti Oli', 'biaya_standar' => 20000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Ganti Oli Gardan', 'biaya_standar' => 10000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Servis Injeksi', 'biaya_standar' => 65000, 'id_mekanik_default' => 1],
            ['nama_layanan' => 'Servis CVT', 'biaya_standar' => 60000, 'id_mekanik_default' => 1],
            ['nama_layanan' => 'Ganti Kampas Rem', 'biaya_standar' => 30000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Bongkar Pasang Ban', 'biaya_standar' => 25000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Servis Ringan', 'biaya_standar' => 75000, 'id_mekanik_default' => 1],
        ];

        // Masukkan data baru ke database
        foreach ($layanan as $l) {
            MasterJasa::create($l);
        }
    }
}
