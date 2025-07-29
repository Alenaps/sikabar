@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Data Perpindahan</h1>
    <a href="{{ route('admin.perpindahan.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ Tambah Perpindahan</a>

    <table class="w-full mt-4 border border-gray-200 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">NIK</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Alamat Baru</th>
                <th class="p-2">Tanggal Pindah</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perpindahan as $data)
                <tr>
                    <td class="p-2">{{ $data->nik }}</td>
                    <td class="p-2">{{ $data->nama }}</td>
                    <td class="p-2">{{ $data->alamat_baru }}</td>
                    <td class="p-2">{{ $data->tanggal_pindah }}</td>
                    <td class="p-2">
                        <a href="{{ route('admin.perpindahan.edit', $data->id) }}" class="text-blue-600">Edit</a> |
                        <form action="{{ route('admin.perpindahan.destroy', $data->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $perpindahan->links() }}
    </div>
</div>
@endsection
