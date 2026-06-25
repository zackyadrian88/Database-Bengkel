@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="font-headline text-3xl font-bold text-gray-800">Stok Gudang</h2>
            <p class="text-gray-500 mt-1 text-sm"></p>
        </div>
        <button type="button" onclick="openModal('addPartModal')" class="lf-btn-primary px-6 py-3 font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Tambah Part Baru
        </button>
    </div>

    <div class="lf-card p-0 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="font-headline text-lg font-semibold text-gray-800">Katalog Inventaris
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 text-sm text-gray-500">
                        <th class="px-6 py-4 font-medium text-center">ID</th>
                        <th class="px-6 py-4 font-medium">Nama Sparepart</th>
                        <th class="px-6 py-4 font-medium">Harga Retail</th>
                        <th class="px-6 py-4 font-medium">Stok</th>
                        <th class="px-6 py-4 font-medium text-center">Restock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-800" id="tableGudang">
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL ADD PART -->
    <div id="addPartModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-[60] hidden">
        <div class="lf-card border-t-4 border-primary">
            <h3 class="font-headline text-xl font-semibold mb-4">Input Part Baru</h3>
            <div class="space-y-4">
                <div id="msgAddPart" class="text-sm font-bold text-[#15803D] h-4"></div>
                <input type="text" id="nNamaPart" class="lf-input w-full" placeholder="Nama Sparepart Lengkap">
                <input type="number" id="nHarga" class="lf-input w-full" placeholder="Harga Jual (Rp)">
                <input type="number" id="nStok" class="lf-input w-full" placeholder="Stok Awal" value="10">
                <button type="button" onclick="simpanPartBaru()" class="lf-btn-primary w-full py-3 mt-2 font-semibold">Simpan ke Database</button>
                <button type="button" onclick="closeModal('addPartModal')" class="lf-btn-secondary w-full py-2 mt-2">Batal</button>
            </div>
        </div>
    </div>

    <!-- MODAL RESTOCK -->
    <div id="restockQuickModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm z-[60] hidden">
        <div class="lf-card border-t-4 border-secondary">
            <h3 class="font-headline text-xl font-semibold mb-2">Restock Suku Cadang</h3>
            <p class="text-sm text-primary font-bold mb-4" id="labelNamaPart">-</p>
            <div class="space-y-4">
                <div id="msgRestockQuick" class="text-sm font-bold text-[#15803D] h-4"></div>
                <input type="hidden" id="qIdPart">
                <input type="number" id="qTambahStok" class="lf-input w-full text-center text-xl font-bold" value="10">
                <button type="button" onclick="prosesRestockCepat()" class="lf-btn-secondary w-full py-3 mt-2 font-semibold bg-secondary text-white border-none hover:bg-blue-600">Update Stok</button>
                <button type="button" onclick="closeModal('restockQuickModal')" class="w-full py-2 mt-2 text-gray-500 hover:text-gray-800 text-center block">Batal</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const API = window.location.origin + "/api";

    function alertMsg(id, txt, ok=true) {
        const e = document.getElementById(id); if(!e) return;
        e.innerText = txt; e.className = `text-sm font-bold h-4 ${ok?'text-[#15803D]':'text-[#EF4444]'}`;
        setTimeout(() => e.innerText = "", 3000);
    }
    function openModal(id) { document.getElementById('overlay').classList.remove('hidden'); document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById('overlay').classList.add('hidden'); document.getElementById(id).classList.add('hidden'); }

    async function loadGudang() {
        try {
            const res = await fetch(`${API}/data-gudang`); const data = await res.json();
            document.getElementById('tableGudang').innerHTML = data.map(p => {
                let badge = p.stok > 10 ? 'bg-[#BBF7D0] text-[#15803D]' : (p.stok > 0 ? 'bg-[#FED7AA] text-[#C2410C]' : 'bg-[#FEF2F2] text-[#EF4444]');
                return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-gray-500 text-center">#${p.id_sparepart}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">${p.nama_sparepart}</td>
                    <td class="px-6 py-4 font-mono text-gray-700">Rp ${parseInt(p.harga).toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-pill text-xs font-semibold ${badge}">${p.stok} Unit</span></td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" onclick="bukaRestockCepat(${p.id_sparepart}, '${p.nama_sparepart}')" class="text-primary hover:text-primaryHover transition-colors">
                            <span class="material-symbols-outlined">add_circle</span>
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
        const p = { id_sparepart: parseInt(document.getElementById('qIdPart').value), tambah_stok: parseInt(document.getElementById('qTambahStok').value) };
        const res = await fetch(`${API}/restock-barang`, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(p) });
        if(res.ok) { alertMsg('msgRestockQuick', 'Stok ditambah!'); loadGudang(); setTimeout(() => closeModal('restockQuickModal'), 1000); }
    }

    window.onload = loadGudang;
</script>
@endpush
