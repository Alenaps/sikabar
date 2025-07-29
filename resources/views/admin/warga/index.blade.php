@extends('layouts.admin')

@section('title', 'Data Warga')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Data Warga</h1>

    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-2">
        <form method="GET" action="{{ route('admin.warga.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" placeholder="Cari Nama / NIK / No KK" value="{{ request('search') }}"
                class="border rounded px-4 py-2 w-full sm:w-64">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
        </form>

        <a href="{{ route('admin.warga.create') }}" class="bg-green-500 text-white px-4 py-2 rounded text-center">+ Tambah Warga</a>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-3 py-2 text-left">NIK</th>
                    <th class="px-3 py-2 text-left">No KK</th>
                    <th class="px-3 py-2 text-left">Nama</th>
                    <th class="px-3 py-2 text-left">JK</th>
                    <th class="px-3 py-2 text-left">Tempat Lahir</th>
                    <th class="px-3 py-2 text-left">Tgl Lahir</th>
                    <th class="px-3 py-2 text-left">Agama</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">Hub. Keluarga</th>
                    <th class="px-3 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($wargas as $warga)
                <tr>
                    <td class="px-3 py-2">{{ $warga->nik }}</td>
                    <td class="px-3 py-2">{{ $warga->kartu_keluarga->no_kk ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $warga->nama }}</td>
                    <td class="px-3 py-2">{{ $warga->jenis_kelamin }}</td>
                    <td class="px-3 py-2">{{ $warga->tempat_lahir }}</td>
                    <td class="px-3 py-2">{{ $warga->tanggal_lahir }}</td>
                    <td class="px-3 py-2">{{ $warga->agama }}</td>
                    <td class="px-3 py-2">
                        {{ $warga->status_kependudukkan ?? '-' }}
                    </td>

                    <td class="px-3 py-2 whitespace-normal">
                        {{ $warga->hubungan_dalam_keluarga ?? '-' }}
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.warga.edit', $warga->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.warga.destroy', $warga->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
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
