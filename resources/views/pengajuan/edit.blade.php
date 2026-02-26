<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-8 border-blue-600">

                <div class="p-10 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Update Record</h2>
                            <p class="text-xs font-bold text-slate-400">Editing Pengajuan ID: #{{ $pengajuan->id }}</p>
                        </div>
                        <a href="{{ route('pengajuan.index') }}" class="text-slate-400 hover:text-slate-800 font-bold text-sm transition">Back to List</a>
                    </div>
                </div>

                <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Marketing Pemohon</label>
                            <select name="marketing_id" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" required>
                                @foreach($marketings as $m)
                                    <option value="{{ $m->id }}" {{ $pengajuan->marketing_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }} (Lvl {{ $m->level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" value="{{ $pengajuan->tanggal }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Customer & CP</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="text" name="customer_nama" value="{{ $pengajuan->customer_nama }}" placeholder="Nama Customer" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" required>
                            <input type="text" name="customer_cp" value="{{ $pengajuan->customer_cp }}" placeholder="Kontak" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Pengajuan</label>
                        <select name="jenis_pengajuan" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner">
                            @foreach(['Komisi Penjualan', 'Entertain', 'Proposal'] as $jenis)
                                <option value="{{ $jenis }}" {{ $pengajuan->jenis_pengajuan == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2"
                         x-data="{
                            displayValue: '',
                            rawValue: '{{ (int)$pengajuan->nominal_value }}',
                            formatRupiah(val) {
                                if (!val) return '';
                                return new Intl.NumberFormat('id-ID').format(val);
                            },
                            updateValue(e) {
                                // Hanya ambil angka saja
                                let val = e.target.value.replace(/\D/g, '');
                                this.rawValue = val;
                                this.displayValue = this.formatRupiah(val);
                            }
                         }"
                         x-init="displayValue = formatRupiah(rawValue)">

                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Nominal Rupiah (Value)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-blue-300">Rp</span>

                            <input type="text"
                                   x-model="displayValue"
                                   @input="updateValue($event)"
                                   placeholder="0"
                                   class="w-full bg-blue-50 border-none rounded-2xl p-6 pl-12 font-black text-2xl text-blue-700 focus:ring-2 focus:ring-blue-600 shadow-inner">

                            <input type="hidden" name="nominal_value" x-model="rawValue" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm focus:ring-2 focus:ring-blue-600 shadow-inner" placeholder="Tulis alamat lengkap customer di sini...">{{ $pengajuan->alamat }}</textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 py-6 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 hover:scale-[1.02] transition-all">
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
