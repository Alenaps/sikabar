@extends('layouts.user')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">LIHAT DATA WARGA</h1>

    <form method="GET" action="{{ route('user.warga.index') }}" class="mb-4 space-y-2">

        {{-- Bagian 1: Pencarian --}}
        <div class="flex flex-wrap gap-2 items-center">
            <input 
                type="text" 
                name="cari" 
                placeholder="Cari NIK / Nama / KK" 
                value="{{ request('cari') }}" 
                class="border px-3 py-1 text-sm rounded-md"
            />
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow text-sm"
            >
                <i class="fas fa-search mr-1"></i> Cari
            </button>
        </div>

        {{-- Bagian 2: Filter --}}
        <div class="flex flex-wrap gap-2 items-center">
            <select name="bulan" class="border px-3 py-1 text-sm rounded-md">
                <option value="">Pilih Bulan</option>
                @foreach(range(1,12) as $bulan)
                    <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
                    </option>
                @endforeach
            </select>

            <select name="desa" class="border px-3 py-1 text-sm rounded-md">
                <option value="">Pilih Desa</option>
                @foreach(['Bandar Agung','Sribhawono', 'Srimenanti','Sripendowo', 'Sadar Sriwijaya','Mekar Jaya', 'Waringin Jaya'] as $desa)
                    <option value="{{ $desa }}" {{ request('desa') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
                @endforeach
            </select>

            <select name="jenis_kelamin" class="border px-3 py-1 text-sm rounded-md">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>

            
            <input type="number" name="usia_min" value="{{ request('usia_min') }}" placeholder="Usia min" class="border px-3 py-1 text-sm rounded-md w-24"/>
            <input type="number" name="usia_max" value="{{ request('usia_max') }}" placeholder="Usia max" class="border px-3 py-1 text-sm rounded-md w-24"/>
            
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow text-sm">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </div>

    </form>

        <div class="overflow-auto bg-gray-100 rounded shadow">
            <table class="min-w-full text-xs text-left">
                <thead>
                    <tr class="bg-cyan-700 text-white">
                        <th class="border px-2 py-1">No</th>
                        <th class="border px-3 py-2">NIK</th>
                        <th class="border px-3 py-2">No KK</th>
                        <th class="border px-3 py-2">Nama</th>
                        <th class="border px-3 py-2">JK</th>
                        <th class="border px-3 py-2">Tempat Lahir</th>
                        <th class="border px-3 py-2">Tgl Lahir</th>
                        <th class="border px-3 py-2">Agama</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2">Hub. Keluarga</th>
                        <th class="border px-3 py-2">Desa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wargas as $warga)
                    <tr class="border-b">
                        <td class="border px-2 py-1">{{ $loop->iteration }}</td>
                        <td class="border px-3 py-2">{{ $warga->nik }}</td>
                        <td class="border px-3 py-2">{{ $warga->kartu_keluarga->no_kk ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $warga->nama }}</td>
                        <td class="border px-3 py-2">{{ $warga->jenis_kelamin }}</td>
                        <td class="border px-3 py-2">{{ $warga->tempat_lahir }}</td>
                        <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($warga->tanggal_lahir)->format('d-m-Y') }}</td>
                        <td class="border px-3 py-2">{{ $warga->agama }}</td>
                        <td class="border px-3 py-2">{{ $warga->status_kependudukkan }}</td>
                        <td class="border px-3 py-2">{{ $warga->hubungan_dalam_keluarga }}</td>
                        <td class="border px-3 py-2">{{ $warga->kartu_Keluarga->desa ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table> 
        </div>

        <div class="flex gap-2 mt-4">
                <a href="{{ route('user.warga.exportPdf', request()->query()) }}" 
                target="_blank" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>

                <a href="{{ route('user.warga.exportExcel', request()->query()) }}" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                <i class="fas fa-file-excel mr-1"></i> Cetak Excel
                </a>
        </div>
        
    <div class="mt-4">
        {{ $wargas->links() }}
    </div>
</div>
@endsection
