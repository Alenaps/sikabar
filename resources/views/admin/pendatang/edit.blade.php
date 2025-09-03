@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-8">
    <h2 class="text-2xl font-bold mb-8 text-gray-800 border-b border-gray-300 pb-3">
        Edit Data Pendatang
    </h2>

    <form action="{{ route('admin.pendatang.update', $pendatang->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Data Kartu Keluarga --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-5">Data Kartu Keluarga</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No KK</label>
                    <input type="text" name="no_kk" value="{{ old('no_kk', $pendatang->warga->kartu_keluarga->no_kk ?? '') }}" 
                           class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
                    <select name="desa" class="w-full border border-gray-500 rounded h-11 px-3">
                        <option value="">-- Pilih --</option>
                        <option value="BANDAR AGUNG" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'BANDAR AGUNG' ? 'selected' : '' }}>BANDAR AGUNG</option>
                        <option value="SRIBHAWONO" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'SRIBHAWONO' ? 'selected' : '' }}>SRIBHAWONO</option>
                        <option value="SRIMENANTI" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'SRIMENANTI' ? 'selected' : '' }}>SRIMENANTI</option>
                        <option value="SRIPENDOWO" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'SRIPENDOWO' ? 'selected' : '' }}>SRIPENDOWO</option>
                        <option value="SADAR SRIWIJAYA" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'SADAR SRIWIJAYA' ? 'selected' : '' }}>SADAR SRIWIJAYA</option>
                        <option value="MEKAR JAYA" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'MEKAR JAYA' ? 'selected' : '' }}>MEKAR JAYA</option>
                        <option value="WARINGIN JAYA" {{ old('desa', optional($pendatang->warga->kartu_keluarga)->desa) == 'WARINGIN JAYA' ? 'selected' : '' }}>WARINGIN JAYA</option>          
                     </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" 
                              class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 resize-none" required>{{ old('alamat', $pendatang->warga->kartu_keluarga->alamat ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Data Warga --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-5">Data Warga</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $pendatang->warga->nik) }}" 
                           class="w-full border border-gray-400 p-2 rounded-md bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $pendatang->warga->nama) }}" 
                           class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500" required>
                        <option value="L" {{ old('jenis_kelamin', $pendatang->warga->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $pendatang->warga->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                    <select name="agama" class="w-full border border-gray-500 rounded h-11 px-3">
                        <option value="">-- Pilih --</option>
                        <option value="ISLAM" {{ $pendatang->warga->agama == 'ISLAM' ? 'selected' : '' }}>ISLAM</option>
                        <option value="PROTESTAN" {{ $pendatang->warga->agama == 'PROTESTAN' ? 'selected' : '' }}>PROTESTAN</option>
                        <option value="KATOLIK" {{ $pendatang->warga->agama == 'KATOLIK' ? 'selected' : '' }}>KATOLIK</option>
                        <option value="HINDU" {{ $pendatang->warga->agama == 'HINDU' ? 'selected' : '' }}>HINDU</option>
                        <option value="BUDHA" {{ $pendatang->warga->agama == 'BUDHA' ? 'selected' : '' }}>BUDHA</option>
                        <option value="KONGHUCU" {{ $pendatang->warga->agama == 'KONGHUCU' ? 'selected' : '' }}>KONGHUCU</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendatang->warga->tempat_lahir) }}" 
                           class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendatang->warga->tanggal_lahir) }}" 
                           class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hubungan Keluarga</label>
                    <select name="hubungan_dalam_keluarga" class="w-full border border-gray-500 rounded h-11 px-3">
                        <option value="">-- Pilih --</option>
                        <option value="Kepala Keluarga" {{ $pendatang->warga->hubungan_dalam_keluarga == 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                        <option value="Istri" {{ $pendatang->warga->hubungan_dalam_keluarga == 'Istri' ? 'selected' : '' }}>Istri</option>
                        <option value="Anak" {{ $pendatang->warga->hubungan_dalam_keluarga == 'Anak' ? 'selected' : '' }}>Anak</option>
                        <option value="Orang Tua" {{ $pendatang->warga->hubungan_dalam_keluarga == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                        <option value="Lainnya" {{ $pendatang->warga->hubungan_dalam_keluarga == 'Lainnya' ? 'selected' : '' }}>Famili Lain</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Data Pendatang --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-5">Data Pendatang</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lama</label>
                    <textarea name="alamat_lama" rows="2" 
                              class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 resize-none" required>{{ old('alamat_lama', $pendatang->alamat_lama) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Datang</label>
                    <input type="date" name="tanggal_datang" value="{{ old('tanggal_datang', $pendatang->tanggal_datang) }}" 
                           class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500" required>
                </div>
            </div>
        </div>

        {{-- Tombol --}}   
        <button type="submit" 
            class="bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-6 rounded-md shadow transition">
            Update
        </button>
        
    </form>
</div>
@endsection
