@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Edit Kartu Keluarga</h2>

    <form action="{{ route('admin.kartukeluarga.update', $kartu_keluarga) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="no_kk" class="block font-medium">No KK</label>
            <input 
                type="text" 
                name="no_kk" 
                id="no_kk" 
                value="{{ old('no_kk', $kartu_keluarga->no_kk) }}" 
                class="w-full border border-gray-500 rounded p-2" 
                required
            >
        </div>

        <div>
            <label for="alamat" class="block font-medium">Alamat</label>
            <input 
                type="text" 
                name="alamat" 
                id="alamat" 
                value="{{ old('alamat', $kartu_keluarga->alamat) }}" 
                class="w-full border border-gray-500 rounded p-2" 
                required
            >
        </div>

        <div>
            <label for="desa" class="block font-medium">Desa</label>
            <select name="desa" class="w-full border border-gray-500 rounded h-11 px-3">
              <option value="">-- Pilih --</option>
                        <option value="BANDAR AGUNG" {{ $kartu_keluarga->desa == 'BANDAR AGUNG' ? 'selected' : '' }}>BANDAR AGUNG</option>
                        <option value="SRIBHAWONO" {{ $kartu_keluarga->desa == 'SRIBHAWONO' ? 'selected' : '' }}>SRIBHAWONO</option>
                        <option value="SRIMENANTI" {{ $kartu_keluarga->desa == 'SRIMENANTI' ? 'selected' : '' }}>SRIMENANTI</option>
                        <option value="SRIPENDOWO" {{ $kartu_keluarga->desa == 'SRIPENDOWO' ? 'selected' : '' }}>SRIPENDOWO</option>
                        <option value="SADAR SRIWIJAYA" {{ $kartu_keluarga->desa == 'SADAR SRIWIJAYA' ? 'selected' : '' }}>SADAR SRIWIJAYA</option>
                        <option value="MEKAR JAYA" {{ $kartu_keluarga->desa == 'MEKAR JAYA' ? 'selected' : '' }}>MEKAR JAYA</option>
                        <option value="WARINGIN JAYA" {{ $kartu_keluarga->desa == 'WARINGIN JAYA' ? 'selected' : '' }}>WARINGIN JAYA</option>          
            </select>
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
    </form>
</div>
@endsection
