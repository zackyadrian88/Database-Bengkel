@extends('layouts.app')

@section('content')
<div class="flex h-full w-full gap-6 p-6">
    
    <!-- CENTER PANEL (MAIN CONTENT) -->
    <div class="flex-1 flex flex-col h-full">
        <!-- HEADER -->
        <header class="flex justify-between items-center mb-8 gap-6">
            <div class="font-headline font-bold text-primary text-xl tracking-wide shrink-0">JEKI SPEED</div>
            
            <div class="relative flex-1 max-w-md z-[60]">
                <input id="searchInput" oninput="doSearch(this.value)" class="peer w-full pl-6 pr-14 bg-bgPanel border border-borderPanel rounded-full py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 text-textMain placeholder-textSec" placeholder="Cari Plat Nomor / ID Nota..." type="text" autocomplete="off"/>
                <button class="absolute right-1.5 top-1.5 w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white shadow-md hover:bg-primaryHover transition">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </button>

                <!-- Search Dropdown (Dynamic) -->
                <div id="searchDropdown" class="absolute top-14 left-0 w-full bg-bgPanel border border-borderPanel rounded-2xl shadow-2xl py-2 flex-col gap-1 z-[60] hidden peer-focus:flex hover:flex">
                    <div id="searchResults" class="max-h-64 overflow-y-auto">
                        <div class="px-5 py-3 text-xs text-textSec text-center">Ketik untuk mencari...</div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 bg-bgPanel border border-borderPanel px-5 py-2.5 rounded-full text-sm font-medium text-textMain shrink-0">
                <span id="currentTime">09:30 AM</span> 
                <span class="text-textSec font-normal mx-1">|</span>
                <span id="currentDate" class="text-textSec">Friday, 7 Aug 2026</span>
            </div>
        </header>
        
        <!-- SUMMARY CARDS -->
        <section class="grid grid-cols-3 gap-4 mb-8">
            <!-- Card 1 -->
            <div class="lf-card !p-5 flex flex-col justify-center relative overflow-hidden group">
                <p class="text-[13px] text-textSec font-medium mb-1 group-hover:text-textMain transition-colors">Servis Selesai</p>
                <h3 class="text-3xl font-bold text-textMain mb-2" id="valNota">0</h3>
                <div class="flex items-center gap-1 text-[11px] font-semibold text-statusEmerald">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +0%
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="lf-card !p-5 flex flex-col justify-center relative overflow-hidden group">
                <p class="text-[13px] text-textSec font-medium mb-1 group-hover:text-textMain transition-colors">Rata-rata Waktu Servis</p>
                <div class="flex items-end gap-1 mb-2">
                    <h3 class="text-3xl font-bold text-textMain" id="valStokLow">0</h3>
                    <span class="text-sm font-semibold text-textMain mb-1">min</span>
                </div>
                <div class="flex items-center gap-1 text-[11px] font-semibold text-statusEmerald">
                    <span class="material-symbols-outlined text-[14px]">trending_down</span> 0%
                </div>
            </div>

            <!-- Card 3 -->
            <div class="lf-card !p-5 flex flex-col justify-center relative overflow-hidden group">
                <p class="text-[13px] text-textSec font-medium mb-1 group-hover:text-textMain transition-colors">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-bold text-statusEmerald mb-2 truncate" id="valPendapatan">Rp 0</h3>
                <div class="flex items-center gap-1 text-[11px] font-semibold text-statusEmerald">
                    <span class="material-symbols-outlined text-[14px]">account_balance_wallet</span> Total Omset
                </div>
            </div>
        </section>

        <!-- FLEX CONTAINER FOR MAIN & RIGHT PANEL -->
        <div class="flex flex-row gap-6 pb-6 w-full">
            <!-- MAIN FORMS -->
            <div class="flex-1 min-w-0">
                <div class="grid grid-cols-3 gap-6">
                    <!-- CARD 1: BUKA NOTA BARU -->
                    <div class="lf-card p-6 flex flex-col">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-primary bg-opacity-15 border border-primary border-opacity-20 text-primary flex items-center justify-center shrink-0" style="min-width: 48px; min-height: 48px;">
                            <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                        </div>
                        <div>
                            <h3 class="font-headline font-bold text-lg text-textMain">Buka Nota Baru</h3>
                            <p class="text-[11px] text-textSec">Buat SPK untuk pelanggan baru</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 flex-1">
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">ID Antrean / Kendaraan</label>
                            <input type="number" id="idKen" class="lf-input w-full" placeholder="Masukkan ID">
                        </div>
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">Pilih Layanan</label>
                            <select id="selJasa" onchange="syncJasa()" class="lf-input w-full [&>option]:bg-bgPanel"></select>
                            <input type="hidden" id="idMek">
                        </div>
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">Biaya Jasa (Rp)</label>
                            <input type="number" id="biaya" class="lf-input w-full opacity-70 cursor-not-allowed" readonly value="0">
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-borderPanel flex flex-col gap-2">
                        <button type="button" onclick="prosesNota()" class="lf-btn-primary w-full py-3 h-auto text-[14px] font-bold">Buat Nota</button>
                        <div class="h-4 text-center"><span id="msgNota" class="text-xs font-bold text-statusEmerald"></span></div>
                    </div>
                </div>

                <!-- CARD 2: INPUT SPAREPART -->
                <div class="lf-card p-6 flex flex-col">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#3182CE] bg-opacity-15 border border-[#3182CE] border-opacity-20 text-[#3182CE] flex items-center justify-center shrink-0" style="min-width: 48px; min-height: 48px;">
                            <span class="material-symbols-outlined text-[24px]">build</span>
                        </div>
                        <div>
                            <h3 class="font-headline font-bold text-lg text-textMain">Input Sparepart</h3>
                            <p class="text-[11px] text-textSec">Pasang suku cadang ke kendaraan</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 flex-1">
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">ID Nota Target</label>
                            <input type="text" id="notaId" oninput="validasiNotaRealtime(this.value, 'valPartInfo')" class="lf-input w-full" placeholder="ID Nota">
                            <div id="valPartInfo" class="hidden"></div>
                        </div>
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">Pilih Suku Cadang</label>
                            <select id="selPart" class="lf-input w-full [&>option]:bg-bgPanel">
                                <option value="" disabled selected>Memuat Part...</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">Jumlah Pemasangan</label>
                            <input type="number" id="qty" class="lf-input w-full" value="1" min="1">
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-borderPanel flex flex-col gap-2">
                        <button type="button" onclick="tambahPart()" class="lf-btn-primary !bg-[#3182CE] hover:!bg-[#2B6CB0] w-full py-3 h-auto text-[14px] font-bold">Pasang Sparepart</button>
                        <div class="h-4 text-center"><span id="msgPart" class="text-xs font-bold text-[#3182CE]"></span></div>
                    </div>
                </div>

                <!-- CARD 3: KASIR & TAGIHAN -->
                <div class="lf-card p-6 flex flex-col">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#48BB78] bg-opacity-15 border border-[#48BB78] border-opacity-20 text-[#48BB78] flex items-center justify-center shrink-0" style="min-width: 48px; min-height: 48px;">
                            <span class="material-symbols-outlined text-[24px]">point_of_sale</span>
                        </div>
                        <div>
                            <h3 class="font-headline font-bold text-lg text-textMain">Kasir & Tagihan</h3>
                            <p class="text-[11px] text-textSec">Proses pembayaran dan cetak struk</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 flex-1">
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">ID Nota</label>
                            <input type="text" id="tNota" oninput="validasiNotaRealtime(this.value, 'valKasirInfo')" class="lf-input w-full" placeholder="ID Nota untuk dicetak">
                            <div id="valKasirInfo" class="hidden"></div>
                        </div>
                        <div>
                            <label class="text-[11px] text-textSec mb-1.5 block uppercase tracking-wider font-semibold">Diskon Tambahan (Rp)</label>
                            <input type="number" id="diskon" class="lf-input w-full" value="0">
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-borderPanel flex flex-col gap-2">
                        <button type="button" onclick="cariStruk()" class="lf-btn-primary !bg-[#48BB78] hover:!bg-[#38A169] w-full py-3 h-auto text-[14px] font-bold">Buka & Cetak Kasir</button>
                        <div class="h-4"></div>
                    </div>
                </div>
                </div>
            </div>

            <!-- RIGHT PANEL: ANTREAN AKTIF -->
            <aside class="w-[280px] shrink-0 flex flex-col gap-6">
                <div class="lf-card p-6 flex flex-col min-h-[400px]">
                    <h3 class="font-headline font-bold text-lg text-textMain mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">queue_music</span>
                        Antrean Aktif
                        <div class="ml-auto flex items-center gap-1 text-[10px] font-semibold text-textSec bg-bgHover px-2 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-statusEmerald animate-pulse"></span>
                            Live
                        </div>
                    </h3>
                    <div class="flex-1 overflow-y-auto pr-1 flex flex-col gap-3 custom-scrollbar" id="queueListBody">
                        <div class="text-center text-textSec text-xs py-4">Memuat antrean...</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- STRUK MODAL -->
