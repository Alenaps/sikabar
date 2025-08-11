@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Data Kelahiran</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kelahiran.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">No KK</label>
                <select name="no_kk" class="select2 w-full border border-gray-500 rounded h-11 px-3" required>
                    <option value="">-- Pilih No KK--</option>
                    @foreach($kartu_keluarga as $kk)
                        <option value="{{ $kk->no_kk }}">{{ $kk->no_kk }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Nama Bayi</label>
                <input type="text" name="nama_bayi" class="w-full border border-gray-500 rounded h-11 px-3" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full border border-gray-500 rounded h-11 px-3" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="w-full border border-gray-500 rounded h-11 px-3" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="w-full border border-gray-500 rounded h-11 px-3" required>
            </div>

            <div>
                <label class="block font-medium mb-1">NIK Ayah</label>
                <select name="nik_ayah" id="nik_ayah" class="select2 w-full border border-gray-500 rounded h-11 px-3">
                    <option value="">-- Pilih NIK Ayah --</option>
                    @foreach ($ayahs as $ayah)
                        <option value="{{ $ayah->nik }}">{{ $ayah->nik }} - {{ $ayah->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">NIK Ibu</label>
                <select name="nik_ibu" id="nik_ibu" class="select2 w-full border border-gray-500 rounded h-11 px-3">
                    <option value="">-- Pilih NIK Ibu --</option>
                    @foreach ($ibus as $ibu)
                        <option value="{{ $ibu->nik }}">{{ $ibu->nik }} - {{ $ibu->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Biar select2 tingginya sama dengan input */
    .select2-selection {
        height: 44px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-selection__rendered {
        line-height: 42px !important;
    }
    .select2-selection__arrow {
        height: 42px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width: 'resolve'
        });
    });
</script>
@endpush
