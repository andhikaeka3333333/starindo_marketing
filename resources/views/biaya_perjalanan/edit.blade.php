    <x-app-layout>
        <div class="py-12 bg-white min-h-screen">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-8 border-indigo-600">

                    <div class="p-10 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-black text-black uppercase tracking-tighter italic">Update Record</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Editing Biaya ID: #{{ $biaya->id }}</p>
                            </div>
                            <a href="{{ route('biaya-perjalanan.index') }}" class="text-slate-400 hover:text-black font-black text-xs uppercase transition tracking-widest">Back to List</a>
                        </div>
                    </div>

                    <form action="{{ route('biaya-perjalanan.update', $biaya->id) }}" method="POST" class="p-10 space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="level" id="hidden_level" value="{{ $biaya->level }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Marketing Pemohon</label>
                                <select name="marketing_id" id="marketing_id" onchange="updateMarketingLevel()"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-black focus:ring-2 focus:ring-indigo-600 shadow-inner" required>
                                    @foreach($marketings as $m)
                                        <option value="{{ $m->id }}" data-level="{{ $m->level }}" {{ $biaya->marketing_id == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Tanggal Transaksi</label>
                                <input type="date" name="tanggal" value="{{ $biaya->tanggal }}"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-black focus:ring-2 focus:ring-indigo-600 shadow-inner" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Nama Customer & CP</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input type="text" name="customer_nama" value="{{ $biaya->customer_nama }}" placeholder="Nama Customer"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-black focus:ring-2 focus:ring-indigo-600 shadow-inner" required>
                                <input type="text" name="customer_cp" value="{{ $biaya->customer_cp }}" placeholder="Kontak"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-black focus:ring-2 focus:ring-indigo-600 shadow-inner">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Kategori Biaya</label>
                            <select name="kategori" id="kategori" onchange="toggleFormLogic()"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-indigo-700 uppercase italic focus:ring-2 focus:ring-indigo-600 shadow-inner" required>
                                @foreach(['Hotel', 'UM', 'Oleh-oleh', 'Cuci Kendaraan', 'Parkir', 'Tambah Angin', 'Lain-lain'] as $kat)
                                    <option value="{{ $kat }}" {{ $biaya->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="logicBox" class="p-6 bg-indigo-50 rounded-[2rem] border-2 border-dashed border-indigo-200 space-y-4 {{ in_array($biaya->kategori, ['Hotel', 'UM']) ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <div>
                                    <label class="block text-[10px] font-black text-indigo-700 uppercase mb-3 tracking-widest">Level Marketing (Fixed)</label>
                                    <div class="flex gap-4">
                                        @foreach ([1, 2, 3] as $l)
                                            <label class="flex items-center opacity-50 cursor-not-allowed">
                                                <input type="radio" id="radio_view_level_{{ $l }}" disabled {{ $biaya->level == $l ? 'checked' : '' }}
                                                    class="text-indigo-600 border-indigo-300">
                                                <span class="ml-2 text-xs font-black text-black uppercase">Lvl {{ $l }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-indigo-700 uppercase mb-2 tracking-widest">Update Wilayah</label>
                                    <select name="wilayah" class="w-full rounded-xl border-none bg-white p-3 font-black text-xs text-black uppercase shadow-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="Jabotabek" {{ $biaya->wilayah == 'Jabotabek' ? 'selected' : '' }}>Jabotabek & Luar Pulau</option>
                                        <option value="Lainnya" {{ $biaya->wilayah == 'Lainnya' ? 'selected' : '' }}>Lainnya (Jawa)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="nominalContainer"
                            x-data="{
                                displayValue: '',
                                rawValue: '{{ (int)$biaya->nominal }}',
                                formatRupiah(val) {
                                    if (!val || val == 0) return '';
                                    return new Intl.NumberFormat('id-ID').format(val);
                                },
                                updateValue(e) {
                                    let val = e.target.value.replace(/\D/g, '');
                                    this.rawValue = val;
                                    this.displayValue = this.formatRupiah(val);
                                }
                            }"
                            x-init="displayValue = formatRupiah(rawValue)"
                            class="space-y-2">

                            <label id="nominalLabel" class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">
                                {{ in_array($biaya->kategori, ['Hotel', 'UM']) ? 'Nominal (Otomatis Sesuai Level)' : 'Nominal Rupiah (Manual)' }}
                            </label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-blue-300 text-xl">Rp</span>

                                <input type="text"
                                    x-model="displayValue"
                                    @input="updateValue($event)"
                                    id="inputTampilanNominal"
                                    :readonly="'{{ in_array($biaya->kategori, ['Hotel', 'UM']) }}' == '1'"
                                    :class="'{{ in_array($biaya->kategori, ['Hotel', 'UM']) }}' == '1' ? 'bg-slate-100 opacity-70 cursor-not-allowed' : 'bg-blue-50'"
                                    class="w-full border-none rounded-3xl p-8 pl-16 font-black text-4xl text-blue-700 focus:ring-2 focus:ring-blue-600 shadow-inner transition-all">

                                <input type="hidden" name="nominal" x-model="rawValue" required>
                            </div>
                            <p id="nominalNote" class="text-[9px] font-bold text-slate-400 uppercase ml-2 {{ in_array($biaya->kategori, ['Hotel', 'UM']) ? '' : 'hidden' }}">
                                * Untuk Hotel/UM, nominal akan dihitung ulang saat simpan berdasarkan Level & Wilayah.
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-black focus:ring-2 focus:ring-indigo-600 shadow-inner"
                                placeholder="Tulis alamat lengkap customer di sini...">{{ $biaya->alamat }}</textarea>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 py-6 bg-indigo-600 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:scale-[1.01] transition-all italic text-lg">
                                Update Data Perjalanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function toggleFormLogic() {
                const kategori = document.getElementById('kategori').value;
                const logicBox = document.getElementById('logicBox');
                const inputNominal = document.getElementById('inputTampilanNominal');
                const labelNominal = document.getElementById('nominalLabel');
                const noteNominal = document.getElementById('nominalNote');

                if (kategori === 'Hotel' || kategori === 'UM') {
                    logicBox.classList.remove('hidden');
                    inputNominal.classList.add('bg-slate-100', 'opacity-70', 'cursor-not-allowed');
                    inputNominal.classList.remove('bg-blue-50');
                    inputNominal.readOnly = true;
                    labelNominal.innerText = 'Nominal (Otomatis Sesuai Level)';
                    noteNominal.classList.remove('hidden');
                } else {
                    logicBox.classList.add('hidden');
                    inputNominal.classList.remove('bg-slate-100', 'opacity-70', 'cursor-not-allowed');
                    inputNominal.classList.add('bg-blue-50');
                    inputNominal.readOnly = false;
                    labelNominal.innerText = 'Nominal Rupiah (Manual)';
                    noteNominal.classList.add('hidden');
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
                        const radio = document.getElementById('radio_view_level_' + i);
                        if (radio) radio.checked = (i == level);
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleFormLogic();
            });
        </script>

        <style>
            input, select, textarea { color: #000000 !important; }
        </style>
    </x-app-layout>
