<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\MasterJasa;
use App\Models\Servis;
use App\Models\Sparepart;
use App\Models\DetailServis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BengkelController extends Controller
{
    // --- KIOS ANTRIAN ---
    public function antrianSekarang() {
        return response()->json(['antrian_sekarang' => Kendaraan::whereDate('created_at', today())->count()]);
    }

    public function ambilAntrian(Request $request) {
        $k = Kendaraan::create($request->all());

        // Hitung total urutan hari ini sebagai referensi nomor tiket live untuk pelanggan
        $urutanHariIni = Kendaraan::whereDate('created_at', today())->count();

        return response()->json([
            'id_kendaraan' => $k->id_kendaraan ?? $k->id,
            'urutan'       => $urutanHariIni
        ]);
    }

    // --- REAL-TIME DASHBOARD STATS ---
    public function dashboardStats() {
        $hariIni = today();

        $stats = [
            'total_nota'         => Servis::whereDate('created_at', $hariIni)->count(),
            'total_pendapatan'   => (int)(Servis::whereDate('created_at', $hariIni)->sum('biaya_jasa') + DetailServis::whereDate('created_at', $hariIni)->sum('subtotal')),
            'part_terpasang'     => (int)DetailServis::whereDate('created_at', $hariIni)->sum('jumlah'),
            'stok_menipis_count' => Sparepart::where('stok', '<=', 5)->count(),
            'stok_menipis_items' => Sparepart::where('stok', '<=', 5)->get(),
        ];

        $stats['recent_logs'] = Servis::leftJoin('kendaraans', 'servis.id_kendaraan', '=', 'kendaraans.id_kendaraan')
            ->select('servis.*', 'kendaraans.nama_pelanggan', 'kendaraans.nomor_polisi', 'kendaraans.merk_kendaraan')
            ->orderBy('servis.created_at', 'desc')
            ->limit(5)
            ->get()
            ->each(function ($log) {
                $log->grand_total = $log->biaya_jasa + DetailServis::where('id_servis', $log->id_servis)->sum('subtotal');
            });

        if (Schema::hasTable('mekaniks')) {
            $stats['active_pits'] = Servis::leftJoin('mekaniks', 'servis.id_mekanik', '=', 'mekaniks.id')
                ->leftJoin('kendaraans', 'servis.id_kendaraan', '=', 'kendaraans.id_kendaraan')
                ->select('mekaniks.nama_mekanik', 'kendaraans.merk_kendaraan', 'servis.jenis_servis', 'servis.id_mekanik')
                ->orderBy('servis.created_at', 'desc')
                ->limit(3)
                ->get();
        } else {
            $stats['active_pits'] = [];
        }

        return response()->json($stats);
    }

    public function masterJasa() {
        return response()->json(MasterJasa::all());
    }

    public function listSparepart() {
        return response()->json(Sparepart::where('stok', '>', 0)->orderBy('nama_sparepart', 'asc')->get());
    }

    public function gudangData() {
        return response()->json(Sparepart::orderBy('nama_sparepart', 'asc')->get());
    }

    // --- LOGIKA BUKA NOTA BERDASARKAN NOMOR URUT ANTREAN HARIAN BUKAN ID DATABASE ---
    public function bukaNota(Request $request) {
        $hariIni = today();
        $nomorAntreanInput = (int) $request->id_kendaraan;

        // Cari data pendaftaran kendaraan ke-N berdasarkan waktu urut hari ini
        $kendaraan = Kendaraan::whereDate('created_at', $hariIni)
            ->orderBy('created_at', 'asc')
            ->skip($nomorAntreanInput - 1)
            ->first();

        if (!$kendaraan) {
            return response()->json(['pesan' => 'Nomor antrean harian tidak ditemukan!'], 404);
        }

        $s = Servis::create([
            'id_kendaraan' => $kendaraan->id_kendaraan ?? $kendaraan->id,
            'id_mekanik'   => $request->id_mekanik,
            'jenis_servis' => $request->jenis_servis,
            'biaya_jasa'   => $request->biaya_jasa,
        ]);

        return response()->json(['id_servis' => $s->id_servis]);
    }

    public function pasangSparepart(Request $request, $id_servis) {
        return DB::transaction(function () use ($request, $id_servis) {
            $p = Sparepart::where('id_sparepart', $request->id_sparepart)->first();
            if (!$p || $p->stok < $request->jumlah) return response()->json(['pesan' => 'Stok Kurang'], 400);

            $p->decrement('stok', $request->jumlah);
            DetailServis::create([
                'id_servis' => $id_servis, 'id_sparepart' => $p->id_sparepart,
                'jumlah' => $request->jumlah, 'subtotal' => $p->harga * $request->jumlah
            ]);
            return response()->json(['pesan' => 'Sukses']);
        });
    }

    public function cetakTagihan($id_servis) {
        $s = Servis::where('id_servis', $id_servis)->first();
        if (!$s) return response()->json(['pesan' => 'Nota Tidak Ditemukan'], 404);
        $k = Kendaraan::where('id_kendaraan', $s->id_kendaraan)->first();

        $p = DetailServis::where('id_servis', $id_servis)
            ->leftJoin('spareparts', 'detail_servis.id_sparepart', '=', 'spareparts.id_sparepart')
            ->select('spareparts.nama_sparepart', 'detail_servis.jumlah', 'detail_servis.subtotal')
            ->get();

        return response()->json([
            'nota'        => $s,
            'kendaraan'   => $k ?? ['nama_pelanggan' => 'Pelanggan Umum', 'merk_kendaraan' => '-', 'nomor_polisi' => '-'],
            'sparepart'   => $p,
            'grand_total' => (int)($s->biaya_jasa + $p->sum('subtotal'))
        ]);
    }

    public function tambahBarang(Request $request) { Sparepart::create($request->all()); return response()->json(['pesan' => 'Disimpan']); }
    public function restockBarang(Request $request) {
        $p = Sparepart::where('id_sparepart', $request->id_sparepart)->first();
        if (!$p) return response()->json(['pesan' => 'Part Tidak Ditemukan'], 404);
        $p->increment('stok', $request->tambah_stok);
        return response()->json(['pesan' => 'Sukses']);
    }
}
