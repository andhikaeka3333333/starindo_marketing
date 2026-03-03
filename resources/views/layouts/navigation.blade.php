<nav x-data="{ open: false, openKendaraan: false, openPengajuan: false }"
    class="bg-[#0f172a] border-b border-white/5 shadow-2xl sticky top-0 z-50 backdrop-blur-md bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transition-transform hover:scale-105 duration-300">
                        <img src="https://static.wixstatic.com/media/24ab9d_8104b3df7a154dcc8744040f538f351c~mv2.png/v1/fill/w_318,h_128,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/logo%20starindo.png"
                            class="h-10 w-auto brightness-110 contrast-125" alt="Starindo Logo">
                    </a>
                </div>

                <div class="hidden lg:flex space-x-1 h-full items-center">
                    @php
                        $mainMenus = [
                            [
                                'route' => 'marketing.index',
                                'label' => 'Marketing',
                                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                            ],
                            [
                                'route' => 'omset.index',
                                'label' => 'Omset',
                                'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', // Icon Trending Up/Omset
                            ],
                            [
                                'route' => 'biaya-perjalanan.index',
                                'label' => 'Perjalanan',
                                'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                            ],
                        ];
                    @endphp

                    @foreach ($mainMenus as $menu)
                        <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                            class="group inline-flex items-center px-4 py-2 text-xs font-bold tracking-[0.1em] uppercase transition-all duration-300 rounded-lg
                           {{ request()->routeIs($menu['route'] . '*')
                               ? 'bg-blue-600/10 text-blue-400 shadow-[inset_0_0_20px_rgba(37,99,235,0.1)]'
                               : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100 transition-opacity"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $menu['icon'] }}"></path>
                            </svg>
                            {{ $menu['label'] }}
                        </a>
                    @endforeach

                    {{-- Dropdown Pengajuan --}}
                    <div class="relative" @click.away="openPengajuan = false">
                        <button @click="openPengajuan = !openPengajuan"
                            class="group inline-flex items-center px-4 py-2 text-xs font-bold tracking-[0.1em] uppercase transition-all duration-300 rounded-lg
                                {{ request()->routeIs('pengajuan.*') || request()->routeIs('kategori.*')
                                    ? 'bg-blue-600/10 text-blue-400 shadow-[inset_0_0_20px_rgba(37,99,235,0.1)]'
                                    : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100 transition-opacity"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Pengajuan
                            <svg class="w-3 h-3 ml-2 transition-transform duration-300"
                                :class="openPengajuan ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="openPengajuan" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute left-0 mt-2 w-52 rounded-xl bg-[#1e293b] border border-white/10 shadow-2xl py-2 z-50">
                            <a href="{{ route('pengajuan.index') }}"
                                class="block px-4 py-2 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('pengajuan.*') ? 'text-blue-400 bg-blue-600/10' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} transition-colors">
                                Data Pengajuan
                            </a>
                            <a href="{{ route('kategori.index') }}"
                                class="block px-4 py-2 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('kategori.*') ? 'text-blue-400 bg-blue-600/10' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} transition-colors">
                                Master Kategori
                            </a>
                        </div>
                    </div>

                    {{-- Dropdown Kendaraan --}}
                    <div class="relative" @click.away="openKendaraan = false">
                        <button @click="openKendaraan = !openKendaraan"
                            class="group inline-flex items-center px-4 py-2 text-xs font-bold tracking-[0.1em] uppercase transition-all duration-300 rounded-lg
                                {{ request()->routeIs('toll.*') || request()->routeIs('bensin.*')
                                    ? 'bg-blue-600/10 text-blue-400 shadow-[inset_0_0_20px_rgba(37,99,235,0.1)]'
                                    : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100 transition-opacity"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0a1 1 0 001 1h1">
                                </path>
                            </svg>
                            Kendaraan
                            <svg class="w-3 h-3 ml-2 transition-transform duration-300"
                                :class="openKendaraan ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="openKendaraan" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute left-0 mt-2 w-48 rounded-xl bg-[#1e293b] border border-white/10 shadow-2xl py-2 z-50">
                            <a href="{{ route('toll.index') }}"
                                class="block px-4 py-2 text-[11px] font-bold uppercase tracking-widest text-slate-300 hover:bg-blue-600/20 hover:text-blue-400 transition-colors">Biaya
                                Tol</a>
                            <a href="{{ route('bensin.index') }}"
                                class="block px-4 py-2 text-[11px] font-bold uppercase tracking-widest text-slate-300 hover:bg-blue-600/20 hover:text-blue-400 transition-colors">Bensin</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Settings Dropdown --}}
            <div class="flex items-center space-x-2 sm:space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="group flex items-center gap-3 pl-3 pr-1 py-1 bg-slate-800/40 hover:bg-slate-800/60 border border-white/5 rounded-full transition-all duration-300">
                            <div class="text-right hidden md:block">
                                <p
                                    class="text-[9px] font-black uppercase text-blue-400 tracking-tighter leading-none mb-1">
                                    PT Starindo Jaya Packaging</p>
                                <p class="text-xs font-bold text-slate-200 tracking-tight">{{ Auth::user()->name }}</p>
                            </div>
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-[10px] font-bold text-white border border-white/20 shadow-lg">
                                {{ strtoupper(collect(explode(' ', Auth::user()->name))->map(fn($word) => $word[0])->take(2)->implode('')) }}
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100 mb-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">User Settings</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="text-xs font-bold uppercase">Manage
                            Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-xs font-bold uppercase text-red-600">Logout</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                {{-- Hamburger Mobile --}}
                <div class="flex items-center lg:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l18 18" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-[#0f172a] border-t border-white/5 pb-4 shadow-xl">

        <div class="pt-2 pb-3 space-y-1 px-4">
            @foreach ($mainMenus as $menu)
                <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                    class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-widest rounded-xl transition-colors
                    {{ request()->routeIs($menu['route'] . '*') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="{{ $menu['icon'] }}"></path>
                    </svg>
                    {{ $menu['label'] }}
                </a>
            @endforeach

            {{-- Mobile Dropdowns --}}
            {{-- (Pengajuan & Kendaraan Mobile tetap sama) --}}
            <div x-data="{ localPengajuan: false }" class="space-y-1">
                <button @click="localPengajuan = !localPengajuan"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-bold uppercase tracking-widest rounded-xl {{ request()->routeIs('pengajuan.*') || request()->routeIs('kategori.*') ? 'text-blue-400' : 'text-slate-400' }} hover:bg-white/5 hover:text-white transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Pengajuan
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="localPengajuan ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="localPengajuan" class="pl-12 space-y-1">
                    <a href="{{ route('pengajuan.index') }}"
                        class="block py-2 text-xs font-bold uppercase {{ request()->routeIs('pengajuan.*') ? 'text-blue-400' : 'text-slate-500' }} hover:text-blue-400 transition-colors">Data
                        Pengajuan</a>
                    <a href="{{ route('kategori.index') }}"
                        class="block py-2 text-xs font-bold uppercase {{ request()->routeIs('kategori.*') ? 'text-blue-400' : 'text-slate-500' }} hover:text-blue-400 transition-colors">Master
                        Kategori Pengajuan</a>
                </div>
            </div>

            <div x-data="{ localOpen: false }" class="space-y-1">
                <button @click="localOpen = !localOpen"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-bold uppercase tracking-widest rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0a1 1 0 001 1h1">
                            </path>
                        </svg>
                        Kendaraan
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="localOpen ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="localOpen" class="pl-12 space-y-1">
                    <a href="{{ route('toll.index') }}"
                        class="block py-2 text-xs font-bold uppercase text-slate-500 hover:text-blue-400 transition-colors">Biaya
                        Tol</a>
                    <a href="{{ route('bensin.index') }}"
                        class="block py-2 text-xs font-bold uppercase text-slate-500 hover:text-blue-400 transition-colors">Bensin</a>
                </div>
            </div>
        </div>
    </div>
</nav>
