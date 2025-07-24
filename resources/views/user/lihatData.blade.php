@extends('layouts.user') {{-- Sesuaikan layout jika ada --}}

@section('content')
<h1 class="text-xl font-bold">LIHAT DATA</h1>

<form method="GET" action="{{ route('user.lihatData') }}" class="flex gap-2 mb-4">
    <select name="bulan"><option value="">Pilih Bulan</option>...</select>
    <select name="desa"><option value="">Pilih Desa</option>...</select>
    <select name="status"><option value="">Pilih Status</option>...</select>
    <select name="jenis_kelamin"><option value="">Pilih Jenis Kelamin</option>...</select>
    <select name="usia"><option value="">Pilih Usia</option>...</select>
    <button type="submit" class="bg-cyan-500 px-4 py-2 text-white rounded">Filter</button>
</form>

<table class="w-full table-auto border">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIK</th>
            <th>Jenis Kelamin</th>
            <th>Desa</th>
            <th>Status</th>
            <th>Usia</th>
        </tr>
    </thead>
    <tbody>
        @forelse($warga as $item)
        <tr>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->nik }}</td>
            <td>{{ $item->jenis_kelamin }}</td>
            <td>{{ $item->desa }}</td>
            <td>{{ $item->status }}</td>
            <td>{{ $item->usia }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

<a href="#" onclick="window.print()" class="mt-4 inline-block bg-cyan-500 text-white px-4 py-2 rounded">Cetak</a>
@endsection
