<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        tab: 'akom',
        // 1. PERSISTENCE: Mengambil data dari localStorage agar tidak hilang saat refresh
        kategori: localStorage.getItem('last_kategori') || 'Hotel',
        marketingId: localStorage.getItem('last_marketing_id') || '',
        customerNama: localStorage.getItem('last_customer_nama') || '',
        customerCp: localStorage.getItem('last_customer_cp') || '',
        km: localStorage.getItem('last_km') || '',
        tanggalTransaksi: localStorage.getItem('last_tanggal') || '{{ date('Y-m-d\TH:i') }}',

        marketings: {{ $marketings->toJson() }},
        rates: {{ $rates->toJson() }},
        tempTols: {{ $tempTol->toJson() }}, // Digunakan untuk kalkulasi estimasi saldo

        loading: false,
        finalizing: false,
        displayNominal: '',
        rawNominal: '',
        wilayah: 'Jabotabek',
        durasi: 1,
        namaGerbang: '',

        get availableWilayahs() {
            if (!this.rates) return [];
            let filtered = this.rates.filter(r => r.kategori === this.kategori);
            // new Set() digunakan agar nama wilayah yang sama tidak muncul berkali-kali di dropdown
            return [...new Set(filtered.map(r => r.wilayah))];
        },

        init() {
            // Watchers tetap sama
            this.$watch('marketingId', val => localStorage.setItem('last_marketing_id', val));
            this.$watch('customerNama', val => localStorage.setItem('last_customer_nama', val));
            this.$watch('customerCp', val => localStorage.setItem('last_customer_cp', val));
            this.$watch('kategori', val => localStorage.setItem('last_kategori', val));
            this.$watch('km', val => localStorage.setItem('last_km', val));
            this.$watch('tanggalTransaksi', val => localStorage.setItem('last_tanggal', val));

            // UBAH BAGIAN INI: Memisahkan tab Pemakaian Tol dan Selisih Tol
            if (['Hotel', 'UM'].includes(this.kategori)) {
                this.tab = 'akom';
            } else if (this.kategori === 'Top-Up Tol') {
                this.tab = 'topup';
            } else if (this.kategori === 'Pemakaian Tol') {
                this.tab = 'pakai';
            } else if (this.kategori === 'Selisih Tol') {
                this.tab = 'selisih';
            } else if (this.kategori === 'Bensin') {
                this.tab = 'bensin';
            } else {
                this.tab = 'oper';
            }
        },

        get selectedMarketing() {
            return this.marketings.find(m => m.id == this.marketingId) || { level: '', no_kartu_tol: '', sisa_saldo_tol: 0 }
        },

        // 2. LOGIKA ESTIMASI SALDO: Simulasi saldo sebelum finalisasi
        get estimatedSaldoTol() {
            let mId = this.marketingId;
            if (!mId) return 0;

            let initialBalance = parseFloat(this.selectedMarketing.sisa_saldo_tol || 0);
            let myTempTols = this.tempTols.filter(t => t.marketing_id == mId);

            let adjustment = myTempTols.reduce((acc, curr) => {
                let nominal = parseFloat(curr.nominal);
                // Top-Up menambah saldo, Pemakaian & Selisih mengurangi saldo
                if (curr.kategori === 'Top-Up Tol') {
                    return acc + nominal;
                } else if (curr.kategori === 'Pemakaian Tol' || curr.kategori === 'Selisih Tol') {
                    return acc - nominal;
                }
                return acc;
            }, 0);

            return initialBalance + adjustment;
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
        },

        resetForm() {
            if (confirm('Reset semua data pemohon?')) {
                localStorage.clear();
                window.location.reload();
            }
        }
    }">

        <div class="max-w-3xl mx-auto px-4 mb-10">
            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border-t-8 border-blue-600">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter italic">Tambah Biaya
                            Perjalanan</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Starindo Digital System
                        </p>
                    </div>
                    <div class="flex gap-4">
                        <button @click="resetForm()"
                            class="text-red-500 hover:text-red-700 font-bold text-[10px] uppercase transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset Form
                        </button>
                        <a href="{{ route('biaya-perjalanan.index') }}"
                            class="text-slate-400 hover:text-slate-800 font-bold text-[10px] uppercase transition">Kembali</a>
                    </div>
                </div>

                <form action="{{ route('biaya-perjalanan.storeTemp') }}" method="POST" class="px-8 py-6 space-y-5"
                    @submit="loading = true">
                    @csrf
                    <input type="hidden" name="level" :value="selectedMarketing.level">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Marketing
                                Pemohon</label>
                            <select name="marketing_id" x-model="marketingId"
                                class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600"
                                required>
                                <option value="">Pilih Nama Marketing</option>
                                @foreach ($marketings as $m)
                                    <option value="{{ $m->id }}">{{ strtoupper($m->nama) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Tgl &
                                Waktu</label>
                            <input
                                :type="['Top-Up Tol', 'Pemakaian Tol', 'Selisih Tol'].includes(kategori) ? 'datetime-local' :
                                    'date'"
                                name="tanggal" x-model="tanggalTransaksi"
                                class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nama
                                Customer</label>
                            <input type="text" name="customer_nama" x-model="customerNama"
                                placeholder="Nama Customer..."
                                class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600"
                                required>
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Kontak
                                / CP</label>
                            <input type="text" name="customer_cp" x-model="customerCp" placeholder="WA / Telp"
                                class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label
                            class="text-[9px] font-black text-blue-600 uppercase tracking-widest ml-1 italic">Kategori
                            Biaya</label>
                        <select name="kategori" x-model="kategori"
                            class="w-full bg-blue-50/50 border-2 border-blue-100 rounded-xl p-3 font-black text-blue-600 uppercase text-xs shadow-sm focus:ring-2 focus:ring-blue-600 appearance-none">
                            <optgroup label="HOTEL & UM (RATE SISTEM)">
                                <option value="Hotel">Hotel</option>
                                <option value="UM">Uang Makan (UM)</option>
                            </optgroup>
                            <optgroup label="TOL (INPUT MANUAL)">
                                <option value="Top-Up Tol">Top-Up Tol</option>
                                <option value="Pemakaian Tol">Pemakaian Tol</option>
                                <option value="Selisih Tol">Selisih Tol</option>
                            </optgroup>
                            <optgroup label="KENDARAAN (INPUT MANUAL)">
                                <option value="Bensin">Bensin</option>
                            </optgroup>
                            <optgroup label="OPERASIONAL (INPUT MANUAL)">
                                <option value="Parkir">Parkir</option>
                                <option value="Cuci Kendaraan">Cuci Kendaraan</option>
                                <option value="Oleh-oleh">Oleh-oleh</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </optgroup>
                        </select>
                    </div>

                    <div x-show="['Hotel', 'UM'].includes(kategori)" x-transition
                        class="grid grid-cols-2 gap-4 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase italic ml-1">Wilayah
                                Tujuan</label>
                            <select name="wilayah" x-model="wilayah"
                                class="w-full bg-white border-none rounded-lg p-2 text-xs font-bold shadow-sm focus:ring-2 focus:ring-blue-600">

                                <template x-for="w in availableWilayahs" :key="w">
                                    <option :value="w" x-text="w"></option>
                                </template>

                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase italic ml-1"
                                x-text="kategori === 'Hotel' ? 'Durasi (Malam)' : 'Durasi (Hari)'"></label>
                            <input type="number" name="durasi" x-model="durasi" min="1"
                                class="w-full bg-white border-none rounded-lg p-2 text-xs font-bold shadow-sm focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>

                    <div x-show="['Top-Up Tol', 'Pemakaian Tol', 'Selisih Tol'].includes(kategori)" x-transition
                        class="space-y-4">
                        <div
                            class="p-5 bg-amber-50 rounded-[2rem] border-2 border-amber-200 shadow-inner grid grid-cols-2 gap-4">
                            <div class="space-y-2 border-r border-amber-200 pr-4">
                                <span class="text-[8px] font-black text-amber-600 uppercase tracking-widest">Saldo Saat
                                    Ini</span>
                                <p class="text-[10px] font-black text-amber-900 italic"
                                    x-text="selectedMarketing.no_kartu_tol || 'BELUM DI-SET'"></p>
                                <p class="text-lg font-black text-slate-800"
                                    x-text="formatRupiah(selectedMarketing.sisa_saldo_tol || 0)"></p>
                            </div>

                            <div class="space-y-2 pl-2">
                                <span class="text-[8px] font-black text-blue-600 uppercase tracking-widest">Estimasi
                                    Saldo Akhir Setelah Finalisasi</span>
                                <p class="text-lg font-black"
                                    :class="estimatedSaldoTol < 0 ? 'text-red-600' : 'text-blue-700'"
                                    x-text="formatRupiah(estimatedSaldoTol)"></p>
                            </div>
                        </div>

                        <div x-show="kategori === 'Pemakaian Tol'" x-transition class="space-y-1">
                            <label
                                class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-1 italic">Nama
                                Gerbang Tol</label>
                            <input type="text" name="nama_gerbang" x-model="namaGerbang"
                                placeholder="Contoh: GT Kudus / GT Manyaran"
                                class="w-full bg-amber-50 border-none rounded-xl p-3 text-xs font-black text-amber-900 shadow-inner focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <div x-show="!['Hotel', 'UM'].includes(kategori)" x-transition class="space-y-4">
                        <div x-show="kategori === 'Bensin'" x-transition class="space-y-1">
                            <label
                                class="text-[9px] font-black text-emerald-600 uppercase tracking-widest ml-1 italic">
                                Posisi KM Kendaraan (Odometer)
                            </label>
                            <input type="number" name="km" x-model="km" placeholder="Contoh: 12500"
                                class="w-full bg-emerald-50 border-none rounded-xl p-3 text-xs font-black text-emerald-900 shadow-inner focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase tracking-widest ml-1 italic"
                                x-text="['Pemakaian Tol', 'Selisih Tol'].includes(kategori) ? 'Biaya yang Terpakai / Selisih' : 'Input Nominal'"></label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-blue-300 text-sm">Rp</span>
                                <input type="text" x-model="displayNominal" @input="updateManualNominal($event)"
                                    placeholder="0"
                                    class="w-full bg-blue-50 border-none rounded-xl p-4 pl-12 font-black text-xl text-blue-700 shadow-inner focus:ring-2 focus:ring-blue-600">
                                <input type="hidden" name="nominal_value" :value="rawNominal">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Keterangan
                                / Catatan</label>
                            <textarea name="keterangan" rows="1"
                                class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600"
                                placeholder="Keterangan opsional..."></textarea>
                        </div>
                    </div>

                    <div x-show="['Hotel', 'UM'].includes(kategori)"
                        class="px-6 py-4 rounded-2xl bg-indigo-600 flex justify-between items-center shadow-lg">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-indigo-200 uppercase tracking-widest">Estimasi
                                Nominal</span>
                            <span
                                class="text-[9px] font-black text-white bg-indigo-500 px-2 py-0.5 rounded mt-0.5 shadow-sm"
                                x-text="'Lvl: ' + (selectedMarketing.level || '-')"></span>
                        </div>
                        <span class="text-2xl font-black text-white italic tracking-tighter"
                            x-text="formatRupiah(calculatedAkomodasi)"></span>
                    </div>

                    <button type="submit" :disabled="loading"
                        class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-lg hover:bg-blue-700 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="loading ? 'MENYIMPAN DRAF...' : '+ TAMBAH KE DAFTAR DRAF'"></span>
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-[1400px] mx-auto px-4 pb-20">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
                <div
                    class="flex items-center bg-slate-100 border-b border-slate-200 pt-2 px-6 gap-1 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button @click="tab = 'akom'"
                        :class="tab === 'akom' ?
                            'bg-white border-x border-t border-slate-200 text-blue-600 font-black rounded-t-xl -mb-[1px]' :
                            'text-slate-400 font-bold'"
                        class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all italic">
                        Hotel & UM ({{ $tempAkomodasi->count() }})
                    </button>

                    <button @click="tab = 'topup'"
                        :class="tab === 'topup' ?
                            'bg-white border-x border-t border-slate-200 text-amber-600 font-black rounded-t-xl -mb-[1px]' :
                            'text-slate-400 font-bold'"
                        class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all italic">
                        Top-Up Tol ({{ $tempTopUp->count() }})
                    </button>

                    <button @click="tab = 'pakai'"
                        :class="tab === 'pakai' ?
                            'bg-white border-x border-t border-slate-200 text-orange-600 font-black rounded-t-xl -mb-[1px]' :
                            'text-slate-400 font-bold'"
                        class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all italic">
                        Pemakaian Tol ({{ $tempTol->where('kategori', 'Pemakaian Tol')->count() }})
                    </button>

                    <button @click="tab = 'selisih'"
                        :class="tab === 'selisih' ?
                            'bg-white border-x border-t border-slate-200 text-rose-600 font-black rounded-t-xl -mb-[1px]' :
                            'text-slate-400 font-bold'"
                        class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all italic">
                        Selisih Tol ({{ $tempTol->where('kategori', 'Selisih Tol')->count() }})
                    </button>

                    <button @click="tab = 'bensin'"
                        :class="tab === 'bensin' ?
                            'bg-white border-x border-t border-slate-200 text-emerald-600 font-black rounded-t-xl -mb-[1px]' :
                            'text-slate-400 font-bold'"
                        class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all italic">
                        Bensin ({{ $tempBensin->count() }})
                    </button>
                    <button @click="tab = 'oper'"
                        :class="tab === 'oper' ?
                            'bg-white border-x border-t border-slate-200 text-indigo-600 font-black rounded-t-xl -mb-[1px]' :
                            'text-slate-400 font-bold'"
                        class="px-6 py-2.5 text-[9px] uppercase tracking-widest transition-all italic">
                        Lainnya ({{ $tempOperasional->count() }})
                    </button>
                </div>

                <div class="p-6">
                    <div x-show="tab === 'topup'" x-cloak class="overflow-x-auto">
                        <table
                            class="w-full text-left text-[10px] whitespace-nowrap table-auto border-separate border-spacing-y-1">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl & Waktu</th>
                                    <th class="py-2 px-3">Marketing</th>
                                    <th class="py-2 px-3">Customer</th>
                                    <th class="py-2 px-3">CP / Kontak</th>
                                    <th class="py-2 px-3">Kategori</th>
                                    <th class="py-2 px-3">Keterangan</th>
                                    <th class="py-2 px-3 text-right">Nominal</th>
                                    <th class="py-2 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempTopUp as $t)
                                    <tr class="hover:bg-amber-50/50 transition bg-slate-50/20">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">
                                            {{ date('d/m/y H:i', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase ">{{ $t->marketing->nama }}
                                        </td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">
                                            {{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3  text-slate-400">{{ $t->customer_cp ?? '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-bold text-amber-600  uppercase">
                                            {{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 ">
                                            {{ Str::limit($t->keterangan, 30) ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black  text-slate-900">Rp
                                            {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('biaya-perjalanan.editTemp', ['tol', $t->id]) }}"
                                                    class="text-blue-400 hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('biaya-perjalanan.destroyTemp', ['tol', $t->id]) }}"
                                                    method="POST" onsubmit="return confirm('Hapus draf ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition"><svg
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'pakai'" x-cloak class="overflow-x-auto">
                        <table
                            class="w-full text-left text-[10px] whitespace-nowrap table-auto border-separate border-spacing-y-1">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl & Waktu</th>
                                    <th class="py-2 px-3">Marketing</th>
                                    <th class="py-2 px-3">Gerbang Tol</th>
                                    <th class="py-2 px-3">Customer</th>
                                    <th class="py-2 px-3">CP / Kontak</th>
                                    <th class="py-2 px-3">Kategori</th>
                                    <th class="py-2 px-3">Keterangan</th>
                                    <th class="py-2 px-3 text-right">Nominal</th>
                                    <th class="py-2 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempTol->where('kategori', 'Pemakaian Tol') as $t)
                                    <tr class="hover:bg-amber-50/50 transition bg-slate-50/20">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">
                                            {{ date('d/m/y H:i', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase ">{{ $t->marketing->nama }}
                                        </td>
                                        <td
                                            class="py-2.5 px-3 font-black text-amber-900  uppercase decoration-amber-200">
                                            {{ $t->nama_gerbang ?? '-' }}</td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">
                                            {{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3  text-slate-400">{{ $t->customer_cp ?? '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-bold text-amber-600  uppercase">
                                            {{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 ">
                                            {{ Str::limit($t->keterangan, 30) ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black  text-slate-900">Rp
                                            {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('biaya-perjalanan.editTemp', ['tol', $t->id]) }}"
                                                    class="text-blue-400 hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('biaya-perjalanan.destroyTemp', ['tol', $t->id]) }}"
                                                    method="POST" onsubmit="return confirm('Hapus draf ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition"><svg
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'selisih'" x-cloak class="overflow-x-auto">
                        <table
                            class="w-full text-left text-[10px] whitespace-nowrap table-auto border-separate border-spacing-y-1">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl & Waktu</th>
                                    <th class="py-2 px-3">Marketing</th>
                                    {{-- <th class="py-2 px-3">Gerbang Tol</th> --}}
                                    <th class="py-2 px-3">Customer</th>
                                    <th class="py-2 px-3">CP / Kontak</th>
                                    <th class="py-2 px-3">Kategori</th>
                                    <th class="py-2 px-3">Keterangan</th>
                                    <th class="py-2 px-3 text-right">Nominal</th>
                                    <th class="py-2 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempTol->where('kategori', 'Selisih Tol') as $t)
                                    <tr class="hover:bg-rose-50/50 transition bg-slate-50/20">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">
                                            {{ date('d/m/y H:i', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase ">{{ $t->marketing->nama }}
                                        </td>
                                        {{-- <td
                                            class="py-2.5 px-3 font-black text-rose-900  uppercase decoration-rose-200">
                                            {{ $t->nama_gerbang ?? '-' }}</td> --}}
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">
                                            {{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3  text-slate-400">{{ $t->customer_cp ?? '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-bold text-rose-600  uppercase">
                                            {{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 ">
                                            {{ Str::limit($t->keterangan, 30) ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black  text-slate-900">Rp
                                            {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('biaya-perjalanan.editTemp', ['tol', $t->id]) }}"
                                                    class="text-blue-400 hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('biaya-perjalanan.destroyTemp', ['tol', $t->id]) }}"
                                                    method="POST" onsubmit="return confirm('Hapus draf ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition"><svg
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'akom'" x-cloak class="overflow-x-auto">
                        <table
                            class="w-full text-left text-[10px] whitespace-nowrap table-auto border-separate border-spacing-y-1">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th>
                                    <th class="py-2 px-3">Marketing</th>
                                    <th class="py-2 px-3">Customer</th>
                                    <th class="py-2 px-3">CP / Kontak</th>
                                    <th class="py-2 px-3">Kategori</th>
                                    <th class="py-2 px-3">Lvl</th>
                                    <th class="py-2 px-3 text-center">Wilayah</th>
                                    <th class="py-2 px-3 text-center">Durasi</th>
                                    <th class="py-2 px-3 text-right">Total Nominal</th>
                                    <th class="py-2 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempAkomodasi as $t)
                                    <tr class="hover:bg-blue-50/50 transition bg-slate-50/20">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">
                                            {{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase ">{{ $t->marketing->nama }}
                                        </td>
                                        <td class="py-2.5 px-3 font-black text-blue-600 uppercase">
                                            {{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3  text-slate-400">{{ $t->customer_cp ?? '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-bold  text-slate-700 uppercase">
                                            {{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-black text-indigo-600 uppercase">Lvl
                                            {{ $t->level }}</td>
                                        <td class="py-2.5 px-3 text-center font-bold uppercase text-slate-500">
                                            {{ $t->wilayah }}</td>
                                        <td class="py-2.5 px-3 text-center font-bold">{{ $t->durasi }}
                                            Hari/Malam
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-black  text-slate-900">Rp
                                            {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('biaya-perjalanan.editTemp', ['akomodasi', $t->id]) }}"
                                                    class="text-blue-400 hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('biaya-perjalanan.destroyTemp', ['akomodasi', $t->id]) }}"
                                                    method="POST">@csrf @method('DELETE')<button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition"><svg
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg></button></form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'bensin'" x-cloak class="overflow-x-auto">
                        <table
                            class="w-full text-left text-[10px] whitespace-nowrap table-auto border-separate border-spacing-y-1">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th>
                                    <th class="py-2 px-3">Marketing</th>
                                    <th class="py-2 px-3">Customer</th>
                                    <th class="py-2 px-3">CP / Kontak</th>
                                    <th class="py-2 px-3 text-center">Posisi KM</th>
                                    <th class="py-2 px-3">Kategori</th>
                                    <th class="py-2 px-3">Keterangan</th>
                                    <th class="py-2 px-3 text-right">Nominal</th>
                                    <th class="py-2 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempBensin as $t)
                                    <tr class="hover:bg-emerald-50/50 transition bg-slate-50/20">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">
                                            {{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase ">{{ $t->marketing->nama }}
                                        </td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">
                                            {{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3  text-slate-400">{{ $t->customer_cp ?? '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 text-center font-black text-emerald-700 ">
                                            {{ number_format($t->km, 0, ',', '.') }} KM</td>
                                        <td class="py-2.5 px-3 font-bold  text-emerald-600 uppercase">
                                            {{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 ">
                                            {{ Str::limit($t->keterangan, 30) ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black  text-slate-900">Rp
                                            {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('biaya-perjalanan.editTemp', ['bensin', $t->id]) }}"
                                                    class="text-blue-400 hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('biaya-perjalanan.destroyTemp', ['bensin', $t->id]) }}"
                                                    method="POST">@csrf @method('DELETE')<button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition"><svg
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg></button></form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'oper'" x-cloak class="overflow-x-auto">
                        <table
                            class="w-full text-left text-[10px] whitespace-nowrap table-auto border-separate border-spacing-y-1">
                            <thead class="text-slate-400 uppercase font-black border-b border-slate-100">
                                <tr>
                                    <th class="py-2 px-3">Tgl</th>
                                    <th class="py-2 px-3">Marketing</th>
                                    <th class="py-2 px-3">Customer</th>
                                    <th class="py-2 px-3">CP / Kontak</th>
                                    <th class="py-2 px-3">Kategori</th>
                                    <th class="py-2 px-3">Keterangan</th>
                                    <th class="py-2 px-3 text-right">Nominal</th>
                                    <th class="py-2 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempOperasional as $t)
                                    <tr class="hover:bg-indigo-50/50 transition bg-slate-50/20">
                                        <td class="py-2.5 px-3 font-bold text-slate-400">
                                            {{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                        <td class="py-2.5 px-3 font-black uppercase ">{{ $t->marketing->nama }}
                                        </td>
                                        <td class="py-2.5 px-3 font-black text-slate-800 uppercase">
                                            {{ $t->customer_nama }}</td>
                                        <td class="py-2.5 px-3  text-slate-400">{{ $t->customer_cp ?? '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-bold  text-indigo-600 uppercase">
                                            {{ $t->kategori }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-500 ">
                                            {{ Str::limit($t->keterangan, 40) ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-right font-black  text-slate-900">Rp
                                            {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('biaya-perjalanan.editTemp', ['operasional', $t->id]) }}"
                                                    class="text-blue-400 hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('biaya-perjalanan.destroyTemp', ['operasional', $t->id]) }}"
                                                    method="POST">@csrf @method('DELETE')<button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition"><svg
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg></button></form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @php
                        $totalTemp =
                            $tempAkomodasi->count() +
                            $tempTol->count() +
                            $tempBensin->count() +
                            $tempOperasional->count();
                    @endphp

                    @if ($totalTemp > 0)
                        <form action="{{ route('biaya-perjalanan.finalize') }}" method="POST" class="mt-8"
                            @submit="finalizing = true">
                            @csrf
                            <button type="submit" :disabled="finalizing"
                                class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl hover:bg-blue-600 transition uppercase tracking-widest  text-[10px] flex items-center justify-center gap-2">
                                <svg x-show="finalizing" class="animate-spin h-3 w-3 text-white" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span
                                    x-text="finalizing ? 'MENERBITKAN DATA RESMI...' : 'FINALISASI: SIMPAN PERMANEN SEMUA DRAF'"></span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
</x-app-layout>
