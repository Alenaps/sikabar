@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">BERANDA</h1>
<p class="mb-6">Halo, Admin {{ Auth::user()->name }}</p>

<!-- Main Menu -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('admin.beranda') }}" class="bg-gradient-to-r from-green-300 to-green-500 hover:opacity-90 p-6 rounded-lg shadow text-center font-bold">BERANDA</a>
    <a href="{{ route('admin.Warga') }}" class="bg-gradient-to-r from-yellow-300 to-yellow-500 hover:opacity-90 p-6 rounded-lg shadow text-center font-bold">DATA WARGA</a>
    <a href="{{ route('admin.kartuKeluarga') }}" class="bg-gradient-to-r from-orange-300 to-orange-500 hover:opacity-90 p-6 rounded-lg shadow text-center font-bold">KARTU KELUARGA</a>
</div>

<!-- Kelola Data -->
<h2 class="text-xl font-bold mb-4">KELOLA DATA</h2>
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <a href="{{ route('admin.perpindahan') }}" class="bg-cyan-300 hover:bg-cyan-400 p-4 rounded shadow text-center font-semibold">Perpindahan</a>
    <a href="{{ route('admin.pendatangBaru') }}" class="bg-blue-300 hover:bg-blue-400 p-4 rounded shadow text-center font-semibold">Pendatang Baru</a>
    <a href="{{ route('admin.kelahiran') }}" class="bg-teal-300 hover:bg-teal-400 p-4 rounded shadow text-center font-semibold">Kelahiran</a>
    <a href="{{ route('admin.kematian') }}" class="bg-purple-300 hover:bg-purple-400 p-4 rounded shadow text-center font-semibold">Kematian</a>
</div>
@endsection
