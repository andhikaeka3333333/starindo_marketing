<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tighter uppercase">Monitoring Pengajuan</h2>
            <a href="{{ route('pengajuan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-4 bg-slate-50 min-h-screen">
        <div class="max-w-[99%] mx-auto px-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <thead class="bg-slate-100/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 w-[10%]">Marketing</th>
                                <th class="px-4 py-3 w-[10%]">Tanggal</th>
                                <th class="px-4 py-3 w-[10%]">Customer</th>
                                <th class="px-4 py-3 w-[10%]">Kontak&nbsp;(CP)</th>
                                <th class="px-4 py-3 w-[15%]">Alamat</th>
                                 <th class="px-4 py-3 w-[10%]">Kontak</th>
                                <th class="px-4 py-3 w-[10%]">Kategori</th>
                                <th class="px-4 py-3 w-[10%] text-right">Value&nbsp;(IDR)</th>
                                <th class="px-4 py-3 w-[10%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pengajuans as $p)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 py-3 text-sm font-bold text-slate-800 uppercase truncate">
                                    {{ $p->marketing->nama }}
                                </td>

                                <td class="px-4 py-3 text-sm font-medium text-slate-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/y') }}
                                </td>

                                <td class="px-4 py-3 text-sm font-bold text-slate-700 truncate">
                                    {{ $p->customer_nama }}
                                </td>

                                <td class="px-4 py-3 text-sm font-bold text-indigo-600 truncate">
                                    {{ $p->customer_cp ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-slate-600 font-medium truncate" title="{{ $p->alamat }}">
                                    {{ $p->alamat ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 font-medium truncate" title="{{ $p->kontak }}">
                                    {{ $p->customer_cp ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $color = match($p->jenis_pengajuan) {
                                            'Komisi Penjualan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'Entertain' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            default => 'bg-blue-100 text-blue-700 border-blue-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase border {{ $color }} inline-block whitespace-nowrap">
                                        {{ $p->jenis_pengajuan }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right font-black text-slate-900 text-sm whitespace-nowrap">
                                    {{ number_format($p->nominal_value, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-center items-center gap-1">
                                        <a href="{{ route('pengajuan.edit', $p->id) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('pengajuan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-2 bg-slate-50 border-t border-slate-200">
                    {{ $pengajuans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
