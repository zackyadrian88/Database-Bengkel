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
Route::get('/master-jasa', [App\Http\Controllers\BengkelController::class, 'masterJasa']);
Route::get('/servis', [BengkelController::class, 'getSemuaServis']);
Route::post('/servis', [BengkelController::class, 'bukaNota']);

Route::post('/servis/{id_servis}/sparepart', [BengkelController::class, 'pasangSparepart']);
Route::get('/servis/{id_servis}/tagihan', [BengkelController::class, 'cetakTagihan']);

// ==========================================
// 3. RUTE GUDANG SPAREPART
// ==========================================
Route::post('/tambah-barang', [BengkelController::class, 'tambahBarang']);
Route::post('/restock-barang', [BengkelController::class, 'restockBarang']);

// warehouse


Route::get('/', function () { return view('dashboard'); });
Route::get('/warehouse', function () { return view('warehouse'); });
// Nanti kamu bisa tambah route untuk /billing di sini
Route::get('/list-sparepart', [App\Http\Controllers\BengkelController::class, 'listSparepart']);
Route::get('/data-gudang', [App\Http\Controllers\BengkelController::class, 'gudangData']);
