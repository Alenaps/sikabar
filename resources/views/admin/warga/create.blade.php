@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Tambah Data Warga</h2>

    <form action="{{ route('admin.warga.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">NIK</label>
            <input type="text" name="nik" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">No KK</label>
            <select name="kartu_keluarga_id" class="w-full border p-2" required>
                <option value="">-- Pilih No KK --</option>
                @foreach($kartu_keluarga as $kk)
                  <option value="{{ $kk->id }}" {{ old('kartu_keluarga_id', $warga->kartu_keluarga_id ?? '') == $kk->id ? 'selected' : '' }}>
                        {{ $kk->no_kk }}
                  </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Nama</label>
            <input type="text" name="nama" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="w-full border p-2" required>
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div>
            <label class="block font-medium">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Agama</label>
            <input type="text" name="agama" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Status Kependudukan</label>
             <select name="status_kependudukkan" class="w-full border p-2">
                <option value="">-- Pilih --</option>
                <option value="Warga">Warga</option>
                <option value="Pendatang">Pendatang</Kbd></option>
                <option value="Perpindahan">Perpindahan</option>
                <option value="Kelahiran">Kelahiran</option>
                <option value="Kematian">Kematian</option>
            </select>
        </div>
        

        <div>
            <label for="hubungan_dalam_keluarga"  class="block font-medium">Hubungan dalam Keluarga</label>
             <select name="hubungan_dalam_keluarga" class="w-full border p-2">
                <option value="">-- Pilih --</option>
                <option value="Kepala Keluarga">Kepala Keluarga</option>
                <option value="Istri">Istri</option>
                <option value="Anak">Anak</option>
                <option value="Saudara">Saudara</option>
                <option value="Orang Tua">Orang Tua</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>


        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
