<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tighter uppercase">Monitoring Omset</h2>
            <a href="{{ route('omset.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-4 bg-slate-50 min-h-screen">
        <div class="max-w-[99%] mx-auto px-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap gap-3 items-center">
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari Nama Marketing..."
                                class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>

                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                            Cari
                        </button>

                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="text-slate-500 hover:text-red-600 text-xs font-bold uppercase tracking-widest transition-all">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <thead class="bg-slate-100/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 w-[25%]">Nama Marketing</th>
                                <th class="px-4 py-3 w-[20%] text-center">Periode Dari</th>
                                <th class="px-4 py-3 w-[20%] text-center">Sampai Tanggal</th>
                                <th class="px-4 py-3 w-[25%] text-right">Nominal (IDR)</th>
                                <th class="px-4 py-3 w-[10%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($omsets as $o)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 py-4 text-sm font-bold text-slate-800 uppercase truncate">
                                    {{ $o->marketing->nama ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-slate-500 text-center">
                                    {{ \Carbon\Carbon::parse($o->periode_dari)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-slate-500 text-center">
                                    {{ \Carbon\Carbon::parse($o->periode_sampai)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 text-right font-black text-indigo-700 text-base">
                                    {{ number_format($o->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('omset.edit', $o->id) }}" class="p-1.5 text-slate-400 hover:text-amber-600 transition-all hover:bg-amber-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('omset.destroy', $o->id) }}" method="POST" onsubmit="return confirm('Hapus data omset ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 transition-all hover:bg-red-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-400 font-medium italic">Data omset tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-2 bg-slate-50 border-t border-slate-200">
                    {{ $omsets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
