@extends('layouts.user')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">LIHAT DATA PERPINDAHAN</h2>

    <form method="GET" action="{{ route('user.perpindahan.index') }}" class="flex flex-wrap gap-2 mb-4">
        <select name="bulan" class="border px-4 py-2">
            <option value="">Pilih Bulan</option>
            @foreach(range(1,12) as $bulan)
                <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
                </option>
            @endforeach
        </select>
        <select name="desa" class="border px-4 py-2">
            <option value="">Pilih Desa</option>
            @foreach(['Desa 1', 'Desa 2', 'Desa 3'] as $desa)
                <option value="{{ $desa }}" {{ request('desa') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
            @endforeach
        </select>
        <select name="jenis_kelamin" class="border px-4 py-2">
            <option value="">Pilih Jenis Kelamin</option>
            <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <select name="usia" class="border px-4 py-2">
            <option value="">Pilih Usia</option>
            <option value="0-17" {{ request('usia') == '0-17' ? 'selected' : '' }}>0-17</option>
            <option value="18-40" {{ request('usia') == '18-40' ? 'selected' : '' }}>18-40</option>
            <option value="41+" {{ request('usia') == '41+' ? 'selected' : '' }}>41+</option>
        </select>

        <button type="submit" class="bg-cyan-400 hover:bg-cyan-500 text-white px-4 py-2 rounded-md shadow">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>

    <div class="overflow-auto bg-gray-100 rounded shadow">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr class="bg-cyan-700 text-white">
                    <th class="px-3 py-2">No</th>
                    <th class="px-3 py-2">NIK</th>
                    <th class="px-3 py-2">No. KK</th>
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">Jenis Kelamin</th>
                    <th class="px-3 py-2">Tempat Lahir</th>
                    <th class="px-3 py-2">Tanggal Lahir</th>
                    <th class="px-3 py-2">Desa</th>
                    <th class="px-3 py-2">Alamat</th>
                    <th class="px-3 py-2">Status Kependudukan</th>
                    <th class="px-3 py-2">Tanggal Pindah</th>
                    <th class="px-3 py-2">Alamat Baru</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perpindahan as $item)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2">{{ $item->nik }}</td>
                        <td class="px-3 py-2">{{ $item->no_kk }}</td>
                        <td class="px-3 py-2">{{ $item->nama }}</td>
                        <td class="px-3 py-2">{{ $item->jenis_kelamin }}</td>
                        <td class="px-3 py-2">{{ $item->tempat_lahir }}</td>
                        <td class="px-3 py-2">{{ $item->tanggal_lahir }}</td>
                        <td class="px-3 py-2">{{ $item->desa }}</td>
                        <td class="px-3 py-2">{{ $item->alamat }}</td>
                        <td class="px-3 py-2">{{ $item->status_kependudukan }}</td>
                        <td class="px-3 py-2">{{ $item->tanggal_pindah }}</td>
                        <td class="px-3 py-2">{{ $item->alamat_baru }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="px-3 py-2 text-center text-gray-500">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('user.perpindahan.export') }}" class="fixed bottom-6 right-6 bg-cyan-400 text-white px-4 py-2 rounded-md shadow hover:bg-cyan-500">
        <i class="fas fa-print"></i> Cetak
    </a>
</div>
@endsection
