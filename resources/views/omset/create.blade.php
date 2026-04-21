<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-[12px] border-[#5850ec]">

                <div class="px-12 pt-12 pb-8 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-black text-[#1e293b] uppercase tracking-tighter">Input Omset Baru</h2>
                        <p class="text-xs font-bold text-[#94a3b8] mt-1">Pencatatan Omset Marketing Starindo</p>
                    </div>
                    <a href="{{ route('omset.index') }}"
                        class="text-[#94a3b8] hover:text-[#5850ec] font-black text-[10px] uppercase tracking-[0.15em] transition">
                        Back to List
                    </a>
                </div>

                <form action="{{ route('omset.store') }}" method="POST" class="px-12 pb-12 space-y-8">
                    @csrf

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-[#94a3b8] uppercase tracking-[0.2em] ml-2">Marketing Terkait</label>
                        <div class="relative">
                            <select name="marketing_id" required
                                class="w-full bg-[#f1f5f9] border-none rounded-[1.5rem] p-5 font-bold text-slate-700 focus:ring-2 focus:ring-[#5850ec] appearance-none cursor-pointer shadow-inner">
                                <option value="">Pilih Marketing</option>
                                @foreach ($marketings as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#94a3b8] uppercase tracking-[0.2em] ml-2">Periode Dari</label>
                            <input type="date" name="periode_dari" required
                                class="w-full bg-[#f1f5f9] border-none rounded-[1.5rem] p-5 font-bold text-slate-600 focus:ring-2 focus:ring-[#5850ec] shadow-inner uppercase">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#94a3b8] uppercase tracking-[0.2em] ml-2">Sampai Tanggal</label>
                            <input type="date" name="periode_sampai" required
                                class="w-full bg-[#f1f5f9] border-none rounded-[1.5rem] p-5 font-bold text-slate-600 focus:ring-2 focus:ring-[#5850ec] shadow-inner uppercase">
                        </div>
                    </div>

                    <div class="space-y-3" x-data="{
                        displayValue: '',
                        rawValue: '',
                        formatRupiah(val) {
                            if (!val) return '0';
                            return new Intl.NumberFormat('id-ID').format(val);
                        },
                        updateValue(e) {
                            let val = e.target.value.replace(/\D/g, '');
                            this.rawValue = val;
                            this.displayValue = this.formatRupiah(val);
                        }
                    }">
                        <label class="text-[10px] font-black text-[#5850ec] uppercase tracking-[0.2em] ml-2">Nominal Omset (IDR)</label>
                        <div class="bg-[#f1f5f9] rounded-[1.8rem] p-5 flex items-center shadow-inner group-within:ring-2 group-within:ring-[#5850ec] transition-all">
                            <span class="text-3xl font-black text-[#cbd5e1] mr-5">Rp</span>
                            <input type="text" x-model="displayValue" @input="updateValue($event)" placeholder="0"
                                class="w-full bg-transparent border-none p-0 font-black text-2xl text-[#334155] focus:ring-0 placeholder-[#cbd5e1]">
                            <input type="hidden" name="nominal" x-model="rawValue" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full py-6 bg-[#5850ec] text-white rounded-[2rem] font-black uppercase tracking-[0.25em] text-xs shadow-2xl shadow-indigo-100 hover:bg-[#4338ca] hover:scale-[1.01] transition-all active:scale-95">
                            Simpan Data Omset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
