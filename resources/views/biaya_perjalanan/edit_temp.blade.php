<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border-t-8 border-indigo-500">

                <div class="p-10 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-black uppercase tracking-tighter italic">Edit Draf</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Draf ID: #{{ $temp->id }}</p>
                        </div>
                        <a href="{{ route('biaya-perjalanan.create') }}" class="text-slate-400 hover:text-black font-black text-xs uppercase transition tracking-widest">Kembali ke Input</a>
                    </div>
                </div>

                <form action="{{ route('biaya-perjalanan.updateTemp', $temp->id) }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="level" id="hidden_level" value="{{ $temp->level }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Marketing</label>
                            <select name="marketing_id" id="marketing_id" onchange="updateMarketingLevel()" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm" required>
                                @foreach($marketings as $m)
                                    <option value="{{ $m->id }}" data-level="{{ $m->level }}" {{ $temp->marketing_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ $temp->tanggal }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Customer & CP</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="text" name="customer_nama" value="{{ $temp->customer_nama }}" placeholder="Nama Customer" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm" required>
                            <input type="text" name="customer_cp" value="{{ $temp->customer_cp }}" placeholder="Kontak" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-black uppercase tracking-widest ml-1">Kategori Biaya</label>
                        <select name="kategori" id="kategori" onchange="toggleFormLogic()" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-sm text-indigo-700 uppercase italic" required>
                            @foreach(['Hotel', 'UM', 'Oleh-oleh', 'Cuci Kendaraan', 'Parkir', 'Tambah Angin', 'Lain-lain'] as $kat)
                                <option value="{{ $kat }}" {{ $temp->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="logicBox" class="p-6 bg-indigo-50 rounded-[2rem] border-2 border-dashed border-indigo-200 space-y-4 {{ in_array($temp->kategori, ['Hotel', 'UM']) ? '' : 'hidden' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div>
                                <label class="block text-[10px] font-black text-indigo-700 uppercase mb-3 tracking-widest">Level Marketing</label>
                                <div class="flex gap-4">
                                    @foreach ([1, 2, 3] as $l)
                                        <label class="flex items-center opacity-50 cursor-not-allowed">
                                            <input type="radio" id="radio_view_level_{{ $l }}" disabled {{ $temp->level == $l ? 'checked' : '' }} class="text-indigo-600">
                                            <span class="ml-2 text-xs font-black text-black uppercase">Lvl {{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-indigo-700 uppercase mb-2 tracking-widest">Wilayah</label>
                                <select name="wilayah" class="w-full rounded-xl border-none bg-white     p-3 font-black text-xs uppercase shadow-sm">
                                    <option value="Jabotabek" {{ $temp->wilayah == 'Jabotabek' ? 'selected' : '' }}>Jabotabek & Luar Pulau</option>
                                    <option value="Lainnya" {{ $temp->wilayah == 'Lainnya' ? 'selected' : '' }}>Lainnya (Jawa)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Nominal</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-blue-300 text-xl">Rp</span>
                            <input type="text" id="display_nominal" value="{{ number_format($temp->nominal, 0, ',', '.') }}"
                                class="w-full border-none rounded-3xl p-8 pl-16 font-black text-4xl text-blue-700 bg-blue-50"
                                {{ in_array($temp->kategori, ['Hotel', 'UM']) ? 'readonly' : '' }}>
                            <input type="hidden" name="nominal" value="{{ $temp->nominal }}">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-6 bg-indigo-500 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-600 transition-all text-lg">
                        Simpan Perubahan Draf
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tambahkan script toggleFormLogic dan updateMarketingLevel di sini
        // sama seperti di halaman edit/create sebelumnya
    </script>
</x-app-layout>
