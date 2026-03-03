<x-guest-layout>
    <div class="min-h-screen flex">
        <div class="hidden lg:flex w-1/2 bg-cover bg-center relative"
             style="background-image: url('{{ asset('asset/bg-auth.png') }}');">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 to-transparent flex flex-col justify-center px-12 text-white">
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-8 py-12">
            <div class="max-w-md w-full">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
                    <p class="text-gray-500 mt-2">Silakan masuk ke akun Starindo Anda</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <x-text-input id="email"
                            class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm py-3"
                            type="email" name="email" :value="old('email')"
                            required autofocus placeholder="admin@starindo.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                            @if (Route::has('password.request'))
                                <a class="text-sm text-blue-600 hover:text-blue-800 font-medium transition" href="{{ route('password.request') }}">
                                    {{ __('Lupa password?') }}
                                </a>
                            @endif
                        </div>
                        <x-text-input id="password"
                            class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm py-3"
                            type="password" name="password"
                            required placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Biarkan saya tetap masuk') }}</span>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                            {{ __('LOGIN KE DASHBOARD') }}
                        </button>
                    </div>
                </form>

                <p class="mt-10 text-center text-xs text-gray-400 uppercase tracking-widest">
                    &copy; 2026 Starindo Digital System
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
