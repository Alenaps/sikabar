@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Data Perpindahan</h2>
    <form action="{{ route('admin.perpindahan.store') }}" method="POST" class="space-y-4">
        @csrf

        <div >
            <label class="block font-medium">NIK</label>
            <select name="nik" id="nik" class="select2 w-full">
                <option value="">-- Pilih NIK --</option>
                @foreach ($wargas as $warga)
                    <option value="{{ $warga->nik }}">{{ $warga->nik }} - {{ $warga->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Alamat Baru</label>
            <input type="text" name="alamat_baru" class="w-full border p-2 border-gray-500 rounded" required>
        </div>

        <div>
            <label class="block font-medium">Tanggal Pindah</label>
            <input type="date" name="tanggal_pindah" class="w-full border p-2 border-gray-500 rounded" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Biar select2 tingginya sama dengan input*/
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