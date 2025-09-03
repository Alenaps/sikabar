<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Dashboard User')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0047ab',
            secondary: '#001f5b'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-100 font-sans antialiased">

<!-- HEADER -->
<header class="bg-blue-600 text-white px-4 py-3 flex items-center justify-between md:justify-start space-x-4 shadow-md">
  <img src="{{ asset('assets/img/kec.png') }}" alt="Logo" class="w-10 h-10">
  <div>
    <h1 class="text-lg font-bold leading-tight">Sistem Informasi Kependudukan</h1>
    <p class="text-sm">Kecamatan Bandar Sribhawono</p>
  </div>
</header>

<div class="flex flex-col md:flex-row min-h-screen">

  <!-- SIDEBAR -->
  <aside class="w-full md:w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex flex-col">
    <nav class="flex-1 px-4 py-6 space-y-2">
     <nav class="flex-1 space-y-4">
        <a href="{{ route('user.beranda') }}" class="flex items-center gap-3 hover:bg-blue-700 p-2 rounded-md transition">
            <img src="https://img.icons8.com/fluency/48/home.png" alt="Home" class="w-6 h-6">
            <span class="text-base">Beranda</span>
        </a>
        <a href="{{ route('user.warga.index') }}" class="flex items-center gap-3 hover:bg-blue-700 p-2 rounded-md transition">
            <img src="https://img.icons8.com/3d-fluency/94/group--v1.png" alt="Data Warga" class="w-6 h-6">
            <span class="text-base">Data Warga</span>
        </a>
        <a href="{{ route('user.kartukeluarga.index') }}" class="flex items-center gap-3 hover:bg-blue-700 p-2 rounded-md transition">
            <img src="https://img.icons8.com/stickers/100/overview-pages-1.png" alt="Kartu Keluarga" class="w-6 h-6">
            <span class="text-base">Kartu Keluarga</span>
        </a>
    </nav>
      <p class="text-sm font-semibold mt-4 border-t border-blue-500 pt-2">LIHAT DATA</p>
      <a href="{{ route('user.perpindahan.index') }}" class="block ml-2 hover:text-blue-300">• PERPINDAHAN</a>
      <a href="{{ route('user.pendatang.index') }}" class="block ml-2 hover:text-blue-300">• PENDATANG BARU</a>
      <a href="{{ route('user.kelahiran.index') }}" class="block ml-2 hover:text-blue-300">• KELAHIRAN</a>
      <a href="{{ route('user.kematian.index') }}" class="block ml-2 hover:text-blue-300">• KEMATIAN</a>
    </nav>

    <!-- Tombol Logout -->
    <form method="POST" action="{{ route('logout') }}" class="px-4 pb-4">
      @csrf
      <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded shadow transition">
        🔓 Logout
      </button>
    </form>

    <footer class="text-center text-xs p-4 border-t border-blue-700">
      Copyright by LNP 2025
    </footer>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 p-4 md:p-10 bg-white">
    @yield('content')
    @yield('scripts')
  </main>
</div>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
    })
</script>
@endif