<div id="strukModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-[60] hidden">
    <div class="lf-card shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
        <div class="p-6 font-mono text-sm">
            <div class="text-center border-b border-dashed border-borderPanel pb-4 mb-4 mt-2">
                <h2 class="font-headline text-2xl font-bold text-textMain">JEKI SPEED</h2>
                <p class="text-xs text-textSec">Struk Pembayaran</p>
            </div>
            <div class="space-y-1 text-textSec mb-4 text-xs">
                <p>Nota: <span id="sId" class="font-bold text-textMain"></span></p>
                <p>Cust: <span id="sNama" class="font-bold text-textMain"></span></p>
                <p>Mek : <span id="sMek" class="text-textMain"></span></p>
            </div>
            <div id="listRincianPart" class="space-y-2 border-b border-dashed border-borderPanel pb-4 mb-4 text-xs text-textMain"></div>
            <div class="flex justify-between items-center mb-6">
                <span class="font-bold text-textSec uppercase">Total</span>
                <span id="sTotal" class="text-lg font-bold text-primary"></span>
            </div>
            <div class="flex gap-4 font-body">
                <button type="button" onclick="window.print()" class="lf-btn-secondary w-full py-2.5">Cetak</button>
                <button type="button" onclick="tandaiSelesai()" class="lf-btn-primary w-full py-2.5">Selesai</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const API = window.location.origin + "/api";

    // Set Current Time and Date
    function updateDateTime() {
        const now = new Date();
        const timeEl = document.getElementById('currentTime');
        const dateEl = document.getElementById('currentDate');
        if(timeEl) timeEl.innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        if(dateEl) dateEl.innerText = now.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    function alertMsg(id, txt, ok = true) {
        const e = document.getElementById(id);
        if (!e) return;
        
        e.innerText = txt;
        if (id === 'msgNota') {
            e.className = `text-xs font-bold ${ok ? 'text-statusEmerald' : 'text-statusRed'}`;
        } else if (id === 'msgPart') {
            e.className = `text-xs font-bold ${ok ? 'text-statusEmerald' : 'text-statusRed'}`;
        } else {
            e.className = `text-xs font-bold ${ok ? 'text-statusEmerald' : 'text-statusRed'}`;
        }
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

            if (document.getElementById('valNota')) document.getElementById('valNota').innerText = d.total_nota;
            if (document.getElementById('valStokLow')) document.getElementById('valStokLow').innerText = d.rata_waktu_servis || 0;
            if (document.getElementById('valPendapatan')) document.getElementById('valPendapatan').innerText = "Rp " + parseInt(d.total_pendapatan).toLocaleString(); 
        } catch (e) {
            console.error(e);
        }
    }

    async function loadJasa() {
        try {
            const res = await fetch(`${API}/master-jasa`);
            const d = await res.json();
            let o = '<option value="" disabled selected>Pilih Layanan...</option>';
            const arr = Array.isArray(d) ? d : Object.values(d);
            arr.forEach(j => {
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
            alertMsg('msgNota', `BERHASIL!`);
            document.getElementById('notaId').value = out.no_nota;
            document.getElementById('tNota').value = out.no_nota;
            loadStats();
        } else {
            alertMsg('msgNota', 'Gagal!', false);
        }
    }
    
    async function loadSpareparts() {
        try {
            const res = await fetch(`${API}/list-sparepart`);
            const d = await res.json();
            let o = '<option value="" disabled selected>Pilih Part...</option>';
            const arr = Array.isArray(d) ? d : Object.values(d);
            arr.forEach(p => o += `<option value="${p._id}">${p.nama_sparepart} (Sisa: ${p.stok})</option>`);
            document.getElementById('selPart').innerHTML = o;
        } catch (e) {
            console.error(e);
        }
    }

    async function tambahPart() {
        const selectValue = document.getElementById('selPart').value;
        if (!selectValue) {
            alertMsg('msgPart', 'Pilih part!', false);
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
            alertMsg('msgPart', 'DIPASANG!');
            loadStats();
            loadSpareparts();
        } else {
            alertMsg('msgPart', 'Gagal!', false);
        }
    }

    let currentStrukId = null;
    async function cariStruk() {
        const id = document.getElementById('tNota').value;
        const diskon = parseFloat(document.getElementById('diskon').value) || 0;
        if (!id) return;
        
        try {
            const res = await fetch(`${API}/servis/${id}/tagihan`);
            if (!res.ok) {
                alertMsg('msgKasir', 'Nota tidak ditemukan!', false);
                return;
            }
            
            const d = await res.json();
            currentStrukId = d.nota.no_nota;
            const meks = { 1: "Agus (Pit 1)", 2: "Budi (Pit 2)", 3: "Anton (Pit 3)" };

            document.getElementById('sId').innerText = "SPK-" + d.nota.no_nota;
            document.getElementById('sNama').innerText = d.kendaraan.nama_pelanggan || 'Umum';
            document.getElementById('sMek').innerText = meks[d.nota.id_mekanik] || 'Mekanik';

            let html = `<div class="flex justify-between font-bold text-textMain"><span>${d.nota.jenis_servis}</span><span>Rp ${d.nota.biaya_jasa.toLocaleString('id-ID')}</span></div>`;
            d.sparepart.forEach(p => {
                html += `<div class="flex justify-between text-textSec pl-2 mt-1"><span>+ ${p.nama_sparepart} (x${p.jumlah})</span><span>Rp ${p.subtotal.toLocaleString('id-ID')}</span></div>`;
            });

            document.getElementById('listRincianPart').innerHTML = html;
            document.getElementById('sTotal').innerText = "Rp " + (d.grand_total - diskon).toLocaleString('id-ID');
            openModal('strukModal');
        } catch(e) {
            console.error(e);
        }
    }

    async function tandaiSelesai() {
        if (!currentStrukId) {
            closeModal('strukModal');
            return;
        }
        
        try {
            const res = await fetch(`${API}/servis/${currentStrukId}/selesai`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });
            
            if (res.ok) {
                closeModal('strukModal');
                loadStats();
                loadAntreanAktif();
                document.getElementById('tNota').value = '';
                alertMsg('msgKasir', 'Servis Selesai!');
            }
        } catch (e) {
            console.error(e);
            closeModal('strukModal');
        }
    }

    let searchTimeout;
    async function doSearch(q) {
        const resultsDiv = document.getElementById('searchResults');
        if (!q || q.length < 2) {
            resultsDiv.innerHTML = '<div class="px-5 py-3 text-xs text-textSec text-center">Ketik minimal 2 karakter...</div>';
            return;
        }
        
        resultsDiv.innerHTML = '<div class="px-5 py-3 text-xs text-textSec text-center">Mencari...</div>';
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`${API}/cari-riwayat?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                
                if (!data || data.length === 0) {
                    resultsDiv.innerHTML = '<div class="px-5 py-3 text-xs text-textSec text-center">Tidak ditemukan.</div>';
                    return;
                }
                
                let html = '';
                data.forEach(item => {
                    const statusColor = item.status === 'Selesai' ? 'text-statusEmerald' : 'text-statusGold';
                    html += `
                    <div class="px-5 py-3 hover:bg-bgHover cursor-pointer flex items-center justify-between group transition-colors border-b border-borderPanel last:border-0" onclick="document.getElementById('searchInput').value = '${item.no_nota}'; document.getElementById('tNota').value = '${item.no_nota}'; document.getElementById('notaId').value = '${item.no_nota}';">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-textMain group-hover:text-primary transition-colors">SPK-${item.no_nota} <span class="text-textSec font-normal mx-1">|</span> ${item.plat_nomor}</p>
                                <p class="text-[11px] text-textSec mt-0.5">${item.jenis_servis} • <span class="${statusColor}">${item.status} (${item.tanggal})</span></p>
                            </div>
                        </div>
                    </div>`;
                });
                resultsDiv.innerHTML = html;
            } catch (e) {
                console.error(e);
                resultsDiv.innerHTML = '<div class="px-5 py-3 text-xs text-textRed text-center">Terjadi kesalahan.</div>';
            }
        }, 500);
    }

    let valTimeout;
    async function validasiNotaRealtime(id, targetElementId) {
        const el = document.getElementById(targetElementId);
        if (!id) {
            el.innerHTML = '';
            el.classList.add('hidden');
            return;
        }
        
        el.classList.remove('hidden');
        el.innerHTML = `<div class="p-3 mt-2 bg-bgPanel border border-borderPanel rounded-lg flex items-center gap-3 animate-pulse">
            <span class="material-symbols-outlined text-textSec text-lg">hourglass_empty</span>
            <span class="text-xs text-textSec font-medium">Memvalidasi ID...</span>
        </div>`;
        
        clearTimeout(valTimeout);
        valTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`${API}/validasi-nota/${id}`);
                if (res.ok) {
                    const data = await res.json();
                    el.innerHTML = `
                    <div class="p-3 mt-2 bg-statusEmerald/10 border border-statusEmerald/20 rounded-lg flex flex-col gap-1">
                        <div class="flex items-center gap-2 text-statusEmerald">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span class="text-[11px] font-bold uppercase tracking-wider">Nota Valid</span>
                        </div>
                        <p class="text-[13px] font-semibold text-textMain mt-1">${data.nama_pelanggan}</p>
                        <p class="text-[11px] text-textSec font-mono">${data.plat_nomor}</p>
                    </div>`;
                } else {
                    el.innerHTML = `
                    <div class="p-3 mt-2 bg-statusRed/10 border border-statusRed/20 rounded-lg flex items-center gap-2 text-statusRed">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span class="text-xs font-semibold">ID Nota tidak ditemukan</span>
                    </div>`;
                }
            } catch (e) {
                el.innerHTML = `
                <div class="p-3 mt-2 bg-statusGold/10 border border-statusGold/20 rounded-lg flex items-center gap-2 text-statusGold">
                    <span class="material-symbols-outlined text-[16px]">warning</span>
                    <span class="text-xs font-semibold">Gagal koneksi ke server</span>
                </div>`;
            }
        }, 600);
    }

    async function loadAntreanAktif() {
        const tbody = document.getElementById('queueListBody');
        try {
            const res = await fetch(`${API}/antrean-aktif`);
            if (res.ok) {
                const data = await res.json();
                const arr = Array.isArray(data) ? data : Object.values(data);
                if (arr.length === 0) {
                    tbody.innerHTML = '<div class="text-center text-textSec text-xs py-4">Belum ada antrean aktif hari ini.</div>';
                    return;
                }
                
                let html = '';
                arr.forEach(item => {
                    const statusColor = item.status === 'Selesai' ? 'text-statusEmerald bg-statusEmerald/10' : 'text-statusGold bg-statusGold/10';
                    html += `
                    <div class="border border-borderPanel/50 rounded-lg p-3 hover:border-primary/50 transition-colors cursor-pointer group bg-[#16161A]">
                        <div class="flex justify-between items-start mb-2">
                            <span class="inline-flex items-center justify-center bg-primary/20 text-primary text-[10px] font-bold px-2 py-0.5 rounded">SPK-${item.no_nota}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold ${statusColor}">${item.status}</span>
                        </div>
                        <p class="font-semibold text-sm text-textMain group-hover:text-primary transition-colors">${item.nama_pelanggan}</p>
                        <p class="text-xs text-textSec mb-1">${item.plat_nomor}</p>
                        <p class="text-[11px] text-textSec border-t border-borderPanel/30 pt-1 mt-1">${item.jenis_servis}</p>
                    </div>`;
                });
                tbody.innerHTML = html;
            }
        } catch (e) {
            console.error(e);
            tbody.innerHTML = '<div class="text-center text-statusRed text-xs py-4">Gagal memuat antrean.</div>';
        }
    }

    window.onload = () => {
        loadStats();
        loadJasa();
        loadSpareparts();
        loadAntreanAktif();
        setInterval(loadStats, 5000);
        setInterval(loadAntreanAktif, 10000);
    };
</script>