<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BengkelController;

// ==========================================
// 1. RUTE KIOS ANTRIAN
// ==========================================
Route::get('/antrian-sekarang', [BengkelController::class, 'antrianSekarang']);
Route::post('/ambil-antrian', [BengkelController::class, 'ambilAntrian']);

// ==========================================
// 2. RUTE DASHBOARD & KASIR
// ==========================================
Route::get('/dashboard-stats', [BengkelController::class, 'dashboardStats']);
Route::get('/master-jasa', [BengkelController::class, 'masterJasa']);
Route::post('/servis', [BengkelController::class, 'bukaNota']);

Route::post('/servis/{id_servis}/sparepart', [BengkelController::class, 'pasangSparepart']);
Route::get('/servis/{id_servis}/tagihan', [BengkelController::class, 'cetakTagihan']);
Route::get('/cari-riwayat', [BengkelController::class, 'cariRiwayat']);

// ==========================================
// 3. RUTE GUDANG SPAREPART
// ==========================================
Route::post('/tambah-barang', [BengkelController::class, 'tambahBarang']);
Route::post('/restock-barang', [BengkelController::class, 'restockBarang']);
Route::get('/list-sparepart', [BengkelController::class, 'listSparepart']);
Route::get('/data-gudang', [BengkelController::class, 'gudangData']);
