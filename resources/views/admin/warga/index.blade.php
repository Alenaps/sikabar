@extends('layouts.admin')

@section('title', 'Data Warga')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Data Warga</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-2">
        <form method="GET" action="{{ route('admin.warga.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" placeholder="Cari Nama / NIK / No KK" value="{{ request('search') }}"
                class="border rounded px-4 py-2 w-full sm:w-64">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
        </form>

        <a href="{{ route('admin.warga.create') }}" class="bg-green-500 text-white px-4 py-2 rounded text-center">+ Tambah Warga</a>
    </div>

    <div class="overflow-auto bg-gray-100 rounded shadow">
        <table class="min-w-full text-xs text-left">
            <thead>
                <tr class="bg-cyan-700 text-white">
                        <th class="border px-3 py-2 text-center">No</th>
                        <th class="border px-3 py-2 text-center">NIK</th>
                        <th class="border px-3 py-2 text-center">No KK</th>
                        <th class="border px-3 py-2 text-center">Nama</th>
                        <th class="border px-3 py-2 text-center">Jenis Kelamin</th>
                        <th class="border px-3 py-2 text-center">Tempat Lahir</th>
                        <th class="border px-3 py-2 text-center">Tgl Lahir</th>
                        <th class="border px-3 py-2 text-center">Agama</th>
                        <th class="border px-3 py-2 text-center">Status</th>
                        <th class="border px-3 py-2 text-center">Hub. Keluarga</th>
                        <th class="border px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($wargas as $warga)
                    <tr>
                        <td class="border px-3 py-2 text-center">{{ $loop->iteration }}</td>
                        <td class="border px-3 py-2">{{ $warga->nik }}</td>
                        <td class="border px-3 py-2">{{ $warga->kartu_keluarga->no_kk ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $warga->nama }}</td>
                        <td class="border px-3 py-2">{{ $warga->jenis_kelamin }}</td>
                        <td class="border px-3 py-2">{{ $warga->tempat_lahir }}</td>
                        <td class="border px-3 py-2">{{ $warga->tanggal_lahir }}</td>
                        <td class="border px-3 py-2">{{ $warga->agama }}</td>
                        <td class="border px-3 py-2">
                            {{ $warga->status_kependudukkan ?? '-' }}
                        </td>

                        <td class="border px-3 py-2 whitespace-normal">
                            {{ $warga->hubungan_dalam_keluarga ?? '-' }}
                        </td>
                        <td class="px-4 py-2 border space-x-1">
                                <a href="{{ route('admin.warga.edit', $warga) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                                <form action="{{ route('admin.warga.destroy', $warga) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
        {{ $wargas->withQueryString()->links() }}
    </div>
</div>
@endsection
