@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Edit Kartu Keluarga</h2>

    <form action="{{ route('admin.kartukeluarga.update', $kartu_keluarga) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="no_kk" class="block">No KK</label>
            <input type="text" name="no_kk" id="no_kk" class="w-full border rounded p-2" value="{{ old('no_kk', $kartu_keluarga->no_kk) }}" required>
        </div>

        <div>
            <label for="alamat" class="block">Alamat</label>
            <input type="text" name="alamat" id="alamat" class="w-full border rounded p-2" value="{{ old('alamat', $kartu_keluarga->alamat) }}" required>
        </div>

        <div>
            <label for="desa" class="block">Desa</label>
            <input type="text" name="desa" id="desa" class="w-full border rounded p-2" value="{{ old('desa', $kartu_keluarga->desa) }}" required>
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Perbarui</button>
    </form>
</div>
@endsection
