@extends('layouts.admin')

@section('content')
<div class="p-6">
   <h2>No KK: {{ $kk->no_kk }}</h2>
    <p>Alamat: {{ $kk->alamat }}</p>

    <h3>Anggota Keluarga:</h3>
    <table>
        <tr>
            <th>NIK</th><th>Nama</th><th>Hubungan</th>
        </tr>
        @foreach ($kk->anggota as $w)
            <tr>
                <td>{{ $w->nik }}</td>
                <td>{{ $w->nama }}</td>
                <td>{{ $w->hubungan_dalam_keluarga }}</td>
            </tr>
        @endforeach
    </table>

    <a href="{{ route('admin.warga.create', ['no_kk' => $kk->no_kk]) }}">+ Tambah Anggota</a>


</div>
@endsection
