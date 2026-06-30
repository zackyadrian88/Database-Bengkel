@extends('layouts.app')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="font-headline text-3xl font-bold text-gray-800">Rincian Tagihan</h2>
            <p class="text-gray-500 mt-1 text-sm">Riwayat tagihan kassa operasional.</p>
        </div>
        <span class="bg-[#BBF7D0] text-[#15803D] px-4 py-1.5 rounded-pill text-xs font-semibold">Tersinkronisasi</span>
    </div>

    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 lg:col-span-7">
            <div class="lf-card p-0 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-headline text-lg font-semibold text-gray-800">Daftar Tagihan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-200 text-sm text-gray-500">
                                <th class="px-6 py-4 font-medium">Nota</th>
                                <th class="px-6 py-4 font-medium">Pelanggan</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 text-center font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-800" id="tableBillingLog">
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-5">
            <div class="lf-card sticky top-28">
                <div class="flex items-center gap-2 text-primary font-headline text-xl font-semibold mb-6 border-b border-gray-100 pb-4">
                    <span class="material-symbols-outlined">sticky_note_2</span> Bukti Nota
                </div>

                <div id="notesPlaceholder" class="text-center py-10 text-gray-400 text-sm">
                    Pilih ikon mata pada tabel untuk melihat rincian log.
                </div>

                <div id="notesContent" class="hidden space-y-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Pelanggan & Kendaraan</p>
                        <p class="font-bold text-gray-800 text-lg" id="noteCustomer">-</p>
                        <p class="text-sm text-primary" id="noteVehicle">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Layanan Utama</p>
                        <div class="bg-[#F3F4F6] p-4 rounded-md border border-gray-200">
                            <p class="font-bold text-gray-800" id="noteJasa">-</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-2">Suku Cadang</p>
                        <div class="space-y-2 text-sm text-gray-700" id="notePartsList"></div>
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
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-semibold text-primary">#${shortId}</td>
                    <td class="px-6 py-4"><p class="font-semibold">${l.nama_pelanggan || 'Umum'}</p></td>
                    <td class="px-6 py-4"><span class="px-3 py-1 bg-[#BBF7D0] text-[#15803D] rounded-pill text-xs font-medium">Selesai</span></td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" onclick="showDigitalNotes('${l.id_servis}')" class="text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                </tr>`;
            }).join('') || '<tr><td colspan="4" class="p-6 text-center text-gray-500">Belum ada transaksi</td></tr>';
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
                    partsHtml += `<div class="flex justify-between border-b border-gray-100 pb-2"><span>${p.nama_sparepart} (x${p.jumlah})</span><span class="font-mono">Rp ${p.subtotal.toLocaleString('id-ID')}</span></div>`;
                });
            } else {
                partsHtml = `<p class="text-sm text-gray-400 italic">Hanya Jasa Servis</p>`;
            }
            document.getElementById('notePartsList').innerHTML = partsHtml;
        } catch (e) {}
    }

    window.onload = loadBillingRecords;
    setInterval(loadBillingRecords, 5000);
</script>
@endpush
