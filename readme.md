🛠️ Workshop Management System - Jeki Speed
Sistem manajemen bengkel real-time yang mengintegrasikan Kios Antrian Mandiri dan Dashboard Admin/Kasir. Sistem ini dibangun menggunakan framework Laravel dan MySQL sebagai database utamanya.

🚀 Panduan Operasional Server
1. Menyalakan Server (Start)
Pastikan database MySQL Anda (lewat XAMPP/Laragon) sudah dalam posisi Start sebelum menjalankan langkah ini.

Buka terminal di VS Code.

Pastikan Anda berada di folder utama proyek (Bengkel).

Jalankan perintah berikut:

Bash
php artisan serve
Server akan berjalan di alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000).

Buka browser dan akses:

Dashboard Admin: [http://127.0.0.1:8000/](http://127.0.0.1:8000/)

Kios Antrian: [http://127.0.0.1:8000/kios](http://127.0.0.1:8000/kios)

2. Mematikan Server (Shutdown)
Jika operasional bengkel sudah selesai atau ingin melakukan maintenance, ikuti langkah ini:

Kembali ke terminal VS Code tempat server berjalan.

Tekan tombol kombinasi: Ctrl + C.

Terminal akan kembali ke baris perintah biasa, menandakan server telah berhenti.

Matikan MySQL di panel kontrol (XAMPP/Laragon) jika perangkat ingin dimatikan sepenuhnya.

🔧 Perintah Penting Maintenance
Gunakan perintah ini jika terjadi kendala pada data atau sistem:

Update Layanan AHASS:
Jika daftar layanan hilang atau ingin diperbarui sesuai standar 7 layanan AHASS Indonesia.

Bash
php artisan db:seed --class=MasterJasaSeeder
Membersihkan Cache (Jika Error 500 tetap muncul):
Berguna untuk menyegarkan rute dan tampilan setelah ada perubahan kode.

Bash
    php artisan route:clear
    php artisan view:clear
    php artisan config:clear
    ```
*   **Migrasi Database (Jika tabel belum ada):**
    ```bash
    php artisan migrate
    ```

---

## 📂 Struktur Penting
*   **Controller:** `app/Http/Controllers/BengkelController.php` (Logika utama).
*   **Models:** `app/Models/` (Struktur data Kendaraan, Servis, Sparepart, DetailServis).
*   **Views:** `resources/views/` (`dashboard.blade.php` & `kios.blade.php`).
*   **API Routes:** `routes/api.php` (Jalur data real-time).

---

> **Catatan:** Selalu pastikan file `DetailServis.php` tidak memiliki typo pada bagian `protected $table` agar proses input sparepart tidak gagal.
