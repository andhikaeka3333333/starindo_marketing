<x-app-layout>
    <div class="min-h-screen bg-[#f8fafc] py-8 text-slate-900 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wide">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 uppercase tracking-tight">
                        Monitoring <span class="text-indigo-600">Tol</span>
                    </h2>
                </div>
                <div class="flex gap-2">
                    <button onclick="openModal('topup')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow-lg shadow-indigo-100 active:scale-95">
                        + Top Up Saldo
                    </button>
                    <button onclick="openModal('out')"
                        class="bg-white hover:bg-slate-50 text-slate-900 px-5 py-2.5 border border-slate-200 rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow-sm active:scale-95">
                        - Bayar Tol
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-8">
                <div
                    class="lg:col-span-2 relative overflow-hidden bg-indigo-600 p-8 rounded-[1.5rem] shadow-xl shadow-indigo-100">
                    <div class="relative z-10 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-bold uppercase text-indigo-100 tracking-[0.2em] opacity-80">
                                    Saldo Tol</p>
                                <h3 class="text-5xl font-extrabold mt-3 tracking-tight">
                                    Rp {{ number_format($current_balance, 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md">
                                <svg class="w-8 h-8 text-white opacity-80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-[1.5rem] border border-slate-200 shadow-sm flex flex-col justify-center">
                    <p class="text-[10px] font-bold uppercase text-slate-400 tracking-[0.2em]">In (Bulan Ini)</p>
                    <h4 class="text-2xl font-extrabold text-green-600 mt-2">+ Rp
                        {{ number_format($total_topup, 0, ',', '.') }}</h4>
                </div>

                <div
                    class="bg-white p-6 rounded-[1.5rem] border border-slate-200 shadow-sm flex flex-col justify-center">
                    <p class="text-[10px] font-bold uppercase text-slate-400 tracking-[0.2em]">Out (Bulan Ini)</p>
                    <h4 class="text-2xl font-extrabold text-indigo-600 mt-2">- Rp
                        {{ number_format($total_out, 0, ',', '.') }}</h4>
                </div>
            </div>

            <div class="bg-white rounded-[1.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-900">Riwayat Aktivitas</h3>
                    <span
                        class="text-[9px] font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg uppercase tracking-wider">Live
                        Updates</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4 text-center">Tanggal</th>
                                <th class="px-6 py-4">Keterangan Aktivitas</th>
                                <th class="px-8 py-4 text-right">Nominal</th>
                                <th class="px-8 py-4 text-right">Sisa Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($history as $item)
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="px-6 py-3 text-center">
                                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">
                                            {{ $item->created_at->format('d M') }}
                                            <span
                                                class="block text-[9px] opacity-60 font-medium">{{ $item->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span
                                            class="text-xs font-bold text-slate-800 uppercase tracking-tight">{{ $item->keterangan }}</span>
                                    </td>
                                    <td class="px-8 py-3 text-right">
                                        <span
                                            class="text-xs font-extrabold {{ $item->tipe == 'topup' ? 'text-green-600' : 'text-slate-900' }}">
                                            {{ $item->tipe == 'topup' ? '+' : '-' }} Rp
                                            {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-3 text-right">
                                        <span class="text-[10px] font-bold text-slate-400 italic">Rp
                                            {{ number_format($item->saldo_akhir, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 bg-slate-50/30">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>

    <div id="modal"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-[100] p-6">
        <div class="bg-white w-full max-w-md rounded-[2rem] p-10 shadow-2xl relative border border-slate-100">
            <h3 id="modalTitle" class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight mb-8">New
                Transaction</h3>

            <form action="{{ route('toll.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="tipe" id="modalType">

                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-400 tracking-[0.2em] ml-1">Keterangan
                        Transaksi</label>
                    <input type="text" name="keterangan" placeholder="Masukan rute atau keterangan..."
                        class="mt-2 w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-slate-900 placeholder:text-slate-400 font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition-all shadow-inner"
                        required>
                </div>

                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-400 tracking-[0.2em] ml-1">Nominal
                        Rp</label>
                    <input type="number" name="nominal" placeholder="0"
                        class="mt-2 w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-slate-900 text-2xl font-extrabold focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition-all shadow-inner"
                        required>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-xl shadow-indigo-200 transition-all active:scale-95">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(type) {
            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalType').value = type;
            document.getElementById('modalTitle').innerText = type === 'topup' ? 'Top Up Saldo' : 'Bayar Biaya Tol';
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>
