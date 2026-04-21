<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tighter uppercase">Edit Kategori</h2>
            <a href="{{ route('kategori.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI
            </a>
        </div>
    </x-slot>

    <div class="py-4 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-2 mt-4">

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs font-bold uppercase tracking-tight flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-100/80 px-4 py-3 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Update Data Kategori</h3>
                    <span class="text-[9px] font-black text-slate-400 uppercase bg-slate-100 px-2 py-0.5 rounded">ID: {{ $kategori->id }}</span>
                </div>

                <div class="p-6">
                    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Nama Kategori saat ini</label>
                            <input type="text" name="nama_kategori"
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder:font-normal"
                                placeholder="INPUT NAMA KATEGORI..." required>

                            @error('nama_kategori')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                            @enderror
                            <p class="text-slate-400 text-[10px] mt-1.5 font-medium">Pastikan nama kategori unik dan merepresentasikan jenis pengajuan dengan jelas.</p>
                        </div>

                        <div class="flex justify-end pt-3 border-t border-slate-100 gap-3">
                            <a href="{{ route('kategori.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 py-2.5 px-5 rounded-xl font-black text-xs uppercase tracking-widest transition-all">
                                Batal
                            </a>
                             <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 px-6 rounded-xl font-black text-xs uppercase tracking-widest shadow-md transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Update Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Tambahan (Opsional) --}}
            <div class="mt-4 p-4 bg-white rounded-xl border border-slate-200 shadow-sm flex items-start gap-3">
                <div class="p-2 bg-indigo-50 text-indigo-500 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 10 9 9 0 0118-10z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase">Efek Perubahan</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Mengubah nama kategori di sini akan otomatis memperbarui tampilan di semua data pengajuan yang menggunakan kategori ini. Integritas data tetap terjaga.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
