<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen"
         x-data="{
            type: '{{ $type }}',
            kategori: '{{ $data->kategori }}',
            marketingId: '{{ $data->marketing_id }}',
            marketings: {{ $marketings->toJson() }},
            rates: {{ $rates->toJson() }},
            wilayah: '{{ $data->wilayah ?? 'Jabotabek' }}',
            durasi: {{ $data->durasi ?? 1 }},
            displayNominal: '{{ number_format($data->nominal, 0, ',', '.') }}',
            rawNominal: '{{ $data->nominal }}',
            namaGerbang: '{{ $data->nama_gerbang ?? '' }}',
            km: '{{ $data->km ?? '' }}',

            // Format tanggal dinamis
            tanggalTransaksi: '{{ $type === 'tol' ? date('Y-m-d\TH:i', strtotime($data->tanggal)) : date('Y-m-d', strtotime($data->tanggal)) }}',

            init() {
                // Default Nama Gerbang jika kategori Top-Up
                if (this.kategori === 'Top-Up Tol' && !this.namaGerbang) {
                    this.namaGerbang = 'Top Up Tol';
                }
            },

            // Fungsi untuk update type secara otomatis saat kategori berubah
            // Agar template x-if di bawah sinkron dengan kategori baru
            updateType() {
                if (['Hotel', 'UM'].includes(this.kategori)) this.type = 'akomodasi';
                else if (['Top-Up Tol', 'Pemakaian Tol'].includes(this.kategori)) this.type = 'tol';
                else if (this.kategori === 'Bensin') this.type = 'bensin';
                else this.type = 'operasional';
            },

            get selectedMarketing() {
                return this.marketings.find(m => m.id == this.marketingId) || { level: '', no_kartu_tol: '' }
            },

            get calculatedAkomodasi() {
                if (this.type !== 'akomodasi') return 0;
                let rateFound = this.rates.find(r => r.kategori === this.kategori && r.level == this.selectedMarketing.level && r.wilayah === this.wilayah);
                return rateFound ? rateFound.nominal * this.durasi : 0;
            },

            formatRupiah(val) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
            }
         }">

        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border-t-8 border-blue-600">

                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Edit Draf Perjalanan</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Starindo Digital System</p>
                    </div>
                    <a href="{{ route('biaya-perjalanan.create') }}" class="text-slate-400 hover:text-slate-800 font-bold text-[10px] uppercase transition">Batal / Kembali</a>
                </div>

                <form action="{{ route('biaya-perjalanan.updateTemp', [$type, $data->id]) }}" method="POST" class="px-8 py-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="level" :value="selectedMarketing.level">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Marketing Pemohon</label>
                            <select name="marketing_id" x-model="marketingId" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600">
                                @foreach ($marketings as $m)
                                    <option value="{{ $m->id }}">{{ strtoupper($m->nama) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl & Waktu Transaksi</label>
                            <input :type="type === 'tol' ? 'datetime-local' : 'date'"
                                   name="tanggal"
                                   x-model="tanggalTransaksi"
                                   class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Customer</label>
                            <input type="text" name="customer_nama" value="{{ $data->customer_nama }}" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kontak / CP</label>
                            <input type="text" name="customer_cp" value="{{ $data->customer_cp }}" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-blue-600 uppercase tracking-widest ml-1">Kategori Biaya</label>
                        <select name="kategori" x-model="kategori" @change="updateType()"
                            class="w-full bg-blue-50/50 border-2 border-blue-100 rounded-xl p-3 font-black text-blue-600 uppercase text-xs shadow-sm focus:ring-2 focus:ring-blue-600 appearance-none">
                            <optgroup label="HOTEL & UM (RATE SISTEM)">
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
                                <option value="Cuci Kendaraan">Cuci Kendaraan</option>
                                <option value="Oleh-oleh">Oleh-oleh</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </optgroup>
                        </select>
                    </div>

                    <template x-if="type === 'akomodasi'">
                        <div class="grid grid-cols-2 gap-4 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-blue-600 uppercase ml-1">Wilayah Tujuan</label>
                                <select name="wilayah" x-model="wilayah" class="w-full bg-white border-none rounded-lg p-2 text-xs font-bold shadow-sm focus:ring-2 focus:ring-blue-600">
                                    <option value="Jabotabek">Jabotabek & Luar Pulau</option>
                                    <option value="Lainnya">Lainnya (Jawa)</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-blue-600 uppercase ml-1" x-text="kategori === 'Hotel' ? 'Durasi Malam' : 'Durasi Hari'"></label>
                                <input type="number" name="durasi" x-model="durasi" min="1" class="w-full bg-white border-none rounded-lg p-2 text-xs font-bold shadow-sm focus:ring-2 focus:ring-blue-600">
                            </div>
                        </div>
                    </template>

                    <template x-if="type === 'tol'">
                        <div class="space-y-4">
                            <div class="p-4 bg-amber-50 rounded-2xl border-2 border-dashed border-amber-200 flex justify-between items-center">
                                <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Kartu Tol:</span>
                                <span class="text-sm font-black text-amber-800" x-text="selectedMarketing.no_kartu_tol || 'BELUM DI-SET'"></span>
                            </div>
                            <div class="space-y-1 p-5 bg-amber-50/50 rounded-2xl border border-amber-100">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-1">Nama Gerbang Tol</label>
                                <input type="text" name="nama_gerbang" x-model="namaGerbang" :readonly="kategori === 'Top-Up Tol'"
                                       class="w-full bg-white border-none rounded-lg p-3 font-black text-amber-900 shadow-sm focus:ring-2 focus:ring-amber-500">
                            </div>
                        </div>
                    </template>

                    <template x-if="type === 'bensin'">
                        <div class="space-y-1 p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest ml-1">Posisi KM Kendaraan</label>
                            <input type="number" name="km" x-model="km" class="w-full bg-white border-none rounded-lg p-3 font-black text-emerald-700 shadow-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </template>

                    <template x-if="type !== 'akomodasi'">
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-blue-600 uppercase tracking-widest ml-1" x-text="type === 'tol' ? 'Update Biaya Tol' : 'Update Nominal Rupiah'"></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-blue-300 text-sm">Rp</span>
                                    <input type="text" x-model="displayNominal"
                                           @input="rawNominal = $event.target.value.replace(/\D/g, ''); displayNominal = new Intl.NumberFormat('id-ID').format(rawNominal)"
                                           class="w-full bg-blue-50 border-none rounded-xl p-4 pl-12 font-black text-xl text-blue-700 shadow-inner focus:ring-2 focus:ring-blue-600">
                                    <input type="hidden" name="nominal_value" :value="rawNominal">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan / Catatan</label>
                                <textarea name="keterangan" rows="2" class="w-full bg-slate-50 border-none rounded-xl p-3 text-xs font-bold shadow-inner focus:ring-2 focus:ring-blue-600">{{ $data->keterangan }}</textarea>
                            </div>
                        </div>
                    </template>

                    <div class="px-6 py-4 rounded-2xl bg-indigo-600 flex justify-between items-center shadow-lg transition-all">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-indigo-200 uppercase tracking-widest">Update Perubahan</span>
                            <span class="text-[9px] font-black text-white bg-indigo-500 px-2 py-0.5 rounded mt-0.5 shadow-sm inline-block" x-text="'Kategori: ' + kategori"></span>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] font-bold text-indigo-300 uppercase mb-1 tracking-tighter">Total Perhitungan</p>
                            <span class="text-2xl font-black text-white tracking-tighter leading-none"
                                  x-text="type === 'akomodasi' ? formatRupiah(calculatedAkomodasi) : formatRupiah(rawNominal)"></span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-lg hover:bg-blue-700 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>UPDATE DRAF PERJALANAN</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
