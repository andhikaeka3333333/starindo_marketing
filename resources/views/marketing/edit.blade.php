<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-8 border-blue-600">

                <div class="p-10 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter italic">Update Marketing</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Editing ID: #{{ $marketing->id }}</p>
                        </div>
                        <a href="{{ route('marketing.index') }}" class="text-slate-400 hover:text-slate-800 font-bold text-[10px] uppercase tracking-widest transition">Back to List</a>
                    </div>
                </div>

                <form action="{{ route('marketing.update', $marketing->id) }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nama Lengkap Personel</label>
                        <input type="text" name="nama" value="{{ old('nama', $marketing->nama) }}"
                               class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-slate-700 focus:ring-2 focus:ring-blue-600 shadow-inner uppercase italic" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nomor Kartu Tol (E-Money)</label>
                        <input type="text" name="no_kartu_tol" value="{{ old('no_kartu_tol', $marketing->no_kartu_tol) }}"
                               class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-indigo-600 shadow-inner focus:ring-2 focus:ring-blue-600" placeholder="Contoh: 1234...">
                    </div>

                    <div class="space-y-2" x-data="{
                        s: '{{ number_format(old('sisa_saldo_tol', $marketing->sisa_saldo_tol), 0, ',', '.') }}',
                        formatRupiah(val) {
                            if (!val) return '';
                            let angka = val.toString().replace(/\D/g, '');
                            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        }
                    }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Sisa Saldo Tol (Rp)</label>
                        <input type="text" x-model="s" @input="s = formatRupiah($event.target.value)"
                               class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-indigo-600 shadow-inner focus:ring-2 focus:ring-blue-600" placeholder="0">
                        <input type="hidden" name="sisa_saldo_tol" :value="s.replace(/\./g, '')">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Level Jabatan</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([1, 2, 3] as $l)
                            <label class="cursor-pointer group">
                                <input type="radio" name="level" value="{{ $l }}" {{ $marketing->level == $l ? 'checked' : '' }} class="hidden peer" required>
                                <div class="text-center py-4 rounded-2xl border-2 border-slate-50 bg-slate-50 peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white transition-all font-black text-slate-300">
                                    LVL {{ $l }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex pt-6">
                        <button type="submit" class="flex-1 py-6 bg-slate-900 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl hover:bg-blue-600 hover:scale-[1.02] transition-all italic text-[11px]">
                            Update Data Marketing
                        </button>
                    </div>
                </form>

            </div>
            <p class="text-center mt-8 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Master Data Starindo Jaya Packaging</p>
        </div>
    </div>
</x-app-layout>
