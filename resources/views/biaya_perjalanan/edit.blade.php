<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen"
         x-data="{
            type: '{{ $type }}',
            marketingId: '{{ $data->marketing_id }}',
            marketings: {{ $marketings->toJson() }},
            rates: {{ isset($rates) ? $rates->toJson() : '[]' }},

            // State untuk Akomodasi
            kategoriAkom: '{{ $data->kategori }}',
            wilayah: '{{ $data->wilayah ?? 'Jabotabek' }}',
            durasi: {{ $data->durasi ?? 1 }},

            // State untuk Manual (Tol, Bensin, Operasional)
            displayNominal: '',
            rawNominal: '{{ (int)$data->nominal }}',

            get selectedMarketing() {
                return this.marketings.find(m => m.id == this.marketingId) || { level: '', no_kartu_tol: '' }
            },

            get calculatedAkomodasi() {
                if (this.type !== 'akomodasi') return 0;
                let match = this.rates.find(r =>
                    r.kategori === this.kategoriAkom &&
                    r.level == this.selectedMarketing.level &&
                    r.wilayah === this.wilayah
                );
                return match ? match.nominal * this.durasi : 0;
            },

            formatRupiah(val) {
                if (!val) return '0';
                return new Intl.NumberFormat('id-ID').format(val);
            },

            updateManualNominal(e) {
                let val = e.target.value.replace(/\D/g, '');
                this.rawNominal = val;
                this.displayNominal = this.formatRupiah(val);
            }
         }"
         x-init="displayNominal = formatRupiah(rawNominal)">

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-8 border-blue-600">

                <div class="p-10 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter italic">Edit Biaya {{ $type }}</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Record ID: #{{ $data->id }} — Starindo Digital System</p>
                        </div>
                        <a href="{{ route('biaya-perjalanan.index') }}" class="text-slate-400 hover:text-slate-800 font-bold text-[10px] uppercase tracking-widest transition">Back to List</a>
                    </div>
                </div>

                <form action="{{ route('biaya-perjalanan.update', [$type, $data->id]) }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Marketing Pemohon</label>
                            <select name="marketing_id" x-model="marketingId" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" required>
                                @foreach($marketings as $m)
                                    <option value="{{ $m->id }}">{{ strtoupper($m->nama) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" value="{{ $data->tanggal }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nama Customer</label>
                            <input type="text" name="customer_nama" value="{{ $data->customer_nama }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Kontak / CP</label>
                            <input type="text" name="customer_cp" value="{{ $data->customer_cp }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner">
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    @if($type === 'akomodasi')
                        <div class="space-y-6">
                            <div class="p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1 italic">Kategori Rate</label>
                                        <select name="kategori" x-model="kategoriAkom" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-sm">
                                            <option value="Hotel">Hotel</option>
                                            <option value="UM">Uang Makan (UM)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Level (Locked)</label>
                                        <input type="text" name="level" :value="selectedMarketing.level" readonly class="w-full bg-slate-100 border-none rounded-2xl p-4 font-black text-sm text-slate-500 cursor-not-allowed shadow-inner">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Wilayah</label>
                                        <select name="wilayah" x-model="wilayah" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-sm">
                                            <option value="Jabotabek">Jabotabek & Luar Pulau</option>
                                            <option value="Lainnya">Lainnya (Jawa)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Durasi</label>
                                        <input type="number" name="durasi" x-model="durasi" class="w-full bg-white border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-sm">
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-blue-100 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-blue-600 uppercase italic">Estimasi Nominal Baru</span>
                                    <span class="text-2xl font-black text-blue-700 italic tracking-tighter" x-text="'Rp ' + formatRupiah(calculatedAkomodasi)"></span>
                                </div>
                            </div>
                        </div>

                    @elseif($type === 'tol')
                        <div class="space-y-6">
                            <div class="p-4 bg-amber-50 rounded-2xl border-2 border-dashed border-amber-200 flex justify-between items-center">
                                <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest italic">ID Kartu Tol:</span>
                                <span class="text-sm font-black text-amber-800 italic" x-text="selectedMarketing.no_kartu_tol || 'BELUM DI-SET'"></span>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Kategori Tol</label>
                                <select name="kategori" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner focus:ring-2 focus:ring-blue-600">
                                    <option value="Top-Up Tol" {{ $data->kategori == 'Top-Up Tol' ? 'selected' : '' }}>Top-Up Tol</option>
                                    <option value="Pemakaian Tol" {{ $data->kategori == 'Pemakaian Tol' ? 'selected' : '' }}>Pemakaian Tol</option>
                                </select>
                            </div>
                        </div>

                    @elseif($type === 'bensin')
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-600 uppercase italic ml-1 tracking-widest">Posisi KM Kendaraan</label>
                                <input type="number" name="km" value="{{ $data->km }}" class="w-full bg-blue-50 border-none rounded-2xl p-4 font-black text-blue-700 shadow-inner focus:ring-2 focus:ring-blue-600">
                            </div>
                        </div>

                    @else
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Kategori Biaya</label>
                            <select name="kategori" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner focus:ring-2 focus:ring-blue-600">
                                <option value="Parkir" {{ $data->kategori == 'Parkir' ? 'selected' : '' }}>Parkir</option>
                                <option value="Cuci Kendaraan" {{ $data->kategori == 'Cuci Kendaraan' ? 'selected' : '' }}>Cuci</option>
                                <option value="Oleh-oleh" {{ $data->kategori == 'Oleh-oleh' ? 'selected' : '' }}>Oleh-oleh</option>
                                <option value="Lain-lain" {{ $data->kategori == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>
                    @endif

                    @if($type !== 'akomodasi')
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1 italic">Nominal Biaya (Value)</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-blue-300">Rp</span>
                                    <input type="text" x-model="displayNominal" @input="updateManualNominal($event)" class="w-full bg-blue-50 border-none rounded-2xl p-6 pl-12 font-black text-2xl text-blue-700 focus:ring-2 focus:ring-blue-600 shadow-inner italic">
                                    <input type="hidden" name="nominal" x-model="rawNominal">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Keterangan / Rincian</label>
                                <textarea name="keterangan" rows="2" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner focus:ring-2 focus:ring-blue-600">{{ $data->keterangan }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div class="pt-6">
                        <button type="submit" class="w-full py-6 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 hover:scale-[1.02] transition-all italic text-xs">
                            Update Record {{ strtoupper($type) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
