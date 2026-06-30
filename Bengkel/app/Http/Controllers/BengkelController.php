<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\MasterJasa;
use App\Models\Servis;
use App\Models\Sparepart;

class BengkelController extends Controller
{
    // --- KIOS ANTRIAN ---
    public function antrianSekarang()
    {
        return response()->json([
            'antrian_sekarang' => Kendaraan::whereDate('created_at', today())->count()
        ]);
    }

    public function ambilAntrian(Request $request)
    {
        $k = Kendaraan::create($request->all());

        // Hitung total urutan hari ini sebagai referensi nomor tiket live untuk pelanggan
        $urutanHariIni = Kendaraan::whereDate('created_at', today())->count();

        return response()->json([
            'id_kendaraan' => (string) $k->_id,
            'urutan'       => $urutanHariIni
        ]);
    }

    // --- REAL-TIME DASHBOARD STATS ---
    public function dashboardStats()
    {
        $hariIni = today();

        // Hitung total nota hari ini
        $servisHariIni = Servis::whereDate('created_at', $hariIni)->get();

        $totalBiayaJasa = $servisHariIni->sum('biaya_jasa');
        $totalSubtotalParts = 0;
        $totalPartTerpasang = 0;

        foreach ($servisHariIni as $s) {
            $parts = $s->detail_parts ?? [];
            foreach ($parts as $p) {
                $totalSubtotalParts += $p['subtotal'] ?? 0;
                $totalPartTerpasang += $p['jumlah'] ?? 0;
            }
        }

        $stats = [
            'total_nota'         => $servisHariIni->count(),
            'total_pendapatan'   => (int)($totalBiayaJasa + $totalSubtotalParts),
            'part_terpasang'     => (int)$totalPartTerpasang,
            'stok_menipis_count' => Sparepart::where('stok', '<=', 5)->count(),
            'stok_menipis_items' => Sparepart::where('stok', '<=', 5)->get()->map(function ($item) {
                return [
                    '_id'            => (string) $item->_id,
                    'nama_sparepart' => $item->nama_sparepart,
                    'stok'           => $item->stok,
                ];
            }),
        ];

        // Recent logs — ambil 5 servis terakhir dengan relasi kendaraan
        $recentServis = Servis::orderBy('created_at', 'desc')->limit(5)->get();
        $stats['recent_logs'] = $recentServis->map(function ($log) {
            $kendaraan = $log->kendaraan; // via relasi belongsTo

            $subtotalParts = 0;
            foreach ($log->detail_parts ?? [] as $p) {
                $subtotalParts += $p['subtotal'] ?? 0;
            }

            return [
                '_id'             => (string) $log->_id,
                'id_servis'       => (string) $log->_id,
                'no_nota_simpel'  => $log->no_nota_simpel ?? '-',
                'kendaraan_id'    => (string) $log->kendaraan_id,
                'id_mekanik'      => $log->id_mekanik,
                'jenis_servis'    => $log->jenis_servis,
                'biaya_jasa'      => $log->biaya_jasa,
                'created_at'      => $log->created_at,
                'nama_pelanggan'  => $kendaraan->nama_pelanggan ?? null,
                'nomor_polisi'    => $kendaraan->nomor_polisi ?? null,
                'merk_kendaraan'  => $kendaraan->merk_kendaraan ?? null,
                'grand_total'     => (int)($log->biaya_jasa + $subtotalParts),
            ];
        });

        // Active pits — 3 servis terakhir
        $stats['active_pits'] = Servis::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($s) {
                $kendaraan = $s->kendaraan;
                return [
                    'id_mekanik'      => $s->id_mekanik,
                    'merk_kendaraan'  => $kendaraan->merk_kendaraan ?? '-',
                    'jenis_servis'    => $s->jenis_servis,
                ];
            });

