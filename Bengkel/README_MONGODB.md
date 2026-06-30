# Panduan Migrasi & Deployment: MySQL ke MongoDB

Dokumen ini berisi panduan teknis lengkap mengenai proses instalasi, migrasi, dan deployment project "Database Bengkel" yang telah di-upgrade dari arsitektur relasional MySQL ke struktur dokumen MongoDB.

## 🌟 Mengapa Migrasi ke MongoDB?
1. **Performa Sangat Cepat**: Operasi baca (seperti memuat Dashboard dan Antrian) lebih ringan karena relasi yang rumit (seperti `detail_servis`) telah di-*embed* ke dalam satu collection nota (`servis`).
2. **Fleksibilitas Data**: Tidak perlu lagi mengubah struktur *schema* tabel jika sewaktu-waktu ada penambahan spesifikasi baru pada kendaraan atau nota servis.

---

## 🛠️ 1. Persiapan & Instalasi MongoDB
### A. Kebutuhan Sistem
*   PHP ^8.2
*   Ekstensi MongoDB untuk PHP (ext-mongodb)
*   Composer
*   MongoDB Server (Local/VPS)
*   MongoDB Compass (Opsional, untuk GUI)

### B. Setup Environment
1. Buka file `.env`.
2. Ubah `DB_CONNECTION` menjadi `mongodb`.
3. Tambahkan konfigurasi URI MongoDB:
```env
DB_CONNECTION=mongodb
DB_URI=mongodb://127.0.0.1:27017
DB_DATABASE=bengkel_db
```
> **Catatan:** Pastikan konfigurasi `mysql` lama Anda tetap dibiarkan agar script pemindah data bisa membaca database lama Anda.
```env
MYSQL_DB_CONNECTION=mysql
MYSQL_DB_HOST=127.0.0.1
MYSQL_DB_PORT=3306
MYSQL_DB_DATABASE=bengkel_lama
MYSQL_DB_USERNAME=root
MYSQL_DB_PASSWORD=
```
*(Catatan: jika di file config/database.php tidak ada konfigurasi tambahan untuk ini, command migrator akan tetap menggunakan koneksi "mysql" default di konfigurasi Laravel).*

---

## 🔄 2. Langkah Migrasi Data Lama
Jika Anda memiliki data bengkel lama yang masih berjalan di MySQL (XAMPP), Anda bisa memindahkannya sekaligus ke MongoDB tanpa kehilangan data!

1. Pastikan **MySQL / XAMPP** menyala dan database MySQL Anda bisa diakses.
2. Pastikan **MongoDB Server** juga sudah menyala.
3. Jalankan perintah migrator otomatis:
```bash
php artisan db:migrate-to-mongo
```
Perintah ini akan secara otomatis:
* Membaca `users`, `kendaraans`, `spareparts`, `master_jasas` dari MySQL dan mengkopinya ke MongoDB.
* Membaca `servis` dan menyatukan `detail_servis` (yang terpisah di MySQL) menjadi sebuah *Embedded Array* di dalam masing-masing nota servis di MongoDB.

---

## 🚀 3. Menjalankan Aplikasi
Setelah migrasi data selesai:
1. Pastikan struktur index MongoDB sudah terbuat:
```bash
php artisan migrate
```
2. Jalankan server Laravel:
```bash
php artisan serve
```
Aplikasi kini sepenuhnya berjalan di atas MongoDB!

---

## 🏗️ 4. Optimasi Database (Indexing)
Untuk menjaga performa seiring bertambahnya data antrian dan nota servis bengkel, pastikan *Collection* MongoDB memiliki Index yang tepat. Script migration yang telah di-update akan otomatis membuat index pada kolom-kolom berikut:
*   `kendaraans`: `created_at` (untuk pencarian antrian harian tercepat), `nama_pelanggan`, `nomor_polisi`.
*   `spareparts`: `nama_sparepart`, `stok`.
*   `servis`: `created_at` (untuk pencarian laporan harian), `kendaraan_id`.

Jika Anda menambah pencarian baru yang kompleks, buatlah compound index menggunakan CLI MongoDB atau Compass.

---

## 🧪 5. Checklist Testing Pasca-Migrasi
Sebelum bengkel benar-benar beroperasi dengan versi baru, wajib pastikan poin berikut sukses:
- [ ] **Kios Antrian**: Coba cetak nomor antrean baru. Pastikan nomor urut hari ini bertambah dan tampil benar.
- [ ] **Dashboard Admin**: Buka Dashboard. Pastikan angka *Total Nota* dan *Pendapatan Hari Ini* tampil (artinya agregasi baca embedded part sukses).
- [ ] **Buka Nota**: Buka nota untuk pelanggan yang baru mendaftar di Kios.
- [ ] **Pasang Sparepart**: Coba tambahkan sparepart ke nota yang berjalan. Periksa stok sparepart (harus berkurang).
- [ ] **Cetak Tagihan**: Buka halaman kasir/cetak. Pastikan rincian sparepart yang terpasang dan grand total biaya jasa terakumulasi dengan tepat.

---

## 🌐 6. Panduan Deployment Production (VPS)
Jika Anda ingin *online* ke server (VPS Ubuntu/Debian):
1. Install **MongoDB Community Edition** di server Ubuntu.
2. Install ekstensi PHP MongoDB: `sudo apt install php8.2-mongodb`.
3. Clone project Laravel Anda.
4. Jalankan `composer install --optimize-autoloader --no-dev`.
5. Salin `.env` dari versi lokal, ganti `APP_ENV=production` dan `APP_DEBUG=false`.
6. Pastikan `DB_URI` mengarah ke MongoDB lokal VPS atau ke MongoDB Atlas (jika menggunakan cloud DB).
7. Konfigurasi **Nginx / Apache** untuk mengarahkan root direktori ke `/public`.
8. Gunakan **Supervisor** untuk menjaga agar antrean atau background job berjalan dengan stabil jika nanti ditambahkan fitur notifikasi WhatsApp.

Selamat menggunakan Jeki Speed Bengkel versi MongoDB! 🚀
