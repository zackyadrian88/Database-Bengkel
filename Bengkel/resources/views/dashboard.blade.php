@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h2 class="font-headline text-3xl font-bold text-gray-800">Rekap Data Penjualan</h2>
        <p class="text-gray-500 mt-1 text-sm"></p>
    </div>

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="lf-card flex flex-col justify-center">
            <p class="text-sm text-gray-500 font-medium mb-2">Total Nota</p>
            <h3 class="text-4xl font-headline font-bold text-primary" id="valNota">0</h3>
        </div>
        <div class="lf-card flex flex-col justify-center">
            <p class="text-sm text-gray-500 font-medium mb-2">Part Terpasang</p>
            <h3 class="text-4xl font-headline font-bold text-secondary" id="valPart">0</h3>
        </div>
        <div class="lf-card flex flex-col justify-center">
            <p class="text-sm text-gray-500 font-medium mb-2">Pendapatan</p>
            <h3 class="text-2xl font-mono font-bold text-tertiary mt-2" id="valPendapatan">Rp 0</h3>
        </div>
        <div class="lf-card flex flex-col justify-center border-l-4 border-error">
            <p class="text-sm text-gray-500 font-medium mb-2">Stok Menipis</p>
            <h3 class="text-4xl font-headline font-bold text-error" id="valStokLow">0</h3>
        </div>
    </section>

    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="lf-card flex flex-col h-full">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 rounded-md bg-[#FFF7ED] flex items-center justify-center text-primary"><span class="material-symbols-outlined">receipt</span></div>
                    <h4 class="font-headline text-lg font-semibold text-gray-800">Buka Nota Baru</h4>
                </div>
                <div class="space-y-4 flex-1">
                    <div id="msgNota" class="text-sm font-bold text-[#15803D] h-4"></div>
                    <input type="number" id="idKen" class="lf-input w-full" placeholder="Masukkan No. Antrean Kios (Cth: 1, 2, 3)">
                    <select id="selJasa" onchange="syncJasa()" class="lf-input w-full"></select>
                    <input type="hidden" id="idMek">
                    <input type="number" id="biaya" class="lf-input w-full bg-gray-50 cursor-not-allowed" readonly placeholder="Biaya Otomatis">
                </div>
                <button type="button" onclick="prosesNota()" class="lf-btn-primary w-full py-3 mt-6 font-semibold">Buat Nota</button>
            </div>

            <div class="lf-card flex flex-col h-full">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center text-secondary"><span class="material-symbols-outlined">build</span></div>
                    <h4 class="font-headline text-lg font-semibold text-gray-800">Input Sparepart</h4>
                </div>
                <div class="space-y-4 flex-1">
                    <div id="msgPart" class="text-sm font-bold text-[#15803D] h-4"></div>
                    <input type="text" id="notaId" class="lf-input w-full" placeholder="ID Nota Target">
                    <select id="selPart" class="lf-input w-full">
                        <option value="" disabled selected>Memuat Data Gudang...</option>
                    </select>
                    <input type="number" id="qty" class="lf-input w-full" placeholder="Jumlah Pemasangan" value="1" min="1">
                </div>
                <button type="button" onclick="tambahPart()" class="lf-btn-secondary w-full py-3 mt-6 font-semibold">Pasang Sparepart</button>
            </div>

            <div class="lf-card col-span-1 md:col-span-2">
                <h4 class="font-headline text-lg font-semibold text-gray-800 mb-4">Kasir & Tagihan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" id="cariId" class="lf-input w-full" placeholder="ID Nota">
                    <input type="number" id="diskon" class="lf-input w-full" value="0" placeholder="Diskon (Rp)">
                </div>
                <button type="button" onclick="cariStruk()" class="lf-btn-primary w-full py-3 mt-4 font-semibold text-sm">Buka & Cetak Kasir</button>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 space-y-8">
            <div class="lf-card p-0 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h4 class="font-headline text-base font-semibold text-gray-800">Peringatan Stok</h4>
                </div>
                <div class="p-6 max-h-[250px] overflow-y-auto space-y-4" id="listStokLow">
                    <p class="text-sm text-gray-500 text-center">Memuat info gudang...</p>
                </div>
            </div>

            <div class="lf-card bg-secondary text-white border-none p-6">
                <h4 class="font-headline text-base font-semibold mb-4 text-white">Workshop Monitor</h4>
                <div class="space-y-3" id="listPitAktif">
                    <p class="text-sm text-blue-100">Memuat antrean...</p>
                </div>
            </div>
        </div>
    </div>

    <div id="strukModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-[60] hidden">
        <div class="lf-card font-mono text-sm border-2 border-primary">
            <div class="text-center border-b border-gray-200 pb-4 mb-4">
                <h2 class="font-headline text-2xl font-bold text-primary">JEKI SPEED</h2>
                <p class="text-xs text-gray-500">Struk Pembayaran</p>
            </div>
            <div class="space-y-1 text-gray-600 mb-4">
                <p>Nota: <span id="sId" class="font-bold text-gray-800"></span></p>
                <p>Cust: <span id="sNama" class="font-bold text-gray-800"></span></p>
                <p>Mek : <span id="sMek"></span></p>
            </div>
            <div id="listRincianPart" class="space-y-2 border-b border-gray-200 pb-4 mb-4 text-xs"></div>
            <div class="flex justify-between items-center mb-6">
                <span class="font-bold text-gray-600 uppercase">Total</span>
                <span id="sTotal" class="text-lg font-bold text-primary"></span>
            </div>
            <div class="flex gap-4 font-body">
                <button type="button" onclick="window.print()" class="lf-btn-secondary w-full py-2">Cetak</button>
                <button type="button" onclick="closeModal('strukModal')" class="lf-btn-primary w-full py-2">Selesai</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const API = window.location.origin + "/api";

    function alertMsg(id, txt, ok = true) {
        const e = document.getElementById(id);
        if (!e) return;
        e.innerText = txt;
        e.className = `text-sm font-bold h-4 ${ok ? 'text-[#15803D]' : 'text-[#EF4444]'}`;
        setTimeout(() => e.innerText = "", 4000);
    }

    function openModal(id) {
        const overlay = document.getElementById('overlay');
        if (overlay) overlay.classList.remove('hidden');
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        const overlay = document.getElementById('overlay');
        if (overlay) overlay.classList.add('hidden');
        document.getElementById(id).classList.add('hidden');
    }

    async function loadStats() {
        try {
            const res = await fetch(`${API}/dashboard-stats`);
            if (!res.ok) return;
            const d = await res.json();

            document.getElementById('valNota').innerText = d.total_nota;
            document.getElementById('valPart').innerText = d.part_terpasang;
            document.getElementById('valPendapatan').innerText = "Rp " + d.total_pendapatan.toLocaleString('id-ID');
            document.getElementById('valStokLow').innerText = d.stok_menipis_count;

            document.getElementById('listStokLow').innerHTML = d.stok_menipis_items.map(i => `
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-gray-700">${i.nama_sparepart}</span>
                    <span class="px-3 py-1 bg-[#FEF2F2] text-[#EF4444] rounded-pill text-xs font-semibold">Sisa ${i.stok}</span>
                </div>
            `).join('') || '<p class="text-center text-gray-500 text-sm">Stok Gudang Aman</p>';

            const meks = { 1: "Agus (Pit 1)", 2: "Budi (Pit 2)", 3: "Anton (Pit 3)" };
            document.getElementById('listPitAktif').innerHTML = d.active_pits.map(p => `
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-3 h-3 rounded-circle bg-[#22C55E]"></div>
                    <p class="truncate text-white"><span class="font-semibold text-blue-100">${meks[p.id_mekanik] || 'Mekanik'}:</span> ${p.merk_kendaraan}</p>
                </div>
            `).join('') || '<p class="text-sm text-blue-100">Pit Kosong</p>';
        } catch (e) {
            console.error(e);
        }
    }

    async function loadJasa() {
        try {
            const res = await fetch(`${API}/master-jasa`);
            const d = await res.json();
            let o = '<option value="" disabled selected>Pilih Layanan...</option>';
            d.forEach(j => {
                const harga = j.biaya_standar || 0;
                const mek = j.id_mekanik_default || "";
                o += `<option value="${j.nama_layanan}" data-harga="${harga}" data-mek="${mek}">${j.nama_layanan}</option>`;
            });
            document.getElementById('selJasa').innerHTML = o;
        } catch (e) {
            console.error(e);
        }
    }

    function syncJasa() {
        const s = document.getElementById('selJasa').selectedOptions[0];
        if (s) {
            document.getElementById('biaya').value = (s.dataset.harga && s.dataset.harga !== "undefined") ? s.dataset.harga : 0;
            document.getElementById('idMek').value = (s.dataset.mek && s.dataset.mek !== "undefined") ? s.dataset.mek : "";
        }
    }

    async function prosesNota() {
        const p = {
            id_kendaraan: parseInt(document.getElementById('idKen').value),
            id_mekanik: parseInt(document.getElementById('idMek').value),
            jenis_servis: document.getElementById('selJasa').value,
            biaya_jasa: parseFloat(document.getElementById('biaya').value) || 0
        };
        const res = await fetch(`${API}/servis`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(p)
        });
        const out = await res.json();
        if (res.ok) {
            alertMsg('msgNota', `NOTA BERHASIL DIBUAT`);
            
            // --- UBAH DI SINI AGAR YANG MUNCUL NOMOR SIMPEL BUKAN KODE RANDOM MONGODB ---
            document.getElementById('notaId').value = out.no_nota; // Muncul angka simpel (Cth: 3)
            document.getElementById('cariId').value = out.no_nota; // Muncul angka simpel (Cth: 3)
            
            loadStats();
        } else {
            alertMsg('msgNota', 'Gagal memproses antrean', false);
        }
    }
    
    async function loadSpareparts() {
        try {
            const res = await fetch(`${API}/list-sparepart`);
            const d = await res.json();
            let o = '<option value="" disabled selected>Pilih Sparepart...</option>';
            d.forEach(p => o += `<option value="${p._id}">${p.nama_sparepart} (Sisa: ${p.stok})</option>`);
            document.getElementById('selPart').innerHTML = o;
        } catch (e) {
            console.error(e);
        }
    }

    async function tambahPart() {
        const selectValue = document.getElementById('selPart').value;
        if (!selectValue) {
            alertMsg('msgPart', 'Pilih part dahulu!', false);
            return;
        }
        const p = {
            id_sparepart: selectValue,
            jumlah: parseInt(document.getElementById('qty').value)
        };
        const res = await fetch(`${API}/servis/${document.getElementById('notaId').value}/sparepart`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(p)
        });
        if (res.ok) {
            alertMsg('msgPart', 'BERHASIL DIPASANG');
            loadStats();
            loadSpareparts();
        } else {
            alertMsg('msgPart', 'Gagal dipasang', false);
        }
    }

    async function cariStruk() {
        const id = document.getElementById('cariId').value;
        const diskon = parseFloat(document.getElementById('diskon').value) || 0;
        if (!id) return;
        
        const res = await fetch(`${API}/servis/${id}/tagihan`);
        if (!res.ok) {
            alert("Tidak ditemukan!");
            return;
        }
        
        const d = await res.json();
        const meks = { 1: "Agus (Pit 1)", 2: "Budi (Pit 2)", 3: "Anton (Pit 3)" };

        document.getElementById('sId').innerText = "#" + d.nota.no_nota;
        document.getElementById('sNama').innerText = d.kendaraan.nama_pelanggan || 'Umum';
        document.getElementById('sMek').innerText = meks[d.nota.id_mekanik] || 'Mekanik';

        let html = `<div class="flex justify-between font-semibold"><span>${d.nota.jenis_servis}</span><span>Rp ${d.nota.biaya_jasa.toLocaleString('id-ID')}</span></div>`;
        d.sparepart.forEach(p => {
            html += `<div class="flex justify-between text-gray-500 pl-2"><span>+ ${p.nama_sparepart} (x${p.jumlah})</span><span>Rp ${p.subtotal.toLocaleString('id-ID')}</span></div>`;
        });

        document.getElementById('listRincianPart').innerHTML = html;
        document.getElementById('sTotal').innerText = "Rp " + (d.grand_total - diskon).toLocaleString('id-ID');
        openModal('strukModal');
    }

    window.onload = () => {
        loadStats();
        loadJasa();
        loadSpareparts();
    };
    setInterval(loadStats, 5000);
</script>
@endpush