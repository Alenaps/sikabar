@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Data Kematian</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between mb-4">
        <form method="GET" action="{{ route('admin.kelahiran.index') }}">
            <input type="text" name="search" placeholder="Cari NIK / Nama " value="{{ request('search') }}"class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
        </form>

        <a href="{{ route('admin.kematian.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ Tambah Kematian</a>
    </div>

    
   <div class="overflow-auto bg-gray-100 rounded shadow">
    <table class="min-w-full text-xs text-left">
        <thead>
            <tr class="bg-cyan-700 text-white">
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">NIK</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Tanggal Kematian</th>
                    <th class="px-4 py-2 border">Tempat Kematian</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kematian as $data)
                    <tr>
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $data->nik }}</td>
                        <td class="px-4 py-2 border">{{ $data->nama ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $data->tanggal_kematian }}</td>
                        <td class="px-4 py-2 border">{{ $data->tempat_kematian }}</td>
                        <td class="px-4 py-2 border space-x-1">
                            <a href="{{ route('admin.kematian.edit', $data->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                            <form action="{{ route('admin.kematian.destroy', $data->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data kematian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
