<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tighter uppercase">Master Kategori Pengajuan</h2>
            <div class="flex gap-2">
                <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase flex items-center">
                    Total: {{ $kategoris->count() }} Kategori
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-4 bg-slate-50 min-h-screen">
        <div class="max-w-[99%] mx-auto px-2">
            <div class="flex flex-col md:flex-row gap-4">

                <div class="w-full md:w-1/3">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-slate-100/80 px-4 py-3 border-b border-slate-200">
                            <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tambah Kategori Baru</h3>
                        </div>
                        <div class="p-5">
                            <form action="{{ route('kategori.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Nama Kategori</label>
                                    <input type="text" name="nama_kategori"
                                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder:font-normal"
                                        placeholder="INPUT NAMA KATEGORI..." required>
                                    @error('nama_kategori')
                                        <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-black text-xs uppercase tracking-widest shadow-md transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                    Simpan Kategori
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-bold uppercase tracking-tight flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs font-bold uppercase tracking-tight flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse table-fixed">
                                <thead class="bg-slate-100/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-3 w-[15%] text-center">No</th>
                                        <th class="px-6 py-3 w-[60%]">Nama Kategori</th>
                                        <th class="px-6 py-3 w-[25%] text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($kategoris as $index => $k)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-slate-400 text-center">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-black text-slate-800 uppercase tracking-tight">
                                            {{ $k->nama_kategori }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('kategori.edit', $k->id) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                                <p class="text-[11px] font-black uppercase tracking-widest">Belum ada data kategori</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
