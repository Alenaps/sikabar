@extends('layouts.admin')

@section('title', 'Data Kelahiran')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Data Kelahiran</h1>
    
    @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
    @endif

     <div class="flex justify-between mb-4">
        <form method="GET" action="{{ route('admin.kelahiran.index') }}">
            <input type="text" name="search" placeholder="Cari Nama Bayi / NO KK " value="{{ request('search') }}"class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
        </form>

        <a href="{{ route('admin.kelahiran.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ Tambah Kelahiran</a>
    </div>
    
    <div class="overflow-auto bg-gray-100 rounded shadow">
         <table class="min-w-full text-xs text-left">
             <thead>
                <tr class="bg-cyan-700 text-white">
                    <th class="border px-3 py-2">No</th>
                    <th class="border px-3 py-2">No KK</th>
                    <th class="border px-3 py-2">Nama Bayi</th>
                    <th class="border px-3 py-2">Jenis Kelamin</th>
                    <th class="border px-3 py-2">Tempat Lahir</th>
                    <th class="border px-3 py-2">Tanggal Lahir</th>
                    <th class="border px-3 py-2">NIK Ayah</th>
                    <th class="border px-3 py-2">NIK Ibu</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($kelahiran as $data)
                <tr>
                    <td class="border px-3 py-2 text-center">{{ $loop->iteration }}</td>
                    <td class="border px-3 py-2">{{ $data->kartu_keluarga->no_kk ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ $data->nama_bayi }}</td>
                    <td class="border px-3 py-2">{{ $data->jenis_kelamin }}</td>
                    <td class="border px-3 py-2">{{ $data->tempat_lahir }}</td>
                    <td class="border px-3 py-2">{{ $data->tanggal_lahir }}</td>
                    <td class="border px-3 py-2">{{ $data->nik_ayah }} - {{ $data->ayah?->nama }}</td>
                    <td class="border px-3 py-2">{{ $data->nik_ibu }} - {{ $data->ibu?->nama }}</td>
                    <td class="border px-4 text-center py-2 border space-x-1">
                            <a href="{{ route('admin.kelahiran.edit', $data->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                            <form action="{{ route('admin.kelahiran.destroy', $data->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kelahiran->withQueryString()->links() }}
    </div>
</div>
@endsection
