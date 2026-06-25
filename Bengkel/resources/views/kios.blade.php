<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>JEKI SPEED | Kios Antrean</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#F97316', secondary: '#3B82F6', success: '#22C55E', background: '#F9FAFB' },
                    fontFamily: { headline: ['Fredoka', 'sans-serif'], body: ['Poppins', 'sans-serif'] },
                    boxShadow: { 'md': '0 4px 6px -1px rgba(0, 0, 0, 0.10)', 'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.10)' },
                    borderRadius: { 'md': '12px', 'circle': '50%' }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F9FAFB; }
        .lf-card { background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.10); }
        .lf-input { border: 1px solid #D1D5DB; border-radius: 6px; padding: 16px; width: 100%; transition: all 0.2s; margin-bottom: 16px; font-family: 'Poppins', sans-serif;}
        .lf-input:focus { outline: none; border-color: #F97316; box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3); }
        .lf-btn-primary { background-color: #F97316; color: #FFFFFF; border-radius: 12px; padding: 16px; width: 100%; font-weight: 600; font-size: 16px; transition: all 0.2s; letter-spacing: 0.5px; text-transform: uppercase; }
        .lf-btn-primary:hover { background-color: #EA580C; }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-6 relative">

    <div class="text-center mb-10">
        <h2 class="text-4xl md:text-5xl font-headline font-bold text-primary mb-3">JEKI SPEED</h2>
        <p class="text-lg text-gray-500 font-medium">Sistem Registrasi Antrean Kendaraan</p>
    </div>

    <!-- MAIN CORES DISPLAY -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl relative z-10">
        <!-- FORM AMBIL ANTREAN -->
        <div class="lf-card text-center flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-[#FFF7ED] rounded-circle flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary text-4xl">confirmation_number</span>
            </div>
            <h3 class="text-2xl font-headline font-semibold mb-2 text-gray-800">Ambil Antrean</h3>
            <p class="text-sm text-gray-500 mb-8">Daftarkan kendaraan Anda ke sistem mekanik.</p>

            <div class="w-full text-left">
                <input type="text" id="kNama" class="lf-input" placeholder="Nama Lengkap">
                <input type="text" id="kMotor" class="lf-input" placeholder="Merk Motor (Cth: Vario)">
                <input type="text" id="kPlat" class="lf-input" placeholder="Nomor Polisi (Cth: B 1234 ABC)">
            </div>

            <button type="button" onclick="ambilAntrean()" class="lf-btn-primary mt-2">DAFTAR SEKARANG</button>
            <div id="msgKios" class="mt-4 text-sm font-bold h-4 text-center"></div>
        </div>

        <!-- INFO LIVE ANTREAN COUNTER -->
        <div class="lf-card bg-primary text-white border-none flex flex-col items-center justify-center">
            <h3 class="text-2xl font-headline font-semibold mb-2">Antrean Hari Ini</h3>
            <p class="text-orange-100 mb-8 text-sm">Total kendaraan yang sedang dilayani</p>

            <div class="w-48 h-48 bg-white/20 rounded-circle flex items-center justify-center border-4 border-white border-dashed mb-8">
                <span id="jmlAntrean" class="text-7xl font-headline font-bold text-white">0</span>
            </div>
            <p class="text-lg font-medium text-orange-50 tracking-widest uppercase">Kendaraan</p>
        </div>
    </div>

    <!-- BACKGROUND LIGHT OVERLAY -->
    <div id="overlay" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <!-- POP-UP MODAL NOMOR ANTREAN UNTUK PELANGGAN -->
    <div id="successModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-50 hidden">
        <div class="lf-card text-center border-t-8 border-[#22C55E] shadow-xl">
            <div class="w-20 h-20 bg-[#BBF7D0] rounded-circle flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[#15803D] text-4xl">check_circle</span>
            </div>
            <h3 class="text-3xl font-headline font-bold text-gray-800 mb-1">Berhasil!</h3>
            <p class="text-gray-500 text-sm mb-6">Harap ingat Nomor Antrean Anda</p>

            <div class="bg-[#FFF7ED] border-2 border-primary border-dashed rounded-xl py-6 mb-6">
                <p class="text-xs font-bold text-primary uppercase tracking-widest mb-2">NOMOR URUT</p>
                <div class="text-7xl font-headline font-black text-primary" id="tampilNomorAntrean">0</div>
            </div>

            <button type="button" onclick="closeModal('successModal')" class="lf-btn-primary w-full py-4 text-sm">SELESAI</button>
        </div>
    </div>

    <script>
        const API = window.location.origin + "/api";

        function openModal(id) { document.getElementById('overlay').classList.remove('hidden'); document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById('overlay').classList.add('hidden'); document.getElementById(id).classList.add('hidden'); }

        async function loadAntrian() {
            try {
                const res = await fetch(`${API}/antrian-sekarang`);
                if(res.ok) {
                    const d = await res.json();
                    document.getElementById('jmlAntrean').innerText = d.antrian_sekarang || 0;
                }
            } catch (e) { console.log("Gagal memuat antrean"); }
        }

        async function ambilAntrean() {
            const payload = {
                nama_pelanggan: document.getElementById('kNama').value,
                merk_kendaraan: document.getElementById('kMotor').value,
                nomor_polisi: document.getElementById('kPlat').value
            };

            if(!payload.nama_pelanggan || !payload.merk_kendaraan || !payload.nomor_polisi) {
                const e = document.getElementById('msgKios');
                e.innerText = "Harap lengkapi semua data!";
                e.className = "mt-4 text-sm font-bold h-4 text-[#EF4444]";
                setTimeout(() => e.innerText = "", 3000);
                return;
            }

            try {
                const res = await fetch(`${API}/ambil-antrian`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });

                if(res.ok) {
                    const d = await res.json();

                    // Isi teks nomor urut harian hasil hitung server, lalu panggil pop-up modal
                    document.getElementById('tampilNomorAntrean').innerText = d.urutan;
                    openModal('successModal');

                    // Reset input kolom pendaftaran
                    document.getElementById('kNama').value = "";
                    document.getElementById('kMotor').value = "";
                    document.getElementById('kPlat').value = "";

                    loadAntrian();
                } else {
                    const e = document.getElementById('msgKios');
                    e.innerText = "Gagal mendaftar ke server.";
                    e.className = "mt-4 text-sm font-bold h-4 text-[#EF4444]";
                    setTimeout(() => e.innerText = "", 4000);
                }
            } catch (error) {
                const e = document.getElementById('msgKios');
                e.innerText = "Koneksi ke server terputus!";
                e.className = "mt-4 text-sm font-bold h-4 text-[#EF4444]";
                setTimeout(() => e.innerText = "", 4000);
            }
        }

        window.onload = loadAntrian;
        setInterval(loadAntrian, 5000);
    </script>
</body>
</html>