        return response()->json($stats);
    }

    // MEMPERBAIKI DATA JASA AGAR HARGA TIDAK COPOK / RP 0
    public function masterJasa()
    {
        $jasa = MasterJasa::all()->map(function($j) {
            return [
                'nama_layanan'         => $j->nama_layanan,
                'biaya_standar'        => (int) ($j->biaya_standar ?? $j->harga ?? 0), // Fallback jika nama kolom beda
                'id_mekanik_default'   => (int) ($j->id_mekanik_default ?? $j->id_mekanik ?? 1)
            ];
        });
        return response()->json($jasa);
    }

    public function listSparepart()
    {
        return response()->json(
            Sparepart::where('stok', '>', 0)
                ->orderBy('nama_sparepart', 'asc')
                ->get()
                ->map(function ($p) {
                    return [
                        '_id'             => (string) $p->_id,
                        'id_sparepart'    => (string) $p->_id, 
                        'nama_sparepart'  => $p->nama_sparepart,
                        'harga'           => (int) $p->harga,
                        'stok'            => (int) $p->stok,
                        'jenis_sparepart' => $p->jenis_sparepart,
                    ];
                })
        );
    }

    public function gudangData()
    {
        return response()->json(
            Sparepart::orderBy('nama_sparepart', 'asc')
                ->get()
                ->map(function ($p) {
                    return [
                        '_id'             => (string) $p->_id,
                        'id_sparepart'    => (string) $p->_id, 
                        'nama_sparepart'  => $p->nama_sparepart,
                        'harga'           => (int) $p->harga,
                        'stok'            => (int) $p->stok,
                        'jenis_sparepart' => $p->jenis_sparepart,
                    ];
                })
        );
    }

    // --- BUKA NOTA DENGAN FITUR NOMOR NOTA SIMPEL (AUTO-INCREMENT) ---
   // 1. GANTI FUNGSI BUKANOTA
    public function bukaNota(Request $request)
    {
        $hariIni = today();
        $nomorAntreanInput = (int) $request->id_kendaraan;

        $kendaraan = Kendaraan::whereDate('created_at', $hariIni)
            ->orderBy('created_at', 'asc')
            ->skip($nomorAntreanInput - 1)
            ->first();

        if (!$kendaraan) {
            return response()->json(['pesan' => 'Nomor antrean harian tidak ditemukan!'], 404);
        }

        $totalNotaHariIni = Servis::whereDate('created_at', $hariIni)->count();
        $noNotaSimpel = $totalNotaHariIni + 1;

        $s = Servis::create([
            'no_nota_simpel' => (int) $noNotaSimpel,
            'kendaraan_id'   => $kendaraan->_id,
            'id_mekanik'     => (int) $request->id_mekanik,
            'jenis_servis'   => $request->jenis_servis,
            'biaya_jasa'     => (float) $request->biaya_jasa,
            'detail_parts'   => [], 
        ]);

        // KITA TAMBAHKAN 'no_nota' DI RESPON AGAR BISA DIBACA JAVASCRIPT
        return response()->json([
            'id_servis' => (string) $s->_id,
            'no_nota'   => $s->no_nota_simpel 
        ]);
    }

    // 2. GANTI FUNGSI PASANGSPAREPART
    public function pasangSparepart(Request $request, $id_servis)
    {
        // MODIFIKASI: Bisa cari pakai nomor urut simpel atau ID panjang MongoDB
        $servis = is_numeric($id_servis) 
            ? Servis::where('no_nota_simpel', (int) $id_servis)->first() 
            : Servis::find($id_servis);

        if (!$servis) {
            return response()->json(['pesan' => 'Nota Tidak Ditemukan'], 404);
        }

        $p = Sparepart::find($request->id_sparepart);
        if (!$p || $p->stok < $request->jumlah) {
            return response()->json(['pesan' => 'Stok Kurang'], 400);
        }

        $p->decrement('stok', $request->jumlah);

        $servis->push('detail_parts', [
            'sparepart_id'   => (string) $p->_id,
            'nama_sparepart' => $p->nama_sparepart,
            'jumlah'         => (int) $request->jumlah,
            'subtotal'       => (float) ($p->harga * $request->jumlah),
        ]);

        return response()->json(['pesan' => 'Sukses']);
    }

    // 3. GANTI FUNGSI CETAKTAGIHAN
    public function cetakTagihan($id_servis)
    {
        // MODIFIKASI: Bisa cari pakai nomor urut simpel atau ID panjang MongoDB
        $s = is_numeric($id_servis) 
            ? Servis::where('no_nota_simpel', (int) $id_servis)->first() 
            : Servis::find($id_servis);

        if (!$s) {
            return response()->json(['pesan' => 'Nota Tidak Ditemukan'], 404);
        }

        $kendaraan = $s->kendaraan;
        $parts = collect($s->detail_parts ?? []);

        return response()->json([
            'nota' => [
                '_id'            => (string) $s->_id,
                'id_servis'      => (string) $s->_id,
                'no_nota'        => $s->no_nota_simpel ?? 1,
                'kendaraan_id'   => (string) $s->kendaraan_id,
                'id_mekanik'     => $s->id_mekanik,
                'jenis_servis'   => $s->jenis_servis,
                'biaya_jasa'     => (int) $s->biaya_jasa,
                'created_at'     => $s->created_at,
            ],
            'kendaraan' => $kendaraan
                ? [
                    '_id'            => (string) $kendaraan->_id,
                    'nama_pelanggan' => $kendaraan->nama_pelanggan,
                    'merk_kendaraan' => $kendaraan->merk_kendaraan,
                    'nomor_polisi'   => $kendaraan->nomor_polisi,
                ]
                : ['nama_pelanggan' => 'Pelanggan Umum', 'merk_kendaraan' => '-', 'nomor_polisi' => '-'],
            'sparepart'   => $parts->map(function ($p) {
                return [
                    'nama_sparepart' => $p['nama_sparepart'],
                    'jumlah'         => (int) $p['jumlah'],
                    'subtotal'       => (int) $p['subtotal'],
                ];
            })->values(),
            'grand_total' => (int)($s->biaya_jasa + $parts->sum('subtotal')),
        ]);
    }
    
    public function tambahBarang(Request $request)
    {
        Sparepart::create($request->all());
        return response()->json(['pesan' => 'Disimpan']);
    }

    public function restockBarang(Request $request)
    {
        $p = Sparepart::find($request->id_sparepart);
        if (!$p) {
            return response()->json(['pesan' => 'Part Tidak Ditemukan'], 404);
        }
        $p->increment('stok', $request->tambah_stok);
        return response()->json(['pesan' => 'Sukses']);
    }
}