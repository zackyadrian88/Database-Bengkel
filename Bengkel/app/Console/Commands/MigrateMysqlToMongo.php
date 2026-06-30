<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\MasterJasa;
use App\Models\Sparepart;
use App\Models\Servis;
use MongoDB\BSON\ObjectId;

class MigrateMysqlToMongo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-to-mongo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from legacy MySQL to MongoDB, including merging detail_servis into servis document array.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai migrasi dari MySQL ke MongoDB...');

        try {
            // Test koneksi MySQL
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $this->error('Koneksi ke MySQL gagal. Pastikan konfigurasi database "mysql" benar di file .env dan server berjalan.');
            return;
        }

        // 1. Migrasi Users
        $this->info('Migrasi Users...');
        $users = DB::connection('mysql')->table('users')->get();
        User::truncate();
        foreach ($users as $u) {
            User::create([
                'name' => $u->name,
                'email' => $u->email,
                'password' => $u->password,
                'created_at' => $u->created_at,
                'updated_at' => $u->updated_at,
            ]);
        }
        $this->info('Users berhasil dipindahkan: ' . count($users));

        // 2. Migrasi Master Jasas
        $this->info('Migrasi Master Jasas...');
        $jasas = DB::connection('mysql')->table('master_jasas')->get();
        MasterJasa::truncate();
        foreach ($jasas as $j) {
            MasterJasa::create([
                'nama_layanan' => $j->nama_layanan,
                'harga' => $j->harga ?? 0,
                'created_at' => $j->created_at,
                'updated_at' => $j->updated_at,
            ]);
        }
        $this->info('Master Jasa berhasil dipindahkan: ' . count($jasas));

        // 3. Migrasi Spareparts
        $this->info('Migrasi Spareparts...');
        $spareparts = DB::connection('mysql')->table('spareparts')->get();
        Sparepart::truncate();
        $sparepartMap = []; // old_id => new_id (ObjectId)
        foreach ($spareparts as $sp) {
            $newSp = Sparepart::create([
                'nama_sparepart' => $sp->nama_sparepart,
                'harga' => $sp->harga,
                'stok' => $sp->stok,
                'jenis_sparepart' => $sp->jenis_sparepart,
                'created_at' => $sp->created_at,
                'updated_at' => $sp->updated_at,
            ]);
            $sparepartMap[$sp->id_sparepart] = [
                '_id' => (string) $newSp->_id,
                'nama' => $sp->nama_sparepart,
                'harga' => $sp->harga
            ];
        }
        $this->info('Spareparts berhasil dipindahkan: ' . count($spareparts));

        // 4. Migrasi Kendaraan
        $this->info('Migrasi Kendaraan...');
        $kendaraans = DB::connection('mysql')->table('kendaraans')->get();
        Kendaraan::truncate();
        $kendaraanMap = [];
        foreach ($kendaraans as $k) {
            $newK = Kendaraan::create([
                'nama_pelanggan' => $k->nama_pelanggan,
                'nomor_polisi' => $k->nomor_polisi,
                'merk_kendaraan' => $k->merk_kendaraan,
                'no_hp' => $k->no_hp ?? null,
                'created_at' => $k->created_at,
                'updated_at' => $k->updated_at,
            ]);
            $kendaraanMap[$k->id_kendaraan] = (string) $newK->_id;
        }
        $this->info('Kendaraan berhasil dipindahkan: ' . count($kendaraans));

        // 5. Migrasi Servis + Detail Servis (Embedded Array)
        $this->info('Migrasi Servis dan Detail Servis...');
        // Cek tabel detail_servis apakah masih ada di MySQL (berjaga-jaga)
        $hasDetailServis = true;
        try {
            DB::connection('mysql')->table('detail_servis')->first();
        } catch (\Exception $e) {
            $hasDetailServis = false;
        }

        $servis = DB::connection('mysql')->table('servis')->get();
        Servis::truncate();
        foreach ($servis as $s) {
            $newKendaraanId = $kendaraanMap[$s->id_kendaraan] ?? null;

            $detailParts = [];
            if ($hasDetailServis) {
                $details = DB::connection('mysql')->table('detail_servis')
                             ->where('id_servis', $s->id_servis)
                             ->get();

                foreach ($details as $d) {
                    $spMap = $sparepartMap[$d->id_sparepart] ?? null;
                    if ($spMap) {
                        $detailParts[] = [
                            'sparepart_id' => $spMap['_id'],
                            'nama_sparepart' => $spMap['nama'],
                            'jumlah' => (int) $d->jumlah,
                            'subtotal' => (float) $d->subtotal,
                        ];
                    }
                }
            }

            Servis::create([
                'kendaraan_id' => $newKendaraanId ? new ObjectId($newKendaraanId) : null,
                'id_mekanik' => $s->id_mekanik,
                'jenis_servis' => $s->jenis_servis,
                'biaya_jasa' => (float) $s->biaya_jasa,
                'detail_parts' => $detailParts,
                'created_at' => $s->created_at,
                'updated_at' => $s->updated_at,
            ]);
        }
        $this->info('Servis dan Embedded Detail Parts berhasil dipindahkan: ' . count($servis));

        $this->info('Migrasi ke MongoDB Selesai!');
    }
}
