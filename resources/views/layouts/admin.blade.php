<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'SIKABAR Admin') }}</title>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-700 to-blue-900 text-white flex flex-col">
            <div class="p-4 text-center border-b border-blue-800">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto w-16 mb-2">
                <h1 class="text-lg font-bold">Sistem Informasi</h1>
                <h2 class="text-sm">Kecamatan Sribhawono</h2>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.beranda') }}" class="flex items-center px-3 py-2 rounded hover:bg-blue-600 {{ request()->routeIs('admin.beranda') ? 'bg-blue-600' : '' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                    BERANDA
                </a>
                <a href="{{ route('admin.dataWarga') }}" class="flex items-center px-3 py-2 rounded hover:bg-blue-600 {{ request()->routeIs('admin.dataWarga') ? 'bg-blue-600' : '' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.121 17.804A1 1 0 016 17h12a1 1 0 01.879 1.485l-6 10a1 1 0 01-1.758 0l-6-10a1 1 0 01.121-1.681z"/></svg>
                    DATA WARGA
                </a>
                <a href="{{ route('admin.kartuKeluarga') }}" class="flex items-center px-3 py-2 rounded hover:bg-blue-600 {{ request()->routeIs('admin.kartuKeluarga') ? 'bg-blue-600' : '' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
                    KARTU KELUARGA
                </a>
                <p class="mt-4 mb-1 text-sm font-semibold">KELOLA DATA</p>
                <a href="{{ route('admin.perpindahan') }}" class="block px-3 py-2 rounded hover:bg-blue-600">Perpindahan</a>
                <a href="{{ route('admin.pendatangBaru') }}" class="block px-3 py-2 rounded hover:bg-blue-600">Pendatang Baru</a>
                <a href="{{ route('admin.kelahiran') }}" class="block px-3 py-2 rounded hover:bg-blue-600">Kelahiran</a>
                <a href="{{ route('admin.kematian') }}" class="block px-3 py-2 rounded hover:bg-blue-600">Kematian</a>
            </nav>
            <div class="p-4 text-center text-xs text-gray-300">
                &copy; {{ date('Y') }} Copyrights by LNP
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-700">Admin Panel</h1>
                <div class="flex items-center space-x-3">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Dynamic Page Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
