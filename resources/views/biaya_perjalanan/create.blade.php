<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen"
         x-data="{
            tab: 'akom',
            kategori: 'Hotel',
            marketingId: '',
            marketings: {{ $marketings->toJson() }},
            rates: {{ $rates->toJson() }},
            loading: false,
            finalizing: false,
            displayNominal: '',
            rawNominal: '',
            wilayah: 'Jabotabek',
            durasi: 1,

            get selectedMarketing() {
                return this.marketings.find(m => m.id == this.marketingId) || { level: '', no_kartu_tol: '' }
            },

            get calculatedAkomodasi() {
                if (!['Hotel', 'UM'].includes(this.kategori) || !this.selectedMarketing.level) return 0;
                let rateFound = this.rates.find(r => r.kategori === this.kategori && r.level == this.selectedMarketing.level && r.wilayah === this.wilayah);
                return rateFound ? rateFound.nominal * this.durasi : 0;
            },

            updateManualNominal(e) {
                let val = e.target.value.replace(/\D/g, '');
                this.rawNominal = val;
                this.displayNominal = val ? new Intl.NumberFormat('id-ID').format(val) : '';
            },

            formatRupiah(val) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
            }
         }">

        <div class="max-w-3xl mx-auto px-4 mb-10">
            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border-t-8 border-blue-600">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter italic">Tambah Biaya Perjalanan</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Starindo Digital System</p>
                    </div>
                    <a href="{{ route('biaya-perjalanan.index') }}" class="text-slate-400 hover:text-slate-800 font-bold text-[10px] uppercase transition">Back to List</a>
                </div>

                <form action="{{ route('biaya-perjalanan.storeTemp') }}" method="POST" class="px-8 py-6 space-y-5" @submit="loading = true">
                    @csrf
                    <input type="hidden" name="level" :value="selectedMarketing.level">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Marketing Pemohon</label>
                            <select name="marketing_id" x-model="marketingId" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600" required>
                                <option value="">Pilih Nama Marketing</option>
                                @foreach ($marketings as $m)
                                    <option value="{{ $m->id }}">{{ strtoupper($m->nama) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nama Customer</label>
                            <input type="text" name="customer_nama" placeholder="Nama Customer..." class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Kontak / CP</label>
                            <input type="text" name="customer_cp" placeholder="WA / Telp" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Pilih Kategori Biaya</label>
                        <select name="kategori" x-model="kategori" class="w-full bg-slate-50 border-none rounded-xl p-3 font-black text-blue-600 uppercase text-xs shadow-inner focus:ring-2 focus:ring-blue-600 appearance-none">
                            <optgroup label="AKOMODASI (RATE SISTEM)">
                                <option value="Hotel">Hotel</option>
                                <option value="UM">Uang Makan (UM)</option>
                            </optgroup>
                            <optgroup label="TOL (INPUT MANUAL)">
                                <option value="Top-Up Tol">Top-Up Tol</option>
                                <option value="Pemakaian Tol">Pemakaian Tol</option>
                            </optgroup>
                            <optgroup label="KENDARAAN (INPUT MANUAL)">
                                <option value="Bensin">Bensin</option>
                            </optgroup>
                            <optgroup label="OPERASIONAL (INPUT MANUAL)">
                                <option value="Parkir">Parkir</option>
                                <option value="Cuci Kendaraan">Cuci</option>
                                <option value="Oleh-oleh">Oleh-oleh</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </optgroup>
                        </select>
                    </div>

                    <div x-show="['Hotel', 'UM'].includes(kategori)" x-transition class="grid grid-cols-2 gap-4 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase italic ml-1">Wilayah Tujuan</label>
                            <select name="wilayah" x-model="wilayah" class="w-full bg-white border-none rounded-lg p-2 text-xs font-bold shadow-sm focus:ring-2 focus:ring-blue-600">
                                <option value="Jabotabek">Jabotabek & Luar Pulau</option>
                                <option value="Lainnya">Lainnya (Jawa)</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase italic ml-1" x-text="kategori === 'Hotel' ? 'Durasi (Malam)' : 'Durasi (Hari)'"></label>
                            <input type="number" name="durasi" x-model="durasi" min="1" class="w-full bg-white border-none rounded-lg p-2 text-xs font-bold shadow-sm focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>

                    <div x-show="['Top-Up Tol', 'Pemakaian Tol'].includes(kategori)" x-transition class="p-4 bg-amber-50 rounded-2xl border-2 border-dashed border-amber-200 flex justify-between items-center">
                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest italic">Nomor Kartu Tol:</span>
                        <span class="text-sm font-black text-amber-800 italic" x-text="selectedMarketing.no_kartu_tol || 'KARTU BELUM DI-SET'"></span>
                    </div>

                    <div x-show="kategori === 'Bensin'" x-transition class="space-y-1">
                        <label class="text-[9px] font-black text-blue-600 uppercase italic ml-1 tracking-widest">Posisi KM Kendaraan</label>
                        <input type="number" name="km" placeholder="Masukkan Angka KM..." class="w-full bg-blue-50 border-none rounded-xl p-3 font-black text-blue-700 shadow-inner focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div x-show="!['Hotel', 'UM'].includes(kategori)" x-transition class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase tracking-widest ml-1 italic">Input Nominal Rupiah</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-blue-300 text-sm">Rp</span>
                                <input type="text" x-model="displayNominal" @input="updateManualNominal($event)" placeholder="0" class="w-full bg-blue-50 border-none rounded-xl p-4 pl-12 font-black text-xl text-blue-700 shadow-inner focus:ring-2 focus:ring-blue-600">
                                <input type="hidden" name="nominal_value" :value="rawNominal">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="1" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600" placeholder="Catatan atau rincian biaya..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 rounded-2xl bg-indigo-600 flex justify-between items-center shadow-lg">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-indigo-200 uppercase tracking-widest">Estimasi Nominal</span>
                            <span class="text-[9px] font-black text-white bg-indigo-500 px-2 py-0.5 rounded mt-0.5 shadow-sm" x-text="'Lvl: ' + (selectedMarketing.level || '-')"></span>
                        </div>
                        <span class="text-2xl font-black text-white italic tracking-tighter" x-text="['Hotel', 'UM'].includes(kategori) ? formatRupiah(calculatedAkomodasi) : 'Rp ' + (displayNominal || '0')"></span>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-lg hover:bg-blue-700 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="loading ? 'MENYIMPAN DRAF...' : '+ TAMBAH KE DAFTAR DRAF'"></span>
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-[1400px] mx-auto px-4 pb-20">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
                <div class="flex items-center bg-slate-100 border-b border-slate-200 pt-2 px-6 gap-1 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button @click="tab = 'akom'" :class="tab === 'akom' ? 'bg-white border-x border-t border-slate-200 text-blue-600 font-black rounded-t-xl -mb-[1px]' : 'text-slate-400 font-bold hover:text-slate-600'" class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all">Hotel & UM ({{ $tempAkomodasi->count() }})</button>
                    <button @click="tab = 'tol'" :class="tab === 'tol' ? 'bg-white border-x border-t border-slate-200 text-amber-600 font-black rounded-t-xl -mb-[1px]' : 'text-slate-400 font-bold hover:text-slate-600'" class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all">Tol ({{ $tempTol->count() }})</button>
                    <button @click="tab = 'bensin'" :class="tab === 'bensin' ? 'bg-white border-x border-t border-slate-200 text-emerald-600 font-black rounded-t-xl -mb-[1px]' : 'text-slate-400 font-bold hover:text-slate-600'" class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all">Bensin ({{ $tempBensin->count() }})</button>
                    <button @click="tab = 'oper'" :class="tab === 'oper' ? 'bg-white border-x border-t border-slate-200 text-indigo-600 font-black rounded-t-xl -mb-[1px]' : 'text-slate-400 font-bold hover:text-slate-600'" class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all">Lainnya ({{ $tempOperasional->count() }})</button>
                </div>

                <div class="p-6">
                    <div x-show="tab === 'akom'">
                        <table class="w-full text-left text-[10px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th><th class="py-2 px-3">Marketing</th><th class="py-2 px-3">Customer</th><th class="py-2 px-3">Kontak</th><th class="py-2 px-3">Kategori</th><th class="py-2 px-3 text-center">Wilayah</th><th class="py-2 px-3 text-center">Durasi</th><th class="py-2 px-3 text-right">Total</th><th class="py-2 px-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($tempAkomodasi as $t)
                                    <tr class="hover:bg-blue-50/50 transition">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">{{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase italic">{{ $t->marketing->nama }}</td>
                                        <td class="py-2.5 px-3 font-black text-blue-600 uppercase">{{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3 font-bold text-slate-400 italic">{{ $t->customer_cp ?? '-' }}</td>
                                        <td class="py-2.5 px-3 font-bold italic text-slate-700 uppercase">{{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 text-center font-medium text-slate-500 uppercase">{{ $t->wilayah }}</td>
                                        <td class="py-2.5 px-3 text-center font-bold">{{ $t->durasi }} Hari</td>
                                        <td class="py-2.5 px-3 text-right font-black italic text-slate-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right">
                                            <form action="{{ route('biaya-perjalanan.destroyTemp', ['akomodasi', $t->id]) }}" method="POST">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'tol'" x-cloak>
                        <table class="w-full text-left text-[10px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th><th class="py-2 px-3">Marketing</th><th class="py-2 px-3 text-amber-600">Kartu Tol</th><th class="py-2 px-3">Customer</th><th class="py-2 px-3">Kontak</th><th class="py-2 px-3">Kategori</th><th class="py-2 px-3">Keterangan</th><th class="py-2 px-3 text-right">Nominal</th><th class="py-2 px-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($tempTol as $t)
                                    <tr class="hover:bg-amber-50/50 transition">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">{{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase italic">{{ $t->marketing->nama }}</td>
                                        <td class="py-2.5 px-3 font-black text-amber-800 italic tracking-tighter">{{ $t->marketing->no_kartu_tol ?? '-' }}</td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">{{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3 font-bold text-slate-400 italic">{{ $t->customer_cp ?? '-' }}</td>
                                        <td class="py-2.5 px-3 font-bold text-amber-600 uppercase italic">{{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 uppercase">{{ $t->keterangan ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black italic text-slate-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right">
                                            <form action="{{ route('biaya-perjalanan.destroyTemp', ['tol', $t->id]) }}" method="POST">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'bensin'" x-cloak>
                        <table class="w-full text-left text-[10px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th><th class="py-2 px-3">Marketing</th><th class="py-2 px-3">Customer</th><th class="py-2 px-3">Kontak</th><th class="py-2 px-3 text-center text-emerald-600 uppercase">Posisi KM</th><th class="py-2 px-3">Keterangan</th><th class="py-2 px-3 text-right">Nominal</th><th class="py-2 px-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($tempBensin as $t)
                                    <tr class="hover:bg-emerald-50/50 transition">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">{{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase italic">{{ $t->marketing->nama }}</td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">{{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3 font-bold text-slate-400 italic">{{ $t->customer_cp ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-center font-black text-emerald-700 italic">{{ number_format($t->km, 0, ',', '.') }} KM</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 uppercase">{{ $t->keterangan ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black italic text-slate-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right">
                                            <form action="{{ route('biaya-perjalanan.destroyTemp', ['bensin', $t->id]) }}" method="POST">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'oper'" x-cloak>
                        <table class="w-full text-left text-[10px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th><th class="py-2 px-3">Marketing</th><th class="py-2 px-3">Customer</th><th class="py-2 px-3">Kontak</th><th class="py-2 px-3">Kategori</th><th class="py-2 px-3">Keterangan</th><th class="py-2 px-3 text-right">Nominal</th><th class="py-2 px-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($tempOperasional as $t)
                                    <tr class="hover:bg-blue-50/50 transition">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">{{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase italic">{{ $t->marketing->nama }}</td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">{{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3 font-bold text-slate-400 italic">{{ $t->customer_cp ?? '-' }}</td>
                                        <td class="py-2.5 px-3 font-bold text-orange-600 uppercase italic">{{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 uppercase">{{ $t->keterangan ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black italic text-slate-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right">
                                            <form action="{{ route('biaya-perjalanan.destroyTemp', ['operasional', $t->id]) }}" method="POST">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @php
                        $totalTemp = $tempAkomodasi->count() + $tempTol->count() + $tempBensin->count() + $tempOperasional->count();
                    @endphp

                    @if($totalTemp > 0)
                        <form action="{{ route('biaya-perjalanan.finalize') }}" method="POST" class="mt-8" @submit="finalizing = true">
                            @csrf
                            <button type="submit" :disabled="finalizing" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl hover:bg-blue-600 transition uppercase tracking-widest italic text-[10px] flex items-center justify-center gap-2">
                                <svg x-show="finalizing" class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="finalizing ? 'MENERBITKAN DATA RESMI...' : 'FINALISASI: SIMPAN PERMANEN SEMUA DRAF'"></span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
