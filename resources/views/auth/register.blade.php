<x-guest-layout>
    <div class="min-h-screen flex">
        <div class="hidden lg:flex w-1/2 bg-cover bg-center relative"
             style="background-image: url('{{ asset('asset/bg-auth.png') }}');">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 to-transparent flex flex-col justify-center px-12 text-white">
                <h1 class="text-5xl font-extrabold tracking-tight mb-4">
                    Join <span class="text-blue-400">Starindo</span>
                </h1>
                <p class="text-lg text-gray-200 max-w-md leading-relaxed">
                    Mulailah perjalanan digitalisasi Anda bersama kami. Kelola data dengan lebih cerdas dan efisien dalam satu platform terintegrasi.
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="h-1 w-20 bg-blue-500 rounded"></div>
                    <span class="text-sm uppercase tracking-[0.2em] font-semibold text-blue-300">Digital Transformation</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-8 py-12">
            <div class="max-w-md w-full">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Daftar Akun</h2>
                    <p class="text-gray-500 mt-2">Buat akun baru untuk akses sistem Starindo</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-medium" />
                        <x-text-input id="name" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm py-3"
                            type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm py-3"
                            type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="admin@starindo.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm py-3"
                            type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-medium" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm py-3"
                            type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                            {{ __('DAFTAR SEKARANG') }}
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun?
                            <a class="text-blue-600 hover:text-blue-800 font-bold underline transition" href="{{ route('login') }}">
                                {{ __('Masuk di sini') }}
                            </a>
                        </p>
                    </div>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400 uppercase tracking-widest">
                    &copy; 2026 Starindo Digital System
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
