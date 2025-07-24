<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKABAR | {{ $title ?? 'Dashboard' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="icon" href="{{ asset('img/logo.png') }}">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-gradient-to-b from-blue-700 to-blue-900 text-white flex flex-col justify-between">
            <div>
                <div class="p-4 flex items-center gap-2">
                    <img src="{{ asset('img/logo.png') }}" class="w-10 h-10" alt="Logo">
                    <h1 class="font-bold text-lg leading-tight">Sistem Informasi<br>Kependudukan</h1>
                </div>
                <p class="px-4 text-sm text-white font-medium">Kecamatan Bandar Sribhawono</p>
                <div class="border-t border-white my-3"></div>

                {{-- Menu --}}
                <nav class="px-4 space-y-2">
                    <a href="{{ route('user.beranda') }}" class="block py-2 px-4 rounded hover:bg-blue-600 {{ request()->routeIs('user.beranda') ? 'bg-blue-600' : '' }}">
                        🏠 BERANDA
                    </a>
                    <p class="text-sm mt-4 font-bold text-white">LIHAT DATA</p>
                    <a href="{{ route('user.perpindahan.index') }}" class="block py-2 px-4 rounded hover:bg-blue-600 {{ request()->routeIs('user.perpindahan.*') ? 'bg-blue-600' : '' }}">
                        📄 PERPINDAHAN
                    </a>
                    <a href="{{ route('user.pendatang.index') }}" class="block py-2 px-4 rounded hover:bg-blue-600 {{ request()->routeIs('user.pendatang.*') ? 'bg-blue-600' : '' }}">
                        📥 PENDATANG BARU
                    </a>
                    <a href="{{ route('user.kelahiran.index') }}" class="block py-2 px-4 rounded hover:bg-blue-600 {{ request()->routeIs('user.kelahiran.*') ? 'bg-blue-600' : '' }}">
                        👶 KELAHIRAN
                    </a>
                    <a href="{{ route('user.kematian.index') }}" class="block py-2 px-4 rounded hover:bg-blue-600 {{ request()->routeIs('user.kematian.*') ? 'bg-blue-600' : '' }}">
                        ⚰️ KEMATIAN
                    </a>
                </nav>
            </div>
            <footer class="text-center text-xs p-4 text-white opacity-80">
                Copyright © LNP 2025
            </footer>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-8 overflow-y-auto bg-gray-100">
            @yield('content')
        </main>
    </div>

</body>
</html>
