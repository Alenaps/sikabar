@extends('layouts.user')

@section('content')
<h1 class="text-xl font-bold mb-4">Data Warga</h1>

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input type="text" name="cari" placeholder="Cari NIK/Nama/KK" value="{{ request('cari') }}" class="border p-2" />

    <select name="desa" class="border p-2">
        <option value="">-- Desa --</option>
        @foreach(['Sribhawono', 'Sripendowo', 'Srimenanti', 'Sadar Sriwijaya', 'Bandar Agung', 'Mekar Jaya', 'Waringin Jaya'] as $desa)
            <option value="{{ $desa }}" {{ request('desa') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
        @endforeach
    </select>

    <select name="jenis_kelamin" class="border p-2">
        <option value="">-- JK --</option>
        <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
        <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
    </select>

    <input type="number" name="usia_min" placeholder="Usia min" value="{{ request('usia_min') }}" class="border p-2 w-24" />
    <input type="number" name="usia_max" placeholder="Usia max" value="{{ request('usia_max') }}" class="border p-2 w-24" />

    <button type="submit" class="bg-blue-500 text-white px-4 py-2">Filter</button>

    <a class="bg-green-500 text-white px-4 py-2">Export Excel</a>
</form>

<table class="w-full border text-sm">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="px-3 py-2">NIK</th>
            <th class="px-3 py-2">No KK</th>
            <th class="px-3 py-2">Nama</th>
            <th class="px-3 py-2">JK</th>
            <th class="px-3 py-2">Tempat Lahir</th>
            <th class="px-3 py-2">Tgl Lahir</th>
            <th class="px-3 py-2">Agama</th>
            <th class="px-3 py-2">Status</th>
            <th class="px-3 py-2">Hub. Keluarga</th>
            <th class="px-3 py-2">Desa</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($wargas as $warga)
        <tr class="border-b">
            <td class="px-3 py-2">{{ $warga->nik }}</td>
            <td class="px-3 py-2">{{ $warga->no_kk }}</td>
            <td class="px-3 py-2">{{ $warga->nama }}</td>
            <td class="px-3 py-2">{{ $warga->jenis_kelamin }}</td>
            <td class="px-3 py-2">{{ $warga->tempat_lahir }}</td>
            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($warga->tanggal_lahir)->format('d-m-Y') }}</td>
            <td class="px-3 py-2">{{ $warga->agama }}</td>
            <td class="px-3 py-2">{{ $warga->status }}</td>
            <td class="px-3 py-2">{{ $warga->hubungan_keluarga }}</td>
            <td class="px-3 py-2">{{ $warga->kartuKeluarga->desa ?? '-' }}</td>

        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center py-4">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $wargas->links() }}
</div>
@endsection
