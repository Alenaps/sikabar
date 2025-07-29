@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Data Warga</h2>

    <form action="{{ route('admin.warga.update', $warga->nik) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">NIK</label>
            <input type="text" name="nik" value="{{ $warga->nik }}" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">No KK</label>
            <select name="kartu_keluarga_id" class="w-full border p-2" required>
                @foreach($kartu_keluarga as $kk)
                    <option value="{{ $kk->id }}" {{ $kk->id == $warga->kartu_keluarga_id ? 'selected' : '' }}>
                        {{ $kk->no_kk }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Nama</label>
            <input type="text" name="nama" value="{{ $warga->nama }}" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="w-full border p-2" required>
                <option value="L" {{ $warga->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $warga->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div>
            <label class="block font-medium">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" value="{{ $warga->tempat_lahir }}" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ $warga->tanggal_lahir }}" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Agama</label>
            <input type="text" name="agama" value="{{ $warga->agama }}" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Status Kependudukan</label>
            <input type="text" name="status_kependudukkan" value="{{ $warga->status_kependudukkan }}" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Hubungan dalam Keluarga</label>
            <input type="text" name="hubungan_dalam_keluarga" value="{{ $warga->hubungan_dalam_keluarga }}" class="w-full border p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
