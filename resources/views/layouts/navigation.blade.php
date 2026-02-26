<nav x-data="{ open: false }" class="bg-[#0f172a] border-b border-white/5 shadow-2xl sticky top-0 z-50 backdrop-blur-md bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transition-transform hover:scale-105 duration-300">
                        <img src="https://static.wixstatic.com/media/24ab9d_8104b3df7a154dcc8744040f538f351c~mv2.png/v1/fill/w_318,h_128,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/logo%20starindo.png"
                             class="h-10 w-auto brightness-110 contrast-125" alt="Starindo Logo">
                    </a>
                </div>

                <div class="hidden space-x-1 sm:flex h-full items-center">
                    @php
                        $menus = [
                            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['route' => 'marketing.index', 'label' => 'Marketing', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['route' => 'pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['route' => 'rekapitulasi', 'label' => 'Rekap', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ];
                    @endphp

                    @foreach ($menus as $menu)
                        <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                           class="group inline-flex items-center px-4 py-2 text-xs font-bold tracking-[0.1em] uppercase transition-all duration-300 rounded-lg
                           {{ request()->routeIs($menu['route'] . '*')
                              ? 'bg-blue-600/10 text-blue-400 shadow-[inset_0_0_20px_rgba(37,99,235,0.1)]'
                              : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                            </svg>
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="h-8 w-[1px] bg-white/10 hidden sm:block"></div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="group flex items-center gap-3 pl-3 pr-1 py-1 bg-slate-800/40 hover:bg-slate-800/60 border border-white/5 rounded-full transition-all duration-300">
                            <div class="text-right hidden md:block">
                                <p class="text-[9px] font-black uppercase text-blue-400 tracking-tighter leading-none mb-1">PT Starindo Jaya Packaging</p>
                                <p class="text-xs font-bold text-slate-200 tracking-tight">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-[10px] font-bold text-white border border-white/20 shadow-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100 mb-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">User Settings</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="text-xs font-bold uppercase tracking-tight hover:bg-blue-50 transition-colors">
                            Manage Profile
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-xs font-bold uppercase tracking-tight text-red-600 hover:bg-red-50 transition-colors">
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
