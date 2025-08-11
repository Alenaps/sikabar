@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Data Perpindahan</h1>
    
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="flex justify-between mb-4">
        <form method="GET" action="{{ route('admin.perpindahan.index') }}">
            <input type="text" name="search" placeholder="Cari Nama / NIK " value="{{ request('search') }}"class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
        </form>

        <a href="{{ route('admin.perpindahan.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ Tambah Perpindahan</a>
    </div>

 <div class="overflow-auto bg-gray-100 rounded shadow">
    <table class="min-w-full text-xs text-left">
        <thead>
            <tr class="bg-cyan-700 text-white">
                <th class="border px-3 py-2">No</th>
                <th class="border p-2" >NIK</th>
                <th class="border p-2">Nama</th>
                <th class="border p-2">Alamat Baru</th>
                <th class="border p-2">Tanggal Pindah</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perpindahan as $data)
                <tr>
                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $data->nik }}</td>
                    <td class="border p-2">{{ $data->nama }}</td>
                    <td class="border p-2">{{ $data->alamat_baru }}</td>
                    <td class="border p-2">{{ $data->tanggal_pindah }}</td>
                    <td class="border px-4 text-center py-2 border space-x-1">
                            <a href="{{ route('admin.perpindahan.edit', $data->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                            <form action="{{ route('admin.perpindahan.destroy', $data->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <div class="mt-4">
        {{ $perpindahan->links() }}
    </div>
</div>
@endsection
