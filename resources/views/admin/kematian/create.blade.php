@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Data Kematian</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kematian.store') }}" method="POST">
        @csrf       

        <div class="mb-4">
            <label class="block font-medium">NIK</label>
            <select name="nik" id="nik" class="select2 w-full border-gray-500 rounded">
                <option value="">-- Pilih NIK --</option>
                @foreach ($wargas as $warga)
                    <option value="{{ $warga->nik }}">{{ $warga->nik }} - {{ $warga->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="tanggal_kematian" class="block font-semibold">Tanggal Kematian</label>
            <input type="date" name="tanggal_kematian" id="tanggal_kematian" class="border p-2 w-full border-gray-500 rounded" required>
        </div>

        <div class="mb-4">
            <label for="tempat_kematian" class="block font-semibold">Tempat Kematian</label>
            <input type="text" name="tempat_kematian" id="tempat_kematian" class="border p-2 w-full border-gray-500 rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
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