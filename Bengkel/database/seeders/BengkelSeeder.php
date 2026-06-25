<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterJasa;
use App\Models\Sparepart;

class BengkelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. KOSONGKAN TABEL SEBELUM MENGISI (Mencegah data ganda)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Matikan cek kunci sebentar
        MasterJasa::truncate();
        Sparepart::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. DATA KATALOG LAYANAN (STANDAR AHASS)
        $layanan = [
            // Paket Utama
            ['nama_layanan' => 'Servis Lengkap (Tune Up, Cek 15 Poin)', 'biaya_standar' => 120000, 'id_mekanik_default' => 1],
            ['nama_layanan' => 'Servis Ringan / Perawatan Berkala', 'biaya_standar' => 75000, 'id_mekanik_default' => 1],
            ['nama_layanan' => 'KPB (Kupon Perawatan Berkala) - Gratis', 'biaya_standar' => 0, 'id_mekanik_default' => 1],

            // Oli & Mesin
            ['nama_layanan' => 'Ganti Oli Mesin (Jasa Saja)', 'biaya_standar' => 15000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Ganti Oli Gardan / Gear (Matic)', 'biaya_standar' => 10000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Servis Injeksi / Pembersihan Throttle Body', 'biaya_standar' => 60000, 'id_mekanik_default' => 1],
            ['nama_layanan' => 'Overhaul (Turun Mesin)', 'biaya_standar' => 400000, 'id_mekanik_default' => 1],

            // Transmisi & Kaki-kaki
            ['nama_layanan' => 'Servis CVT (Pembersihan)', 'biaya_standar' => 55000, 'id_mekanik_default' => 1],
            ['nama_layanan' => 'Ganti Kampas Rem (Cakram/Tromol)', 'biaya_standar' => 25000, 'id_mekanik_default' => 2],
            ['nama_layanan' => 'Bongkar Pasang Ban', 'biaya_standar' => 20000, 'id_mekanik_default' => 2],
        ];

        // Looping untuk memasukkan data sekaligus memberikan timestamp otomatis
        foreach ($layanan as $item) {
            MasterJasa::create($item);
        }

        // 3. DATA GUDANG SPAREPART (Dengan Simulasi Stok Menipis)
        $sparepart = [
            ['nama_sparepart' => 'Oli MPX 2 (Matic)', 'harga' => 45000, 'stok' => 25, 'jenis_sparepart' => 'Oli'],
            ['nama_sparepart' => 'Oli SPX 1 (Sport)', 'harga' => 65000, 'stok' => 15, 'jenis_sparepart' => 'Oli'],
            ['nama_sparepart' => 'Kampas Rem Depan Vario', 'harga' => 45000, 'stok' => 3, 'jenis_sparepart' => 'Part'], // Stok Kritis!
            ['nama_sparepart' => 'Busi NGK Standar', 'harga' => 20000, 'stok' => 40, 'jenis_sparepart' => 'Part'],
            ['nama_sparepart' => 'V-Belt Honda Beat FI', 'harga' => 120000, 'stok' => 4, 'jenis_sparepart' => 'Part'], // Stok Kritis!
        ];

        foreach ($sparepart as $item) {
            Sparepart::create($item);
        }
    }
}
