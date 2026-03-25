<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tighter uppercase">Monitoring Biaya Perjalanan</h2>
            <a href="{{ route('biaya-perjalanan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ tab: '{{ request('tab', 'akom') }}' }">
        <div class="max-w-[1400px] mx-auto px-4">

            @if(session('success'))
                <div class="mb-6 p-4 bg-white border-l-8 border-emerald-500 shadow-lg rounded-2xl text-slate-800 font-black uppercase text-xs tracking-widest">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8 max-w-2xl">
                <form action="{{ url()->current() }}" method="GET" class="flex gap-3">
                    <input type="hidden" name="tab" :value="tab">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Marketing, Customer, atau Kategori..." class="block w-full pl-6 pr-4 py-3 border-none bg-white rounded-2xl text-sm shadow-sm focus:ring-2 focus:ring-indigo-50 transition-all font-bold">
                    <button type="submit" class="bg-slate-800 text-white px-8 py-3 rounded-2xl text-xs font-black uppercase shadow-lg">Cari</button>
                    @if(request('search'))
                        <a href="{{ url()->current() }}?tab={{ request('tab', 'akom') }}" class="flex items-center text-slate-400 hover:text-red-600 transition-all px-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-200">

                <div class="flex items-center bg-slate-100 border-b border-slate-200 pt-3 px-8 gap-1 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button @click="tab = 'akom'; window.history.replaceState(null, null, '?tab=akom&search={{ request('search') }}')"
                        :class="tab === 'akom' ? 'bg-white border-l border-r border-t border-slate-200 text-indigo-600 font-black rounded-t-2xl -mb-[1px]' : 'text-slate-400 hover:text-indigo-600 font-bold'"
                        class="px-8 py-4 text-[10px] uppercase tracking-widest transition-all">
                        Hotel & UM ({{ $akomodasi->total() }})
                    </button>

                    <button @click="tab = 'topup_tol'; window.history.replaceState(null, null, '?tab=topup_tol&search={{ request('search') }}')"
                        :class="tab === 'topup_tol' ? 'bg-white border-l border-r border-t border-slate-200 text-amber-600 font-black rounded-t-2xl -mb-[1px]' : 'text-slate-400 hover:text-amber-600 font-bold'"
                        class="px-8 py-4 text-[10px] uppercase tracking-widest transition-all">
                        Top-Up Tol ({{ $topup_tol->total() }})
                    </button>

                    <button @click="tab = 'pakai_tol'; window.history.replaceState(null, null, '?tab=pakai_tol&search={{ request('search') }}')"
                        :class="tab === 'pakai_tol' ? 'bg-white border-l border-r border-t border-slate-200 text-orange-600 font-black rounded-t-2xl -mb-[1px]' : 'text-slate-400 hover:text-orange-600 font-bold'"
                        class="px-8 py-4 text-[10px] uppercase tracking-widest transition-all">
                        Pemakaian Tol ({{ $pemakaian_tol->total() }})
                    </button>

                    <button @click="tab = 'bensin'; window.history.replaceState(null, null, '?tab=bensin&search={{ request('search') }}')"
                        :class="tab === 'bensin' ? 'bg-white border-l border-r border-t border-slate-200 text-emerald-600 font-black rounded-t-2xl -mb-[1px]' : 'text-slate-400 hover:text-emerald-600 font-bold'"
                        class="px-8 py-4 text-[10px] uppercase tracking-widest transition-all">
                        Bensin ({{ $bensin->total() }})
                    </button>
                    <button @click="tab = 'oper'; window.history.replaceState(null, null, '?tab=oper&search={{ request('search') }}')"
                        :class="tab === 'oper' ? 'bg-white border-l border-r border-t border-slate-200 text-indigo-600 font-black rounded-t-2xl -mb-[1px]' : 'text-slate-400 hover:text-indigo-600 font-bold'"
                        class="px-8 py-4 text-[10px] uppercase tracking-widest transition-all">
                        Lainnya ({{ $operasional->total() }})
                    </button>
                </div>

                <div class="p-8 overflow-x-auto">

                    <div x-show="tab === 'akom'">
                        <table class="w-full text-left text-[11px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-4">Tgl</th><th class="py-4 px-4">Marketing</th><th class="py-4 px-4">Customer</th><th class="py-4 px-4 text-indigo-500">Kontak</th><th class="py-4 px-4">Wilayah</th><th class="py-4 px-4">Kategori</th><th class="py-4 px-4 text-center">Durasi</th><th class="py-4 px-4 text-right">IDR Value</th><th class="py-4 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($akomodasi as $a)
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ date('d/m/y', strtotime($a->tanggal)) }}</td>
                                        <td class="py-5 px-4 font-black uppercase">{{ $a->marketing->nama ?? 'N/A' }}</td>
                                        <td class="py-5 px-4 font-black text-indigo-600 uppercase">{{ $a->customer_nama }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ $a->customer_cp ?? '-' }}</td>
                                        <td class="py-5 px-4 uppercase font-medium text-slate-500">{{ $a->wilayah }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-700 uppercase">{{ $a->kategori }}</td>
                                        <td class="py-5 px-4 text-center font-bold text-slate-600">{{ $a->durasi }} Hari</td>
                                        <td class="py-5 px-4 text-right font-black text-sm text-slate-900">Rp {{ number_format($a->nominal, 0, ',', '.') }}</td>
                                        <td class="py-5 px-4">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('biaya-perjalanan.edit', ['akomodasi', $a->id]) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('biaya-perjalanan.destroy', ['akomodasi', $a->id]) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest">Data Akomodasi Kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-6">{{ $akomodasi->appends(['tab' => 'akom', 'search' => request('search')])->links() }}</div>
                    </div>

                    <div x-show="tab === 'topup_tol'" x-cloak>
                        <table class="w-full text-left text-[11px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-4">Tgl</th><th class="py-4 px-4">Marketing</th><th class="py-4 px-4 text-amber-600">Kartu Tol</th><th class="py-4 px-4">Customer</th><th class="py-4 px-4">Kontak</th><th class="py-4 px-4">Kategori</th><th class="py-4 px-4">Keterangan</th><th class="py-4 px-4 text-right">IDR Value</th><th class="py-4 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($topup_tol as $t)
                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ date('d/m/y H:i', strtotime($t->tanggal)) }}</td>
                                        <td class="py-5 px-4 font-black uppercase">{{ $t->marketing->nama ?? 'N/A' }}</td>
                                        <td class="py-5 px-4 font-black text-amber-800 tracking-tighter">{{ $t->marketing->no_kartu_tol ?? '-' }}</td>
                                        <td class="py-5 px-4 font-black text-slate-800 uppercase">{{ $t->customer_nama }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ $t->customer_cp ?? '-' }}</td>
                                        <td class="py-5 px-4 font-bold text-amber-600 uppercase">{{ $t->kategori }}</td>
                                        <td class="py-5 px-4 max-w-[150px] truncate uppercase font-medium text-slate-500">{{ $t->keterangan ?? '-' }}</td>
                                        <td class="py-5 px-4 text-right font-black text-sm text-slate-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-5 px-4 text-right">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('biaya-perjalanan.edit', ['tol', $t->id]) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('biaya-perjalanan.destroy', ['tol', $t->id]) }}" method="POST" onsubmit="return confirm('Hapus data top up?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest">Data Top-Up Tol Kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-6">{{ $topup_tol->appends(['tab' => 'topup_tol', 'search' => request('search')])->links() }}</div>
                    </div>

                    <div x-show="tab === 'pakai_tol'" x-cloak>
                        <table class="w-full text-left text-[11px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-4">Tgl</th><th class="py-4 px-4">Marketing</th><th class="py-4 px-4 text-orange-600">Gerbang Tol</th><th class="py-4 px-4">Customer</th><th class="py-4 px-4">Kontak</th><th class="py-4 px-4">Kategori</th><th class="py-4 px-4">Keterangan</th><th class="py-4 px-4 text-right">IDR Value</th><th class="py-4 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($pemakaian_tol as $t)
                                    <tr class="hover:bg-orange-50/30 transition-colors">
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ date('d/m/y H:i', strtotime($t->tanggal)) }}</td>
                                        <td class="py-5 px-4 font-black uppercase">{{ $t->marketing->nama ?? 'N/A' }}</td>
                                        <td class="py-5 px-4 font-black text-orange-800 tracking-tighter decoration-orange-200">{{ $t->nama_gerbang ?? '-' }}</td>
                                        <td class="py-5 px-4 font-black text-slate-800 uppercase">{{ $t->customer_nama }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ $t->customer_cp ?? '-' }}</td>
                                        <td class="py-5 px-4 font-bold text-orange-600 uppercase">{{ $t->kategori }}</td>
                                        <td class="py-5 px-4 max-w-[150px] truncate uppercase font-medium text-slate-500">{{ $t->keterangan ?? '-' }}</td>
                                        <td class="py-5 px-4 text-right font-black text-sm text-slate-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                        <td class="py-5 px-4 text-right">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('biaya-perjalanan.edit', ['tol', $t->id]) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('biaya-perjalanan.destroy', ['tol', $t->id]) }}" method="POST" onsubmit="return confirm('Hapus data pemakaian tol?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest">Data Pemakaian Tol Kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-6">{{ $pemakaian_tol->appends(['tab' => 'pakai_tol', 'search' => request('search')])->links() }}</div>
                    </div>

                    <div x-show="tab === 'bensin'" x-cloak>
                        <table class="w-full text-left text-[11px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-4">Tgl</th><th class="py-4 px-4">Marketing</th><th class="py-4 px-4">Customer</th><th class="py-4 px-4">Kontak</th><th class="py-4 px-4 text-center text-emerald-600">KM</th><th class="py-4 px-4">Keterangan</th><th class="py-4 px-4 text-right">IDR Value</th><th class="py-4 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($bensin as $b)
                                    <tr class="hover:bg-emerald-50/30 transition-colors">
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ date('d/m/y', strtotime($b->tanggal)) }}</td>
                                        <td class="py-5 px-4 font-black uppercase">{{ $b->marketing->nama ?? 'N/A' }}</td>
                                        <td class="py-5 px-4 font-black text-slate-800 uppercase">{{ $b->customer_nama }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ $b->customer_cp ?? '-' }}</td>
                                        <td class="py-5 px-4 text-center font-black text-emerald-700">{{ number_format($b->km, 0, ',', '.') }} KM</td>
                                        <td class="py-5 px-4 max-w-[200px] truncate uppercase font-medium text-slate-500">{{ $b->keterangan ?? '-' }}</td>
                                        <td class="py-5 px-4 text-right font-black text-sm text-slate-900">Rp {{ number_format($b->nominal, 0, ',', '.') }}</td>
                                        <td class="py-5 px-4 text-right">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('biaya-perjalanan.edit', ['bensin', $b->id]) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('biaya-perjalanan.destroy', ['bensin', $b->id]) }}" method="POST" onsubmit="return confirm('Hapus data bensin?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest">Data Bensin Kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-6">{{ $bensin->appends(['tab' => 'bensin', 'search' => request('search')])->links() }}</div>
                    </div>

                    <div x-show="tab === 'oper'" x-cloak>
                        <table class="w-full text-left text-[11px] whitespace-nowrap table-auto">
                            <thead class="text-slate-400 uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-4">Tgl</th><th class="py-4 px-4">Marketing</th><th class="py-4 px-4">Customer</th><th class="py-4 px-4">Kontak</th><th class="py-4 px-4">Kategori</th><th class="py-4 px-4">Keterangan</th><th class="py-4 px-4 text-right">IDR Value</th><th class="py-4 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($operasional as $o)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ date('d/m/y', strtotime($o->tanggal)) }}</td>
                                        <td class="py-5 px-4 font-black uppercase">{{ $o->marketing->nama ?? 'N/A' }}</td>
                                        <td class="py-5 px-4 font-black text-slate-800 uppercase">{{ $o->customer_nama }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-400">{{ $o->customer_cp ?? '-' }}</td>
                                        <td class="py-5 px-4"><span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full font-black text-[9px] uppercase border border-slate-200">{{ $o->kategori }}</span></td>
                                        <td class="py-5 px-4 max-w-[200px] truncate uppercase font-medium text-slate-500">{{ $o->keterangan ?? '-' }}</td>
                                        <td class="py-5 px-4 text-right font-black text-sm text-slate-900">Rp {{ number_format($o->nominal, 0, ',', '.') }}</td>
                                        <td class="py-5 px-4 text-right">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('biaya-perjalanan.edit', ['operasional', $o->id]) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('biaya-perjalanan.destroy', ['operasional', $o->id]) }}" method="POST" onsubmit="return confirm('Hapus data operasional?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest">Data Operasional Kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-6">{{ $operasional->appends(['tab' => 'oper', 'search' => request('search')])->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</x-app-layout>
