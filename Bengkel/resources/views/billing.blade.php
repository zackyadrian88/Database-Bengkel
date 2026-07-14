@extends('layouts.app')

@section('content')
<div class="h-full w-full max-w-5xl mx-auto flex flex-col p-6">
    <!-- HEADER -->
    <div class="mb-6 flex justify-between items-center relative">
        <div>
            <h2 class="font-body text-[16px] font-bold text-textMain">Rincian Tagihan</h2>
            <p class="text-textSec mt-0.5 text-[11px] font-medium">Riwayat tagihan kassa operasional.</p>
        </div>
        
        <div>
            <span class="priority-chip success h-[24px] px-3 gap-1">
                <span class="material-symbols-outlined text-[14px]">cloud_done</span> Tersinkronisasi
            </span>
        </div>
    </div>

    <!-- CONTENT GRID -->
    <div class="flex-1 min-h-0 flex gap-6">
        <!-- LEFT COLUMN: TABLE -->
        <div class="flex-1 flex flex-col lf-card p-0 min-w-0">
            <div class="bg-bgPanel px-4 py-3 border-b border-borderPanel flex items-center gap-2 text-primary shrink-0">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                <h3 class="font-body text-[13px] font-bold text-textMain">Daftar Tagihan</h3>
            </div>
            <div class="overflow-y-auto flex-1 p-0">
                <table class="w-full text-left">
                    <thead class="bg-bgPanel sticky top-0 z-10 border-b border-borderPanel">
                        <tr class="text-[11px] text-textSec font-medium">
                            <th class="px-4 py-2 w-24">Nota</th>
                            <th class="px-4 py-2">Pelanggan</th>
                            <th class="px-4 py-2 w-28">Status</th>
                            <th class="px-4 py-2 text-right w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[12px] text-textMain" id="tableBillingLog">
                        <tr><td colspan="4" class="px-4 py-6 text-center text-textSec text-[11px]">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT COLUMN: DETAILS -->
        <div class="w-80 lf-card flex flex-col shrink-0 p-0 border-l border-borderPanel">
            <div class="px-5 py-4 border-b border-borderPanel flex items-center gap-2 text-primary font-bold">
                <span class="material-symbols-outlined text-[18px]">sticky_note_2</span> 
                <span class="text-[13px] text-textMain">Bukti Nota</span>
            </div>

            <div class="flex-1 overflow-y-auto p-5">
                <div id="notesPlaceholder" class="h-full flex flex-col items-center justify-center text-center text-textSec text-[11px]">
                    <span class="material-symbols-outlined text-[32px] mb-2 opacity-50">visibility_off</span>
                    Pilih ikon mata pada tabel untuk melihat rincian log.
                </div>

                <div id="notesContent" class="hidden space-y-4">
                    <div class="bg-bgHover p-3.5 rounded-lg border border-borderPanel">
                        <p class="text-[10px] font-medium text-textSec mb-1 uppercase tracking-wider">Pelanggan & Kendaraan</p>
                        <p class="font-bold text-[14px] text-textMain mb-0.5" id="noteCustomer">-</p>
                        <p class="text-[11px] text-primary font-mono" id="noteVehicle">-</p>
                    </div>
                    <div class="bg-bgHover p-3.5 rounded-lg border border-borderPanel">
                        <p class="text-[10px] font-medium text-textSec mb-1 uppercase tracking-wider">Layanan Utama</p>
                        <p class="font-bold text-[12px] text-textMain" id="noteJasa">-</p>
                    </div>
                    <div class="bg-bgHover p-3.5 rounded-lg border border-borderPanel">
                        <p class="text-[10px] font-medium text-textSec mb-2.5 uppercase tracking-wider">Suku Cadang</p>
                        <div class="space-y-2 text-[11px] text-textMain" id="notePartsList"></div>
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

    async function loadBillingRecords() {
        try {
            const res = await fetch(`${API}/dashboard-stats`); if(!res.ok) return;
            const d = await res.json();
            document.getElementById('tableBillingLog').innerHTML = d.recent_logs.map(l => {
                const shortId = l.id_servis.slice(-6).toUpperCase();
                return `
                <tr class="h-[36px] hover:bg-bgHover border-b border-borderPanel/50 group cursor-pointer" onclick="showDigitalNotes('${l.id_servis}')">
                    <td class="px-4 font-mono text-[11px] text-primary">#${shortId}</td>
                    <td class="px-4 font-medium text-textMain">${l.nama_pelanggan || 'Umum'}</td>
                    <td class="px-4"><span class="priority-chip success">Selesai</span></td>
                    <td class="px-4 text-right">
                        <button type="button" class="text-textSec hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                        </button>
                    </td>
                </tr>`;
            }).join('') || '<tr><td colspan="4" class="p-4 text-center text-[11px] text-textSec">Belum ada transaksi</td></tr>';
        } catch (e) {}
    }

    async function showDigitalNotes(id) {
        try {
            const res = await fetch(`${API}/servis/${id}/tagihan`); if(!res.ok) return;
            const d = await res.json();

            document.getElementById('notesPlaceholder').classList.add('hidden');
            document.getElementById('notesContent').classList.remove('hidden');

            document.getElementById('noteCustomer').innerText = d.kendaraan.nama_pelanggan || 'Umum';
            document.getElementById('noteVehicle').innerText = `${d.kendaraan.merk_kendaraan || '-'} (${d.kendaraan.nomor_polisi || '-'})`;
            document.getElementById('noteJasa').innerText = `${d.nota.jenis_servis} (Rp ${d.nota.biaya_jasa.toLocaleString('id-ID')})`;

            let partsHtml = "";
            if(d.sparepart && d.sparepart.length > 0) {
                d.sparepart.forEach(p => {
                    partsHtml += `<div class="flex justify-between border-b border-borderPanel pb-1.5 mb-1.5 last:border-0 last:pb-0 last:mb-0">
                        <span>${p.nama_sparepart} <span class="text-primary font-mono text-[10px] ml-1">x${p.jumlah}</span></span>
                        <span class="font-mono text-textMain">Rp ${p.subtotal.toLocaleString('id-ID')}</span>
                    </div>`;
                });
            } else {
                partsHtml = `<p class="text-[10px] text-textSec italic text-center py-2">Hanya Jasa Servis</p>`;
            }
            document.getElementById('notePartsList').innerHTML = partsHtml;
        } catch (e) {}
    }

    window.onload = loadBillingRecords;
    setInterval(loadBillingRecords, 5000);
</script>
@endpush
