<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1600px] mx-auto px-4">

            {{-- HEADER & FILTER --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl p-10 mb-10 border border-slate-200">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Rekapitulasi Sistem
                        Digitalisasi Starindo</h2>
                </div>

                <form action="{{ route('rekap.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Pilih
                            Marketing</label>
                        <select name="marketing_id"
                            class="w-full border-none bg-slate-100 rounded-2xl font-black text-xs p-4 focus:ring-2 focus:ring-slate-900"
                            required>
                            <option value="">-- PILIH PERSONEL --</option>
                            @foreach ($marketings as $m)
                                <option value="{{ $m->id }}" {{ $marketingId == $m->id ? 'selected' : '' }}>
                                    {{ strtoupper($m->nama) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Dari
                            Tanggal</label>
                        <input type="date" name="from" value="{{ $from }}"
                            class="w-full border-none bg-slate-100 rounded-2xl font-black text-xs p-4">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Sampai
                            Tanggal</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="w-full border-none bg-slate-100 rounded-2xl font-black text-xs p-4">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all shadow-xl">
                            Tampilkan Rekap
                        </button>
                    </div>
                </form>
            </div>

            @if ($data)
                {{-- SUMMARY CARDS --}}
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
                    {{-- 1. TOTAL OMSET --}}
                    <div class="bg-slate-900 p-6 rounded-[2.5rem] shadow-xl text-white">
                        <p class="text-[9px] font-black uppercase tracking-widest opacity-60 mb-2">Total Omset</p>
                        <p class="text-2xl font-black tracking-tighter">
                            Rp {{ number_format($data->summary['total_omset'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- 2. TOTAL BIAYA PERJALANAN --}}
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-slate-200">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Biaya Jalan
                        </p>
                        <p class="text-2xl font-black text-red-600 tracking-tighter">
                            Rp {{ number_format($data->summary['total_biaya'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- 3. TOTAL PENGAJUAN --}}
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-slate-200">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Pengajuan
                        </p>
                        <p class="text-2xl font-black text-purple-600 tracking-tighter">
                            Rp {{ number_format($data->summary['total_pengajuan'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- 4. LABA BERSIH --}}
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-slate-200">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Laba Bersih</p>
                        <p
                            class="text-2xl font-black {{ $data->summary['laba_bersih'] < 0 ? 'text-red-600' : 'text-emerald-500' }} tracking-tighter">
                            Rp {{ number_format($data->summary['laba_bersih'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- 5. KARTU PROSENTASE (CARD SENDIRI) --}}
                    <div
                        class="p-6 rounded-[2.5rem] shadow-xl text-white {{ $data->summary['persen_keuntungan'] < 0 ? 'bg-red-600' : 'bg-blue-600' }}">
                        <p class="text-[9px] font-black uppercase tracking-widest opacity-60 mb-2">Prosentase Laba</p>
                        <p class="text-4xl font-black tracking-tighter">
                            {{ number_format($data->summary['persen_keuntungan'], 2, ',', '.') }}%
                        </p>
                    </div>
                </div>

                <div class="space-y-10 pb-20">

                    {{-- TABEL 0: OMSET MARKETING --}}
                    <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden border border-slate-200">
                        <div
                            class="px-8 py-5 bg-indigo-50 border-b border-indigo-100 font-black text-indigo-800 uppercase text-[10px] tracking-widest">
                            Omset Marketing dari {{ date('d/m/Y', strtotime($from)) }} sampai
                            {{ date('d/m/Y', strtotime($to)) }}</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[11px]">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest">
                                    <tr>
                                        <th class="py-4 px-8">Periode Dari</th>
                                        <th class="py-4 px-6">Periode Sampai</th>
                                        <th class="py-4 px-6">Nominal</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                                    @foreach ($data->omsets as $omsets)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-4 px-8 text-slate-400">
                                                {{ date('d/m/Y', strtotime($omsets->periode_dari)) }}</td>
                                            <td class="py-4 px-6 uppercase">
                                                {{ date('d/m/Y', strtotime($omsets->periode_sampai)) }}</td>
                                            <td class="py-4 px-6 uppercase text-indigo-600">Rp
                                                {{ number_format($omsets->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-8 py-3 bg-slate-50">{{ $data->omsets->links() }}</div>
                    </div>

                    {{-- TABEL 1: AKOMODASI --}}
                    <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden border border-slate-200">
                        <div
                            class="px-8 py-5 bg-sky-50 border-b border-sky-100 font-black text-sky-800 uppercase text-[10px] tracking-widest">
                            Detail Biaya Hotel & UM</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[11px]">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest">
                                    <tr>
                                        <th class="py-4 px-8">Tanggal</th>
                                        <th class="py-4 px-6">Customer</th>
                                        <th class="py-4 px-6">CP</th>
                                        <th class="py-4 px-6">Kategori</th>
                                        <th class="py-4 px-6 text-center">Level</th>
                                        <th class="py-4 px-6">Wilayah</th>
                                        <th class="py-4 px-6 text-center">Durasi</th>
                                        <th class="py-4 px-8 text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                                    @foreach ($data->akomodasi as $ak)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-4 px-8 text-slate-400">
                                                {{ date('d/m/Y', strtotime($ak->tanggal)) }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $ak->customer_nama }}</td>
                                            <td class="py-4 px-6 uppercase text-slate-500">{{ $ak->customer_cp }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $ak->kategori }}</td>
                                            <td class="py-4 px-6 text-center">{{ $ak->level }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $ak->wilayah }}</td>
                                            <td class="py-4 px-6 text-center">{{ $ak->durasi }} Hari</td>
                                            <td class="py-4 px-8 text-right text-slate-900 font-black">Rp
                                                {{ number_format($ak->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-8 py-3 bg-slate-50">{{ $data->akomodasi->links() }}</div>
                    </div>

                    {{-- TABEL 2: BENSIN --}}
                    <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden border border-slate-200">
                        <div
                            class="px-8 py-5 bg-emerald-50 border-b border-emerald-100 font-black text-emerald-800 uppercase text-[10px] tracking-widest">
                            Detail Pengisian Bensin</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[11px]">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest">
                                    <tr>
                                        <th class="py-4 px-8">Tanggal</th>
                                        <th class="py-4 px-6">Customer</th>
                                        <th class="py-4 px-6">CP</th>
                                        <th class="py-4 px-6">Kategori</th>
                                        <th class="py-4 px-6 text-center">KM</th>
                                        <th class="py-4 px-6">Keterangan</th>
                                        <th class="py-4 px-8 text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                                    @foreach ($data->bensin as $b)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-4 px-8 text-slate-400">
                                                {{ date('d/m/Y', strtotime($b->tanggal)) }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $b->customer_nama }}</td>
                                            <td class="py-4 px-6 uppercase text-slate-500">{{ $b->customer_cp }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $b->kategori }}</td>
                                            <td class="py-4 px-6 text-center text-emerald-600 font-black">
                                                {{ number_format($b->km, 0, ',', '.') }} KM</td>
                                            <td class="py-4 px-6 uppercase text-slate-400 text-[10px]">
                                                {{ $b->keterangan ?? '-' }}</td>
                                            <td class="py-4 px-8 text-right text-slate-900 font-black">Rp
                                                {{ number_format($b->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-8 py-3 bg-slate-50">{{ $data->bensin->links() }}</div>
                    </div>

                    {{-- TABEL 3: TOL --}}
                    <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden border border-slate-200">
                        <div
                            class="px-8 py-5 bg-amber-50 border-b border-amber-100 font-black text-amber-800 uppercase text-[10px] tracking-widest">
                            Detail Penggunaan Tol</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[11px]">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest">
                                    <tr>
                                        <th class="py-4 px-8">Tanggal</th>
                                        <th class="py-4 px-6">Customer</th>
                                        <th class="py-4 px-6">Kategori</th>
                                        <th class="py-4 px-6">Keterangan</th>
                                        <th class="py-4 px-8 text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                                    @foreach ($data->tol as $t)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-4 px-8 text-slate-400">
                                                {{ date('d/m/Y', strtotime($t->tanggal)) }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $t->customer_nama }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $t->kategori }}</td>
                                            <td class="py-4 px-6 uppercase text-slate-400 text-[10px]">
                                                {{ $t->keterangan ?? '-' }}</td>
                                            <td class="py-4 px-8 text-right text-slate-900 font-black">Rp
                                                {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-8 py-3 bg-slate-50">{{ $data->tol->links() }}</div>
                    </div>

                    {{-- TABEL 4: OPERASIONAL --}}
                    <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden border border-slate-200">
                        <div
                            class="px-8 py-5 bg-indigo-50 border-b border-indigo-100 font-black text-indigo-800 uppercase text-[10px] tracking-widest">
                            Biaya Operasional Lainnya</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[11px]">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest">
                                    <tr>
                                        <th class="py-4 px-8">Tanggal</th>
                                        <th class="py-4 px-6">Customer</th>
                                        <th class="py-4 px-6">Kategori</th>
                                        <th class="py-4 px-6">Keterangan</th>
                                        <th class="py-4 px-8 text-right">Nominal</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                                    @foreach ($data->operasional as $op)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-4 px-8 text-slate-400">
                                                {{ date('d/m/Y', strtotime($op->tanggal)) }}</td>
                                            <td class="py-4 px-6 uppercase">{{ $op->customer_nama }}</td>
                                            <td class="py-4 px-6 uppercase text-indigo-600">{{ $op->kategori }}</td>
                                            <td class="py-4 px-6 uppercase text-slate-400 text-[10px]">
                                                {{ $op->keterangan ?? '-' }}</td>
                                            <td class="py-4 px-8 text-right text-slate-900 font-black">Rp
                                                {{ number_format($op->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-8 py-3 bg-slate-50">{{ $data->operasional->links() }}</div>
                    </div>



                </div>
            @else
                {{-- EMPTY STATE (BAGIAN TENGAH SAAT BELUM FILTER) --}}
                <div
                    class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-sm border-4 border-dashed border-slate-100">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-400 uppercase tracking-tighter">Data Belum Dipanggil</h3>
                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mt-2">Silakan pilih nama
                        marketing dan periode tanggal di atas</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
