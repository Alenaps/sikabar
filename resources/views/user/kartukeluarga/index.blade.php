@extends('layouts.user')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">LIHAT DATA KARTU KELUARGA</h1>

    <form method="GET" action="{{ route('user.kartukeluarga.index') }}" class="mb-4 space-y-2">

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
  
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow text-sm">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </div>

    </form>

        <div class="overflow-auto bg-gray-100 rounded shadow">
            <table class="min-w-full text-xs text-center">
                <thead>
                    <tr class="bg-cyan-700 text-white text-center">
                        <th class="border px-2 py-1">No</th>
                        <th class="border p-3 ">No KK</th>
                        <th class="border p-3 ">Kepala Keluarga</th>
                        <th class="border p-3 ">Alamat</th>
                        <th class="border p-3 ">Desa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kartu_keluarga as $kk)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="border px-2 py-1">{{ $loop->iteration }}</td>
                            <td class="border p-3">{{ $kk->no_kk }}</td>
                            <td class="border p-3">{{ $kk->kepalaKeluarga->nama ?? '-' }}</td>
                            <td class="border p-3">{{ $kk->alamat }}</td>
                            <td class="border p-3">{{ $kk->desa }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-5 text-gray-500">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>     
        </div>
    
        <div class="flex gap-2 mt-4">
            <a href="{{ route('user.kartukeluarga.exportPdf', request()->query()) }}" 
            target="_blank" 
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
            <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
            </a>

            <a href="{{ route('user.kartukeluarga.exportExcel', request()->query()) }}" 
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
            <i class="fas fa-file-excel mr-1"></i> Cetak Excel
            </a>
        </div>

    <div class="mt-4">
        {{ $kartu_keluarga->links() }}
    </div>
</div>
@endsection
