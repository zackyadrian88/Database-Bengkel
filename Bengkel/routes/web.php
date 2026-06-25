<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BengkelController;

// ==========================================
// 1. RUTE VIEW HALAMAN UTAMA (WEB VIEW)
// ==========================================
Route::get('/', function () { return view('dashboard'); });
Route::get('/warehouse', function () { return view('warehouse'); });
Route::get('/billing', function () { return view('billing'); });
Route::get('/kios', function () { return view('kios'); });

// ==========================================
// 2. RUTE API DATA (PREFIX /api)
// ==========================================
Route::prefix('api')->group(function () {
    // Kios Antrian Data
    Route::get('/antrian-sekarang', [BengkelController::class, 'antrianSekarang']);
    Route::post('/ambil-antrian', [BengkelController::class, 'ambilAntrian']);

    // Dashboard & Kasir Data
    Route::get('/dashboard-stats', [BengkelController::class, 'dashboardStats']);
    Route::get('/master-jasa', [BengkelController::class, 'masterJasa']);
    Route::post('/servis', [BengkelController::class, 'bukaNota']);
    Route::post('/servis/{id_servis}/sparepart', [BengkelController::class, 'pasangSparepart']);
    Route::get('/servis/{id_servis}/tagihan', [BengkelController::class, 'cetakTagihan']);

    // Warehouse Data
    Route::post('/tambah-barang', [BengkelController::class, 'tambahBarang']);
    Route::post('/restock-barang', [BengkelController::class, 'restockBarang']);
    Route::get('/list-sparepart', [BengkelController::class, 'listSparepart']);
    Route::get('/data-gudang', [BengkelController::class, 'gudangData']);
});
