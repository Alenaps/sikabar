@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Data Kematian</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kematian.update', $kematian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nik" class="block font-semibold">NIK Warga</label>
            <select name="nik" id="nik" class="border p-2 w-full select2" disabled>
                @foreach($wargas as $warga)
                    <option value="{{ $warga->nik }}" {{ $kematian->nik == $warga->nik ? 'selected' : '' }}>
                        {{ $warga->nik }} - {{ $warga->nama }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="nik" value="{{ $kematian->nik }}">
        </div>

        <div class="mb-4">
            <label for="tanggal_kematian" class="block font-semibold">Tanggal Kematian</label>
            <input type="date" name="tanggal_kematian" id="tanggal_kematian" value="{{ $kematian->tanggal_kematian }}" class="border p-2 w-full" required>
        </div>

        <div class="mb-4">
            <label for="tempat_kematian" class="block font-semibold">Tempat Kematian</label>
            <input type="text" name="tempat_kematian" id="tempat_kematian" value="{{ $kematian->tempat_kematian }}" class="border p-2 w-full" required>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
