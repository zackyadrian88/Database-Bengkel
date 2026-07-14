<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>JEKI SPEED | Kios Antrean</title>

    <!-- Violet Issue Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5E6AD2', 
                        primaryHover: '#4B55B0', 
                        bgBase: '#101014',
                        bgPanel: '#1B1B25',
                        borderPanel: '#2C2C3A',
                        textMain: '#E4E4E5',
                        textSec: '#8A8F98',
                        statusEmerald: '#3DD68C',
                    },
                    fontFamily: { 
                        body: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #101014; color: #E4E4E5; }
        
        .lf-card { 
            background: #1B1B25; 
            border-radius: 8px; 
            border: 1px solid #2C2C3A; 
            box-shadow: none; 
            overflow: hidden;
        }

        .lf-btn-primary { 
            background-color: #5E6AD2; 
            color: #FFFFFF; 
            border-radius: 6px; 
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s; 
            box-shadow: none;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .lf-btn-primary:hover { background-color: #4B55B0; }
        
        .lf-input { 
            background: #1B1B25 !important; 
            border: 1px solid #2C2C3A !important; 
            color: #E4E4E5 !important; 
            border-radius: 9999px !important; 
            height: 32px;
            padding: 0 12px; 
            font-family: 'Inter', sans-serif; 
            font-size: 13px;
            transition: all 0.2s; 
            box-shadow: none !important;
            width: 100%;
            margin-bottom: 12px;
        }
        .lf-input::placeholder { color: #8A8F98 !important; }
        .lf-input:focus { outline: none; border-color: #5E6AD2 !important; box-shadow: 0 0 0 2px rgba(94,106,210,0.25) !important; }
    </style>
</head>
<body class="font-body flex flex-col items-center justify-center min-h-screen p-6 relative bg-bgBase text-[13px]">

    <div class="text-center mb-8">
        <h2 class="text-3xl font-body font-bold text-primary tracking-wide mb-1">JEKI SPEED</h2>
        <p class="text-[13px] text-textSec font-medium">Sistem Registrasi Antrean Kendaraan</p>
    </div>

    <!-- MAIN CORES DISPLAY -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl relative z-10">
        <!-- FORM AMBIL ANTREAN -->
        <div class="lf-card p-6 text-center flex flex-col items-center justify-center">
            <div class="w-12 h-12 bg-bgBase border border-borderPanel rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-primary text-[24px]">confirmation_number</span>
            </div>
            <h3 class="text-[16px] font-body font-semibold mb-1 text-textMain">Ambil Antrean</h3>
            <p class="text-[11px] text-textSec mb-6">Daftarkan kendaraan Anda ke sistem mekanik.</p>

            <div class="w-full text-left">
                <input type="text" id="kNama" class="lf-input" placeholder="Nama Lengkap">
                <input type="text" id="kMotor" class="lf-input" placeholder="Merk Motor (Cth: Vario)">
                <input type="text" id="kPlat" class="lf-input font-mono uppercase" placeholder="Nomor Polisi (Cth: B 1234 ABC)">
            </div>

            <button type="button" onclick="ambilAntrean()" class="lf-btn-primary mt-2">Daftar Sekarang</button>
            <div id="msgKios" class="mt-3 text-[11px] font-mono h-3 text-center"></div>
        </div>

        <!-- INFO LIVE ANTREAN COUNTER -->
        <div class="lf-card p-6 bg-bgHover border-borderPanel flex flex-col items-center justify-center relative overflow-hidden">
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/10 rounded-bl-full blur-2xl"></div>
            
            <h3 class="text-[16px] font-body font-semibold mb-1 text-textMain relative z-10">Antrean Hari Ini</h3>
            <p class="text-textSec mb-6 text-[11px] relative z-10">Total kendaraan yang sedang dilayani</p>

            <div class="w-32 h-32 bg-bgBase rounded-full flex items-center justify-center border border-borderPanel mb-6 relative z-10">
                <span id="jmlAntrean" class="text-[48px] font-mono font-bold text-primary">0</span>
            </div>
            <p class="text-[10px] font-medium text-textSec tracking-widest uppercase relative z-10">Kendaraan</p>
        </div>
    </div>

    <!-- BACKGROUND LIGHT OVERLAY -->
    <div id="overlay" class="fixed inset-0 bg-bgBase/80 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <!-- POP-UP MODAL NOMOR ANTREAN UNTUK PELANGGAN -->
    <div id="successModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-xs z-50 hidden">
        <div class="lf-card p-6 text-center border-t-2 border-t-statusEmerald">
            <div class="w-12 h-12 bg-statusEmerald/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-statusEmerald text-[24px]">check_circle</span>
            </div>
            <h3 class="text-[20px] font-body font-bold text-textMain mb-1">Berhasil!</h3>
            <p class="text-textSec text-[11px] mb-5">Harap ingat Nomor Antrean Anda</p>

            <div class="bg-bgBase border border-borderPanel rounded-lg py-5 mb-5">
                <p class="text-[9px] font-bold text-textSec uppercase tracking-widest mb-1">Nomor Urut</p>
                <div class="text-[40px] font-mono font-bold text-primary leading-none" id="tampilNomorAntrean">0</div>
            </div>

            <button type="button" onclick="closeModal('successModal')" class="lf-btn-primary">Selesai</button>
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
                e.className = "mt-3 text-[11px] font-mono h-3 text-[#EB5757]";
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
                    document.getElementById('tampilNomorAntrean').innerText = d.urutan;
                    openModal('successModal');
                    
                    document.getElementById('kNama').value = "";
                    document.getElementById('kMotor').value = "";
                    document.getElementById('kPlat').value = "";
                    loadAntrian();
                } else {
                    const e = document.getElementById('msgKios');
                    e.innerText = "Gagal mendaftar ke server.";
                    e.className = "mt-3 text-[11px] font-mono h-3 text-[#EB5757]";
                    setTimeout(() => e.innerText = "", 4000);
                }
            } catch (error) {
                const e = document.getElementById('msgKios');
                e.innerText = "Koneksi ke server terputus!";
                e.className = "mt-3 text-[11px] font-mono h-3 text-[#EB5757]";
                setTimeout(() => e.innerText = "", 4000);
            }
        }

        window.onload = loadAntrian;
        setInterval(loadAntrian, 5000);
    </script>
</body>
</html>
