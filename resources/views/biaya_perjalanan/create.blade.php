<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tighter uppercase">Biaya Perjalanan Starindo</h2>
        </div>
    </x-slot>

    <div class="max-w-[1700px] mx-auto py-8 px-4">

        <div class="max-w-2xl mx-auto flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-indigo-900 uppercase tracking-tighter">Input Biaya</h1>
            </div>
            <a href="{{ route('biaya-perjalanan.index') }}"
                class="bg-white border border-indigo-200 text-indigo-600 font-bold py-2 px-4 rounded-xl hover:bg-indigo-50 flex items-center text-xs transition shadow-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m7-7l-7 7 7 7"></path>
                </svg>
                Data Final
            </a>
        </div>

        @if (session('success'))
            <div class="max-w-2xl mx-auto mb-6 p-4 bg-indigo-100 border-l-4 border-indigo-600 text-indigo-800 rounded-r-xl shadow-sm italic font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] shadow-xl border border-indigo-50 overflow-hidden mb-12">
            <div class="bg-indigo-600 py-4 px-8 flex justify-between items-center text-white">
                <h3 class="font-black italic uppercase text-xs tracking-widest">Formulir Entry Draf</h3>
            </div>

            <form action="{{ route('biaya-perjalanan.storeTemp') }}" method="POST" class="p-8 space-y-6" id="formBiaya">
                @csrf
                <input type="hidden" name="level" id="hidden_level" value="1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">Marketing</label>
                        <select name="marketing_id" id="marketing_id" onchange="updateMarketingLevel()"
                            class="w-full rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 font-bold text-slate-700 p-3" required>
                            <option value="" data-level="">-- Pilih Nama --</option>
                            @foreach ($marketings as $m)
                                <option value="{{ $m->id }}" data-level="{{ $m->level }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 font-bold text-slate-600 p-3" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">Customer</label>
                        <input type="text" name="customer_nama" placeholder="Nama Customer"
                            class="w-full rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 font-bold p-3" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">CP Customer</label>
                        <input type="text" name="customer_cp" placeholder="0856..."
                            class="w-full rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 font-bold p-3">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">Kategori Biaya</label>
                    <select name="kategori" id="kategori" onchange="toggleFormLogic()"
                        class="w-full rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 font-black text-indigo-600 uppercase italic p-3" required>
                        <option value="Hotel">Hotel</option>
                        <option value="UM">Uang Makan (UM)</option>
                        <option value="Oleh-oleh">Oleh-oleh</option>
                        <option value="Cuci Kendaraan">Cuci Kendaraan</option>
                        <option value="Parkir">Parkir</option>
                        <option value="Tambah Angin">Tambah Angin</option>
                        <option value="Lain-lain">Lain-lain</option>

                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">Alamat / Keterangan</label>
                    <input type="text" name="alamat" placeholder="Alamat Lengkap..."
                        class="w-full rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 text-sm p-3">
                </div>

                <div id="logicBox" class="p-5 bg-indigo-50 rounded-2xl border-2 border-dashed border-indigo-200 space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-indigo-700 uppercase mb-2 tracking-widest text-center">Level Marketing (Auto)</label>
                        <div class="flex justify-center gap-4">
                            @foreach ([1, 2, 3] as $l)
                                <label class="flex items-center opacity-50 cursor-not-allowed">
                                    <input type="radio" id="radio_view_level_{{ $l }}" disabled class="text-indigo-400">
                                    <span class="ml-1 text-[10px] font-black text-indigo-900 uppercase">Lvl {{ $l }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-indigo-700 uppercase mb-2 tracking-widest">Wilayah Perjalanan</label>
                        <select name="wilayah" class="w-full rounded-lg border-gray-300 text-xs font-black text-indigo-900 uppercase p-2">
                            <option value="Jabotabek">Jabotabek & Luar Pulau</option>
                            <option value="Lainnya">Lainnya (Jatim, Jateng, Jabar)</option>
                        </select>
                    </div>
                </div>

                <div id="manualNominal" class="hidden space-y-2">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest ml-1">Nominal Manual (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 font-black italic">Rp</span>
                        <input type="number" name="nominal" placeholder="0"
                            class="w-full pl-12 rounded-xl border-gray-200 bg-slate-50 focus:ring-indigo-500 font-black text-indigo-900 text-lg p-3">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-900 text-white font-black py-4 rounded-2xl shadow-xl transition-all uppercase tracking-widest italic text-sm">
                    + Tambahkan ke Draf
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] shadow-2xl border border-indigo-100 overflow-hidden">
            <div class="bg-slate-800 px-8 py-5 flex justify-between items-center text-white">
                <h3 class="text-indigo-400 font-black uppercase text-xs italic tracking-widest">Data Sementara</h3>
                <span class="bg-indigo-600 text-white text-[10px] px-4 py-1 rounded-full font-black uppercase shadow-lg">
                    {{ $temps->count() }} Items
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">Tanggal</th>
                            <th class="px-6 py-4 text-left">Marketing</th>
                            <th class="px-6 py-4 text-left">Customer</th>
                            <th class="px-6 py-4 text-left">CP</th>
                            <th class="px-6 py-4 text-left">Alamat</th>
                            <th class="px-6 py-4 text-left">Kategori</th>
                            <th class="px-6 py-4 text-left">Level</th>
                            <th class="px-6 py-4 text-left">Wilayah</th>
                            <th class="px-8 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($temps as $t)
                            <tr class="hover:bg-indigo-50/50 transition duration-150">
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-400">{{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                                <td class="px-6 py-4 text-xs font-black text-slate-800 uppercase italic">{{ $t->marketing->nama }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-indigo-600 uppercase">{{ $t->customer_nama }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">{{ $t->customer_cp ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 truncate max-w-[150px]">{{ $t->alamat ?? '-' }}</td>
                                <td class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">{{ $t->kategori }}</td>
                                <td class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase">{{ $t->level ? 'Lvl ' . $t->level : '-' }}</td>
                                <td class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">{{ $t->wilayah ?? '-' }}</td>
                                <td class="px-8 py-4 text-right font-black text-indigo-900 italic tracking-tight text-sm">
                                    Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <a href="{{ route('biaya-perjalanan.editTemp', $t->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('biaya-perjalanan.destroyTemp', $t->id) }}" method="POST" onsubmit="return confirm('Hapus draf?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-20 text-center text-slate-300 italic text-sm font-black uppercase opacity-50 tracking-widest">Antrean Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($temps->isNotEmpty())
                <div class="p-8 bg-indigo-900 flex flex-col md:flex-row justify-between items-center gap-6 border-t border-indigo-800 shadow-inner">
                    <div class="text-center md:text-left">
                        <p class="text-indigo-300 text-[10px] font-black uppercase tracking-widest mb-1 opacity-70">Total Akumulasi Draf:</p>
                        <h2 class="text-4xl font-black text-white italic leading-none tracking-tighter">Rp {{ number_format($temps->sum('nominal'), 0, ',', '.') }}</h2>
                    </div>
                    <form action="{{ route('biaya-perjalanan.finalize') }}" method="POST" onsubmit="return confirm('Simpan permanen?')">
                        @csrf
                        <button type="submit" class="bg-white text-indigo-900 hover:bg-blue-500 hover:text-white font-black py-4 px-12 rounded-2xl shadow-2xl transition duration-500 transform hover:scale-105 uppercase tracking-tighter text-sm">
                            Simpan Permanen ke Tabel utama
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleFormLogic() {
            const kategori = document.getElementById('kategori').value;
            const logicBox = document.getElementById('logicBox');
            const manualNominal = document.getElementById('manualNominal');

            if (kategori === 'Hotel' || kategori === 'UM') {
                logicBox.classList.remove('hidden');
                manualNominal.classList.add('hidden');
            } else {
                logicBox.classList.add('hidden');
                manualNominal.classList.remove('hidden');
            }
        }

        function updateMarketingLevel() {
            const select = document.getElementById('marketing_id');
            const selectedOption = select.options[select.selectedIndex];
            const level = selectedOption.getAttribute('data-level');
            const hiddenInput = document.getElementById('hidden_level');

            if (level) {
                hiddenInput.value = level;
                for (let i = 1; i <= 3; i++) {
                    document.getElementById('radio_view_level_' + i).checked = false;
                }
                const targetRadio = document.getElementById('radio_view_level_' + level);
                if (targetRadio) targetRadio.checked = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleFormLogic();
            updateMarketingLevel();
        });

        document.getElementById('formBiaya').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = `MEMPROSES...`;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    </script>
</x-app-layout>
