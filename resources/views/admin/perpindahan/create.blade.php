@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Data Perpindahan</h2>
    <form action="{{ route('admin.perpindahan.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">NIK</label>
            <select name="nik" class="w-full border p-2">
                @foreach($wargas as $warga)
                    <option value="{{ $warga->nik }}">{{ $warga->nik }} - {{ $warga->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Alamat Baru</label>
            <input type="text" name="alamat_baru" class="w-full border p-2" required>
        </div>

        <div>
            <label class="block font-medium">Tanggal Pindah</label>
            <input type="date" name="tanggal_pindah" class="w-full border p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
