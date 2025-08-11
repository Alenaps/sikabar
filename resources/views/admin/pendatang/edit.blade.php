@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Edit Data Pendatang</h2>
    <form action="{{ route('admin.pendatang.update', $pendatang->id) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block font-medium">NIK</label>
            <select name="nik" class="w-full border p-2 border-gray-500 rounded">
                @foreach($wargas as $warga)
                    <option value="{{ $warga->nik }}" {{ $pendatang->nik == $warga->nik ? 'selected' : '' }}>
                        {{ $warga->nik }} - {{ $warga->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Alamat Lama</label>
            <input type="text" name="alamat_lama" value="{{ $pendatang->alamat_lama }}" class="w-full border p-2 border-gray-500 rounded" required>
        </div>

        <div>
            <label class="block font-medium">Tanggal Datang</label>
            <input type="date" name="tanggal_datang" value="{{ $pendatang->tanggal_datang }}" class="w-full border p-2 border-gray-500 rounded" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
