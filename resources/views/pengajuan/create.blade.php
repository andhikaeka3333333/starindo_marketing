<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-8 border-blue-600">

                <div class="p-10 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Tambah Data
                                Pengajuan</h2>
                            <p class="text-xs font-bold text-slate-400">Input Data Pengajuan Starindo</p>
                        </div>
                        <a href="{{ route('pengajuan.index') }}"
                            class="text-slate-400 hover:text-slate-800 font-bold text-sm transition">Back to List</a>
                    </div>
                </div>

                <form action="{{ route('pengajuan.store') }}" method="POST" class="p-10 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Marketing
                                Pemohon</label>
                            <select name="marketing_id"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner"
                                required>
                                <option value="">Pilih Marketing</option>
                                @foreach ($marketings as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }} (Lvl {{ $m->level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal
                                Transaksi</label>
                            <input type="date" name="tanggal"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner"
                                required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                            Customer & CP</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="text" name="customer_nama" placeholder="Nama Customer"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner"
                                required>
                            <input type="text" name="customer_cp" placeholder="Kontak"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Pengajuan</label>
                            <select name="kategori_pengajuan_id"
                                class="w-full px-4 py-2 border rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2" x-data="{
                        displayValue: '',
                        rawValue: '',
                        formatRupiah(val) {
                            if (!val) return '';
                            return new Intl.NumberFormat('id-ID').format(val);
                        },
                        updateValue(e) {
                            // Ambil angka saja, buang karakter lain
                            let val = e.target.value.replace(/\D/g, '');
                            this.rawValue = val;
                            this.displayValue = this.formatRupiah(val);
                        }
                    }">

                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Nominal
                            Rupiah (Value)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-blue-300">Rp</span>

                            <input type="text" x-model="displayValue" @input="updateValue($event)" placeholder="0"
                                class="w-full bg-blue-50 border-none rounded-2xl p-6 pl-12 font-black text-2xl text-blue-700 focus:ring-2 focus:ring-blue-600 shadow-inner">

                            <input type="hidden" name="nominal_value" x-model="rawValue" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat
                            Lengkap</label>
                        <textarea name="alamat" rows="3"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner"
                            placeholder="Tulis alamat lengkap customer di sini..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-6 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 hover:scale-[1.02] transition-all">
                        Submit Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
