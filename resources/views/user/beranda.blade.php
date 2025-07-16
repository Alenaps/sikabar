@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">BERANDA</h1>
    <p class="mb-6">Halo, {{ Auth::user()->name }}</p>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-yellow-300 p-4 rounded shadow text-center">
            <p class="text-lg font-bold">{{ $jumlahPendatang }}</p>
            <p>Jumlah Pendatang Baru</p>
        </div>
        <div class="bg-purple-300 p-4 rounded shadow text-center">
            <p class="text-lg font-bold">{{ $jumlahPerpindahan }}</p>
            <p>Jumlah Perpindahan</p>
        </div>
        <div class="bg-cyan-300 p-4 rounded shadow text-center">
            <p class="text-lg font-bold">{{ $jumlahKelahiran }}</p>
            <p>Jumlah Kelahiran</p>
        </div>
        <div class="bg-orange-300 p-4 rounded shadow text-center">
            <p class="text-lg font-bold">{{ $jumlahKematian }}</p>
            <p>Jumlah Kematian</p>
        </div>
        <div class="bg-red-300 p-4 rounded shadow text-center">
            <p class="text-lg font-bold">{{ $jumlahKK }}</p>
            <p>Jumlah Kartu Keluarga</p>
        </div>
        <div class="bg-green-300 p-4 rounded shadow text-center">
            <p class="text-lg font-bold">{{ $jumlahWarga }}</p>
            <p>Jumlah Total Warga</p>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-bold mb-2">Statistik Jumlah Penduduk</h2>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="mb-2 font-semibold">Bulan Ini</p>
                <ul>
                    @foreach($statistikBulanIni as $data)
                        <li>{{ $data->desa }}: {{ $data->jumlah }} warga</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="mb-2 font-semibold">Bulan Lalu</p>
                <ul>
                    @foreach($statistikBulanLalu as $data)
                        <li>{{ $data->desa }}: {{ $data->jumlah }} warga</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
