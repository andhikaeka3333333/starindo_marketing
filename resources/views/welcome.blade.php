<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Starindo Jaya Packaging - Internal System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="bg-[#0f172a] text-white min-h-screen flex flex-col items-center justify-center relative overflow-hidden">

    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none">
    </div>

    <header class="fixed top-0 w-full p-6 lg:p-10 flex justify-end z-50">
        @if (Route::has('login'))
            <nav class="flex gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-6 py-2.5 glass-effect rounded-full text-xs font-black uppercase tracking-widest hover:bg-white hover:text-[#0f172a] transition-all border border-white/10">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:text-blue-400 transition-all">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-6 py-2.5 bg-blue-600 rounded-full text-xs font-black uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg shadow-blue-900/20">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="relative z-10 flex flex-col items-center text-center px-6">

        <img src="https://static.wixstatic.com/media/24ab9d_8104b3df7a154dcc8744040f538f351c~mv2.png/v1/fill/w_318,h_128,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/logo%20starindo.png"
            alt="" width="300">

        {{-- <h1 class="text-4xl md:text-6xl font-black tracking-tighter uppercase mb-4">
                Starindo Jaya <span class="text-blue-500 text-outline">Packaging</span>
            </h1> --}}
    </main>

    <footer class="fixed bottom-0 w-full p-8 flex justify-center opacity-30">
        <p class="text-[10px] font-bold uppercase tracking-widest">
            © {{ date('Y') }} PT Starindo Jaya Packaging Internal System. All Rights Reserved.
        </p>
    </footer>

</body>

</html>
