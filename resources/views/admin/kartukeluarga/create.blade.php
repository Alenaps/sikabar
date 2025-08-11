@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Kartu Keluarga</h2>

    <form action="{{ route('admin.kartukeluarga.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="no_kk" class="block font-medium">No KK</label>
            <input type="text" name="no_kk" id="no_kk" class="w-full border rounded p-2 border-gray-500 rounded" required>
        </div>

        <div>
            <label for="alamat" class="block font-medium">Alamat</label>
            <input type="text" name="alamat" id="alamat" class="w-full border rounded p-2 border-gray-500 rounded" required>
        </div>

        <div>
            <label for="desa" class="block font-medium">Desa</label>
            <input type="text" name="desa" id="desa" class="w-full border rounded p-2 border-gray-500 rounded" required>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
    </form>
</div>
@endsection
