<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Master Data Marketing') }}
            </h2>
            <div class="text-sm text-slate-500 font-medium">
                
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Marketing</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $marketings->count() }}</p>
                        </div>
                        <div class="bg-indigo-600 p-4 rounded-2xl shadow-lg shadow-indigo-200">
                            <p class="text-xs font-semibold text-indigo-100 uppercase tracking-wider">Level 1</p>
                            <p class="text-2xl font-bold text-white">{{ $marketings->where('level', 1)->count() }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Level 2 & 3</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $marketings->whereIn('level', [2,3])->count() }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-widest font-bold">
                                <tr>
                                    <th class="px-6 py-4">Nama Marketing</th>
                                    <th class="px-6 py-4 text-center">Level</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($marketings as $m)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold group-hover:bg-indigo-100 group-hover:text-indigo-600 transition">
                                                {{ strtoupper(substr($m->nama, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-700">{{ $m->nama }}</div>
                                                <div class="text-xs text-slate-400">ID: #{{ str_pad($m->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $badgeClass = [
                                                1 => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                                2 => 'bg-amber-50 text-amber-700 border-amber-100',
                                                3 => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            ][$m->level];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $badgeClass }}">
                                            Lvl {{ $m->level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-3">
                                            <a href="{{ route('marketing.edit', $m->id) }}" class="p-2 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('marketing.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-slate-50 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
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
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sticky top-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="p-3 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight text-uppercase">Tambah</h3>
                        </div>

                        <form action="{{ route('marketing.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" class="w-full bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 placeholder-slate-300 text-sm font-semibold p-4" placeholder="Input nama marketing..." required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Level</label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach([1, 2, 3] as $l)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="level" value="{{ $l }}" class="hidden peer" required>
                                        <div class="text-center p-3 rounded-xl border border-slate-100 bg-slate-50 group-hover:bg-white peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-indigo-200 transition-all font-bold text-slate-400">
                                            {{ $l }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-slate-400 mt-3 italic leading-relaxed">
                                    {{-- *Level menentukan plafon Uang Makan & Hotel sesuai regulasi 19 Nov 2024. --}}
                                </p>
                            </div>

                            <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-xl font-bold shadow-lg shadow-slate-300 hover:bg-indigo-600 hover:-translate-y-1 transition-all">
                                SIMPAN DATA
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
