<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-slate-800 uppercase tracking-tighter italic">Master Data Marketing & Tarif</h2>
            <div class="bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-end">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Personel</span>
                <span class="text-lg font-black text-blue-600 italic leading-none">{{ $stats['total'] }} ORG</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen" x-data="{
        showTarifModal: false,
        formatRupiah(val) {
            if (!val) return '';
            let angka = val.toString().replace(/\D/g, '');
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }">
        <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-white border-l-8 border-blue-600 shadow-xl rounded-2xl text-slate-800 font-black italic uppercase text-xs tracking-widest">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-8 space-y-8">

                    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                            <h3 class="font-black text-slate-800 uppercase italic tracking-tighter">Data Marketing</h3>
                            <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="bg-white border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-600 shadow-inner px-4 py-2">
                                <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-xl font-black uppercase text-[10px]">CARI</button>
                            </form>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                                <tr>
                                    <th class="px-8 py-5">Marketing</th>
                                    <th class="px-8 py-5">Kartu Tol</th>
                                    <th class="px-8 py-5 text-center">Level</th>
                                    <th class="px-8 py-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($marketings as $m)
                                <tr>
                                    <td class="px-8 py-5 font-black text-slate-700 italic">{{ $m->nama }}</td>
                                    <td class="px-8 py-5 font-black text-xs text-blue-600 italic tracking-widest">{{ $m->no_kartu_tol ?? '----' }}</td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-3 py-1 bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase italic">LVL {{ $m->level }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex justify-end gap-5">
                                            <a href="{{ route('marketing.edit', $m->id) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                    <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('marketing.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors leading-none">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="p-6 bg-slate-50 border-t border-slate-100">{{ $marketings->links() }}</div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800 uppercase italic tracking-tighter">Master Tarif</h3>
                            <button @click="showTarifModal = true" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                                + TAMBAH TARIF
                            </button>
                        </div>

                        <form action="{{ route('marketing.update-tarif') }}" method="POST">
                            @csrf
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                                    <tr>
                                        <th class="px-8 py-5">Kategori</th>
                                        <th class="px-8 py-5">Wilayah</th>
                                        <th class="px-8 py-5 text-center">Level</th>
                                        <th class="px-8 py-5 text-right">Nominal (Rp)</th>
                                        <th class="px-8 py-5"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($daftarTarif as $t)
                                    <tr class="hover:bg-slate-50/50" x-data="{ val: formatRupiah('{{ (int)$t->nominal }}') }">
                                        <td class="px-8 py-4 font-black text-slate-800 italic uppercase">{{ $t->kategori }}</td>
                                        <td class="px-8 py-4 font-bold text-slate-400 text-[10px] uppercase italic">{{ $t->wilayah }}</td>
                                        <td class="px-8 py-4 text-center">
                                            <span class="text-[10px] font-black text-slate-400 uppercase italic">Lvl {{ $t->level }}</span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="flex items-center text-blue-600 font-black italic">
                                                <span class="text-xs mr-1 opacity-30">Rp</span>
                                                <input type="text" x-model="val" @input="val = formatRupiah($event.target.value)" class="w-full bg-transparent border-none focus:ring-0 p-0 font-black italic text-blue-600">
                                                <input type="hidden" name="tarif[{{ $t->id }}]" :value="val.replace(/\./g, '')">
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <a href="{{ route('marketing.destroy-tarif', $t->id) }}" onclick="return confirm('Hapus?')" class="text-slate-200 hover:text-red-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all">
                                    Simpan Perubahan Nominal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 p-10 sticky top-8">
                        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter italic mb-8">Register Marketing</h3>
                        <form action="{{ route('marketing.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Nama Lengkap</label>
                                <input type="text" name="nama" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-sm shadow-inner focus:ring-2 focus:ring-blue-600 uppercase italic placeholder-slate-300" placeholder="Input Nama..." required>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Nomor Kartu Tol</label>
                                <input type="text" name="no_kartu_tol" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-blue-700 text-sm shadow-inner focus:ring-2 focus:ring-blue-600 italic tracking-widest placeholder-slate-300" placeholder="Contoh: 1234...">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Level Jabatan</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach([1, 2, 3] as $l)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="level" value="{{ $l }}" class="hidden peer" required>
                                        <div class="text-center py-4 rounded-2xl border-2 border-slate-50 bg-slate-50 peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white transition-all font-black text-slate-300 italic">
                                            {{ $l }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-blue-600 transition-all italic text-[10px]">
                                Daftarkan Personel
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="showTarifModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[3rem] shadow-2xl max-w-md w-full p-10 border-t-8 border-blue-600" @click.away="showTarifModal = false">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-black text-slate-800 uppercase italic tracking-tighter">Add Master Tarif</h3>
                    <button @click="showTarifModal = false" class="text-slate-400 hover:text-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('marketing.store-tarif') }}" method="POST" class="space-y-5" x-data="{ n: '' }">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Kategori</label>
                        <input type="text" name="kategori" placeholder="HOTEL / UM" class="w-full rounded-2xl border-none bg-slate-50 p-4 font-black shadow-inner uppercase text-xs italic" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Wilayah</label>
                        <input type="text" name="wilayah" placeholder="JABOTABEK / LAINNYA" class="w-full rounded-2xl border-none bg-slate-50 p-4 font-black shadow-inner uppercase text-xs italic" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Level</label>
                            <select name="level" class="w-full rounded-2xl border-none bg-slate-50 p-4 font-black text-xs appearance-none shadow-inner">
                                <option value="1">Lvl 1</option><option value="2">Lvl 2</option><option value="3">Lvl 3</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-600 uppercase tracking-widest italic ml-1">Nominal (Rp)</label>
                            <input type="text" x-model="n" @input="n = n.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" placeholder="0" class="w-full rounded-2xl border-none bg-blue-50 p-4 font-black italic shadow-inner text-blue-700 text-sm" required>
                            <input type="hidden" name="nominal" :value="n.replace(/\./g, '')">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-widest italic text-[10px] mt-4 shadow-xl shadow-blue-200">
                        SIMPAN MASTER TARIF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
