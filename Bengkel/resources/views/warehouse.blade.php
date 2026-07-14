@extends('layouts.app')

@section('content')
<div class="h-full w-full max-w-5xl mx-auto flex flex-col p-6">
    <!-- HEADER -->
    <div class="mb-6 flex justify-between items-center relative">
        <div>
            <h2 class="font-body text-[16px] font-bold text-textMain">Stok Gudang</h2>
            <p class="text-textSec mt-0.5 text-[11px] font-medium">Manajemen inventaris suku cadang.</p>
        </div>
        
        <div>
            <button type="button" onclick="openModal('addPartModal')" class="lf-btn-primary px-4 gap-2">
                <span class="material-symbols-outlined text-[16px]">add</span> Tambah Part
            </button>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="lf-card p-0 flex-1 flex flex-col min-h-0">
        <div class="bg-bgPanel px-4 py-3 border-b border-borderPanel flex items-center gap-2 text-primary shrink-0">
            <span class="material-symbols-outlined text-[18px]">inventory_2</span>
            <h3 class="font-body text-[13px] font-bold text-textMain">Katalog Inventaris</h3>
        </div>
        <div class="overflow-y-auto flex-1 p-0">
            <table class="w-full text-left">
                <thead class="bg-bgPanel sticky top-0 z-10 border-b border-borderPanel">
                    <tr class="text-[11px] text-textSec font-medium">
                        <th class="px-4 py-2 w-24">ID Part</th>
                        <th class="px-4 py-2">Nama Sparepart</th>
                        <th class="px-4 py-2">Harga Retail</th>
                        <th class="px-4 py-2 w-28">Stok</th>
                        <th class="px-4 py-2 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[12px] text-textMain" id="tableGudang">
                    <tr><td colspan="5" class="px-4 py-6 text-center text-textSec text-[11px]">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL ADD PART -->
    <div id="addPartModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-[60] hidden">
        <div class="lf-card">
            <div class="lf-card-body">
                <h3 class="font-body text-[14px] font-bold mb-4 text-textMain">Input Part Baru</h3>
                <div class="space-y-3">
                    <div id="msgAddPart" class="text-[11px] font-mono text-statusEmerald h-3"></div>
                    <input type="text" id="nNamaPart" class="lf-input w-full" placeholder="Nama Sparepart Lengkap">
                    <input type="number" id="nHarga" class="lf-input w-full font-mono" placeholder="Harga Jual (Rp)">
                    <input type="number" id="nStok" class="lf-input w-full font-mono" placeholder="Stok Awal" value="10">
                    
                    <div class="pt-2 space-y-2">
                        <button type="button" onclick="simpanPartBaru()" class="lf-btn-primary w-full">Simpan ke Database</button>
                        <button type="button" onclick="closeModal('addPartModal')" class="lf-btn-secondary w-full">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL RESTOCK -->
    <div id="restockQuickModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-[60] hidden">
        <div class="lf-card">
            <div class="lf-card-body">
                <h3 class="font-body text-[14px] font-bold mb-1 text-textMain">Restock Suku Cadang</h3>
                <p class="text-[11px] text-primary font-medium mb-4" id="labelNamaPart">-</p>
                <div class="space-y-3">
                    <div id="msgRestockQuick" class="text-[11px] font-mono text-statusEmerald h-3"></div>
                    <input type="hidden" id="qIdPart">
                    
                    <div>
                        <label class="text-[10px] font-medium text-textSec mb-1 block tracking-wide">Jumlah Tambahan</label>
                        <input type="number" id="qTambahStok" class="lf-input w-full text-center text-[16px] font-mono font-bold text-textMain h-10" value="10">
                    </div>
                    
                    <div class="pt-2 space-y-2">
                        <button type="button" onclick="prosesRestockCepat()" class="lf-btn-primary w-full">Update Stok</button>
                        <button type="button" onclick="closeModal('restockQuickModal')" class="lf-btn-secondary w-full">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const API = window.location.origin + "/api";

    function alertMsg(id, txt, ok=true) {
        const e = document.getElementById(id); if(!e) return;
        e.innerText = txt; e.className = `text-[11px] font-mono h-3 ${ok ? 'text-statusEmerald' : 'text-statusRed'}`;
        setTimeout(() => e.innerText = "", 3000);
    }
    function openModal(id) { document.getElementById('overlay').classList.remove('hidden'); document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById('overlay').classList.add('hidden'); document.getElementById(id).classList.add('hidden'); }

    async function loadGudang() {
        try {
            const res = await fetch(`${API}/data-gudang`); const data = await res.json();
            document.getElementById('tableGudang').innerHTML = data.map(p => {
                let badgeClass = p.stok > 10 ? 'success' : (p.stok > 0 ? 'medium' : 'urgent');
                const shortId = p._id.slice(-6).toUpperCase();
                return `
                <tr class="h-[36px] hover:bg-bgHover border-b border-borderPanel/50 group">
                    <td class="px-4 font-mono text-[11px] text-primary">#${shortId}</td>
                    <td class="px-4 text-textMain">${p.nama_sparepart}</td>
                    <td class="px-4 font-mono text-textMain">Rp ${parseInt(p.harga).toLocaleString('id-ID')}</td>
                    <td class="px-4"><span class="priority-chip ${badgeClass}">${p.stok} Unit</span></td>
                    <td class="px-4 text-right">
                        <button type="button" onclick="bukaRestockCepat('${p._id}', '${p.nama_sparepart}')" class="lf-btn-secondary h-[26px] px-2 text-[10px] w-auto inline-flex items-center gap-1 text-textSec hover:text-textMain">
                            <span class="material-symbols-outlined text-[14px]">add</span> Stok
                        </button>
                    </td>
                </tr>`;
            }).join('');
        } catch (e) {}
    }

    async function simpanPartBaru() {
        const p = { nama_sparepart: document.getElementById('nNamaPart').value, harga: parseFloat(document.getElementById('nHarga').value), stok: parseInt(document.getElementById('nStok').value), jenis_sparepart: "Umum" };
        const res = await fetch(`${API}/tambah-barang`, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(p) });
        if(res.ok) { alertMsg('msgAddPart', 'Tersimpan!'); loadGudang(); setTimeout(() => { closeModal('addPartModal'); document.getElementById('nNamaPart').value=""; document.getElementById('nHarga').value=""; }, 1000); }
    }

    function bukaRestockCepat(id, nama) {
        document.getElementById('qIdPart').value = id; document.getElementById('labelNamaPart').innerText = nama; document.getElementById('qTambahStok').value = 10;
        openModal('restockQuickModal');
    }

    async function prosesRestockCepat() {
        const p = { id_sparepart: document.getElementById('qIdPart').value, tambah_stok: parseInt(document.getElementById('qTambahStok').value) };
        const res = await fetch(`${API}/restock-barang`, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(p) });
        if(res.ok) { alertMsg('msgRestockQuick', 'Stok ditambah!'); loadGudang(); setTimeout(() => closeModal('restockQuickModal'), 1000); }
    }

    window.onload = loadGudang;
</script>
@endpush
