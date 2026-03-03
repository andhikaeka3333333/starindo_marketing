<x-app-layout>
    <div class="min-h-screen bg-[#f8fafc] py-8 text-slate-900 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
                <h2 class="text-2xl font-bold text-[#1e293b] uppercase tracking-tight">Monitoring Bensin</h2>
                <button onclick="openModal('add')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow-lg shadow-indigo-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Data
                </button>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-widest text-slate-500 border-b border-slate-200">
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 text-center">KM</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4 text-right">Nominal (IDR)</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($data as $item)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-6 py-3 text-[11px] font-medium text-slate-600 uppercase whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-3 text-center text-[11px] font-bold text-slate-900">
                                    {{ number_format($item->km, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-[11px] font-bold text-slate-800 uppercase">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-right text-[11px] font-bold text-slate-900 whitespace-nowrap">
                                    {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex justify-center items-center gap-3">
                                        <button onclick="openModal('edit', {{ $item }})" class="text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form action="{{ route('bensin.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="fuelModal" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-6">
        <div class="bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl relative border border-slate-100">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-900 uppercase mb-6 tracking-tight">Catat Bensin</h3>

            <form id="fuelForm" method="POST" class="space-y-4">
                @csrf
                <div id="methodField"></div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold uppercase text-slate-400 tracking-widest ml-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-bold focus:ring-2 focus:ring-indigo-600 outline-none" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-slate-400 tracking-widest ml-1">Kilometer (KM)</label>
                        <input type="number" name="km" id="km" class="mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-bold focus:ring-2 focus:ring-indigo-600 outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-400 tracking-widest ml-1">Nominal (IDR)</label>
                    <input type="text" id="nominal_display" onkeyup="formatCurrency(this)" class="mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-lg font-bold text-indigo-600 focus:ring-2 focus:ring-indigo-600 outline-none" placeholder="0" required>
                    <input type="hidden" name="nominal" id="nominal_raw">
                </div>

                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-400 tracking-widest ml-1">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-bold focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 rounded-xl font-bold text-xs uppercase text-slate-400 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-indigo-200 transition-all">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            input.value = rupiah;
            document.getElementById('nominal_raw').value = value.replace(/\./g, '');
        }

        function openModal(type, data = null) {
            const modal = document.getElementById('fuelModal');
            const form = document.getElementById('fuelForm');
            modal.classList.replace('hidden', 'flex');

            if (type === 'add') {
                form.action = "{{ route('bensin.store') }}";
                document.getElementById('modalTitle').innerText = "Tambah Data Bensin";
                document.getElementById('methodField').innerHTML = "";
                form.reset();
            } else {
                form.action = `/bensin/${data.id}`;
                document.getElementById('modalTitle').innerText = "Edit Data Bensin";
                document.getElementById('methodField').innerHTML = `@method('PUT')`;
                document.getElementById('tanggal').value = data.tanggal;
                document.getElementById('km').value = data.km;
                document.getElementById('keterangan').value = data.keterangan;
                document.getElementById('nominal_display').value = new Intl.NumberFormat('id-ID').format(data.nominal);
                document.getElementById('nominal_raw').value = data.nominal;
            }
        }

        function closeModal() {
            document.getElementById('fuelModal').classList.replace('flex', 'hidden');
        }
    </script>
</x-app-layout>
