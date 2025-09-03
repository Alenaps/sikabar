@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-8">
    <h2 class="text-2xl font-bold mb-8 text-gray-800 border-b border-gray-300 pb-3">
        Tambah Data Pendatang
    </h2>

    <form action="{{ route('admin.pendatang.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Data Kartu Keluarga --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-5">Data Kartu Keluarga</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No KK</label>
                    <input type="text" name="no_kk" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
                    <select name="desa" class="w-full border border-gray-500 rounded h-11 px-3">
                        <option value="">-- Pilih --</option>
                        <option value="BANDAR AGUNG">BANDAR AGUNG</option>
                        <option value="SRIBHAWONO">SRIBHAWONO</option>
                        <option value="SRIMENANTI">SRIMENANTI</option>
                        <option value="SRIPENDOWO">SRIPENDOWO</option>
                        <option value="SADAR SRIWIJAYA">SADAR SRIWIJAYA</option>
                        <option value="MEKAR JAYA">MEKAR JAYA</option>
                        <option value="WARINGIN JAYA">WARINGIN JAYA</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500 resize-none" required></textarea>
                </div>
            </div>
        </div>

        {{-- Data Warga --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-5">Data Warga</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                    <select name="agama" class="w-full border border-gray-500 rounded h-11 px-3">
                        <option value="">-- Pilih --</option>
                        <option value="ISLAM">ISLAM</option>
                        <option value="PROTESTAN">PROTESTAN</option>
                        <option value="KATOLIK">KATOLIK</option>
                        <option value="HINDU">HINDU</option>
                        <option value="BUDHA">BUDHA</option>
                        <option value="KONGHUCU">KONGHUCU</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hubungan Keluarga</label>
                    <select name="hubungan_dalam_keluarga" class="w-full border border-gray-500 rounded h-11 px-3">
                    <option value="">-- Pilih --</option>
                    <option value="Kepala Keluarga">Kepala Keluarga</option>
                    <option value="Istri">Istri</option>
                    <option value="Anak">Anak</option>
                    <option value="Orang Tua">Orang Tua</option>
                    <option value="Lainnya">Famili Lain</option>
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
                    <textarea name="alamat_lama" rows="2" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500 resize-none" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Datang</label>
                    <input type="date" name="tanggal_datang" class="w-full border border-gray-400 p-2 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500" required>
                </div>
            </div>
        </div>

        {{-- Tombol --}} 
        <button type="submit" class="bg-blue-700 hover:bg-gray-800 text-white font-semibold py-2 px-6 rounded-md shadow transition">
            Simpan
        </button> 
    </form>
</div>
@endsection
