@extends('layouts.user')

@section('content')
<h1 class="text-2xl font-bold mb-4">BERANDA</h1>
<p class="mb-6">Halo, {{ Auth::user()->name }}</p>

<!-- Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('user.lihatData', ['kategori' => 'pendatang']) }}" class="bg-yellow-300 hover:bg-yellow-400 p-6 rounded-lg shadow text-center">
        <p class="text-3xl font-bold">{{ $jumlahPendatang }}</p>
        <p class="mt-2 font-semibold">Jumlah Pendatang Baru</p>
    </a>
    <a href="{{ route('user.lihatData', ['kategori' => 'perpindahan']) }}" class="bg-purple-300 hover:bg-purple-400 p-6 rounded-lg shadow text-center">
        <p class="text-3xl font-bold">{{ $jumlahPerpindahan }}</p>
        <p class="mt-2 font-semibold">Jumlah Perpindahan</p>
    </a>
    <a href="{{ route('user.lihatData', ['kategori' => 'kelahiran']) }}" class="bg-cyan-300 hover:bg-cyan-400 p-6 rounded-lg shadow text-center">
        <p class="text-3xl font-bold">{{ $jumlahKelahiran }}</p>
        <p class="mt-2 font-semibold">Jumlah Kelahiran</p>
    </a>
    <a href="{{ route('user.lihatData', ['kategori' => 'kematian']) }}" class="bg-orange-300 hover:bg-orange-400 p-6 rounded-lg shadow text-center">
        <p class="text-3xl font-bold">{{ $jumlahKematian }}</p>
        <p class="mt-2 font-semibold">Jumlah Kematian</p>
    </a>
    <a href="{{ route('user.lihatData', ['kategori' => 'kk']) }}" class="bg-red-300 hover:bg-red-400 p-6 rounded-lg shadow text-center">
        <p class="text-3xl font-bold">{{ $jumlahKK }}</p>
        <p class="mt-2 font-semibold">Jumlah Kartu Keluarga</p>
    </a>
    <a href="{{ route('user.lihatData', ['kategori' => 'warga']) }}" class="bg-green-300 hover:bg-green-400 p-6 rounded-lg shadow text-center">
        <p class="text-3xl font-bold">{{ $jumlahWarga }}</p>
        <p class="mt-2 font-semibold">Jumlah Total Warga</p>
    </a>
</div>

<!-- Statistik -->
<h2 class="text-xl font-bold mb-4">Statistik Jumlah Penduduk</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Bulan Ini</h3>
        <ul>
            @foreach($statistikBulanIni as $data)
                <li class="flex justify-between border-b py-1">
                    <span>{{ $data->desa }}</span>
                    <span>{{ $data->jumlah }} warga</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Bulan Lalu</h3>
        <ul>
            @foreach($statistikBulanLalu as $data)
                <li class="flex justify-between border-b py-1">
                    <span>{{ $data->desa }}</span>
                    <span>{{ $data->jumlah }} warga</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
