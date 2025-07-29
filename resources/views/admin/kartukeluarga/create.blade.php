@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Tambah Kartu Keluarga</h2>

    <form action="{{ route('admin.kartukeluarga.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="no_kk" class="block">No KK</label>
            <input type="text" name="no_kk" id="no_kk" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label for="alamat" class="block">Alamat</label>
            <input type="text" name="alamat" id="alamat" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label for="desa" class="block">Desa</label>
            <input type="text" name="desa" id="desa" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
    </form>
</div>
@endsection
