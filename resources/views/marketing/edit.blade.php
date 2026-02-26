<x-app-layout>
    <div class="py-24 bg-white min-h-screen">
        <div class="max-w-xl mx-auto px-6">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Perbarui Profil</h3>
                    <a href="{{ route('marketing.index') }}" class="text-slate-400 hover:text-slate-600 text-sm font-bold">Batal</a>
                </div>

                <form action="{{ route('marketing.update', $marketing->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf @method('PUT')

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Marketing</label>
                        <input type="text" name="nama" value="{{ $marketing->nama }}" class="w-full bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold p-5 shadow-inner" required>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Level Penugasan</label>
                        <div class="flex gap-4">
                            @foreach([1, 2, 3] as $lvl)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="level" value="{{ $lvl }}" class="hidden peer" {{ $marketing->level == $lvl ? 'checked' : '' }}>
                                <div class="text-center p-4 rounded-2xl border-2 border-slate-50 bg-slate-50 peer-checked:border-indigo-600 peer-checked:bg-white peer-checked:text-indigo-600 transition-all font-black">
                                    {{ $lvl }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-xl shadow-indigo-100 hover:scale-[1.02] transition-transform active:scale-95">
                        UPDATE SEKARANG
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
