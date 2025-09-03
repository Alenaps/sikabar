@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Data Kartu Keluarga</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between mb-4">
        <form method="GET" action="{{ route('admin.kartukeluarga.index') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari KK..." class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
        </form>

        <form action="{{ route('admin.kartukeluarga.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file" class="border rounded px-2 py-1 text-sm" required>
            <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded text-sm">Import</button>
        </form>

        <a href="{{ route('admin.kartukeluarga.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ Tambah KK</a>
    </div>

    <div class="overflow-auto bg-gray-100 rounded shadow">
    <table class="min-w-full text-xs text-left">
        <thead>
            <tr class="bg-cyan-700 text-white">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">No KK</th>
                    <th class="border px-4 py-2">Alamat</th>
                    <th class="border px-4 py-2">Desa</th>
                    <th class="border px-4 py-2">Jumlah Anggota</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kartu_keluarga as $kk)
                    <tr>
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $kk->no_kk }}</td>
                        <td class="border px-4 py-2">{{ $kk->alamat }}</td>
                        <td class="border px-4 py-2">{{ $kk->desa }}</td>
                        <td class="border px-4 py-2">{{ $kk->anggota->count() }} anggota</td>
                        <td class="px-4 py-2 border space-x-1">
                            <a href="{{ route('admin.kartukeluarga.edit', $kk->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>
                            <form action="{{ route('admin.kartukeluarga.destroy', $kk->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border px-4 py-2 text-center">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kartu_keluarga->withQueryString()->links() }}
    </div>
</div>
@endsection
