@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Edit Data Perpindahan</h2>
    <form action="{{ route('admin.perpindahan.update', $perpindahan->id) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block font-medium">NIK</label>
            <select name="nik" class="w-full border p-2 border-gray-500 rounded">
                @foreach($wargas as $warga)
                    <option value="{{ $warga->nik }}" {{ $perpindahan->nik == $warga->nik ? 'selected' : '' }}>
                        {{ $warga->nik }} - {{ $warga->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Alamat Baru</label>
            <input type="text" name="alamat_baru" value="{{ $perpindahan->alamat_baru }}" class="w-full border p-2 border-gray-500 rounded" required>
        </div>

        <div>
            <label class="block font-medium">Tanggal Pindah</label>
            <input type="date" name="tanggal_pindah" value="{{ $perpindahan->tanggal_pindah }}" class="w-full border p-2 border-gray-500 rounded" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
