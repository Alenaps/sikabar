@extends('layouts.admin')

@section('title', 'Beranda Admin')

@section('content')
  <h2 class="text-2xl font-bold mb-2 text-gray-800">Beranda</h2>
  <p class="mb-6 text-gray-600">Selamat datang, Admin Desa</p>

  <!-- Ringkasan -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
    <a href="{{ route('admin.beranda') }}" class="bg-green-400 hover:bg-green-500 text-white text-center p-4 rounded-lg shadow font-semibold transition">Beranda</a>
    <a href="{{ route('admin.warga.index') }}" class="bg-yellow-400 hover:bg-yellow-500 text-white text-center p-4 rounded-lg shadow font-semibold transition">Data Warga</a>
    <a href="{{ route('admin.kartukeluarga.index') }}" class="bg-orange-400 hover:bg-orange-500 text-white text-center p-4 rounded-lg shadow font-semibold transition">Kartu Keluarga</a>
  </div>

  <!-- Kelola Data -->
  <h3 class="text-lg font-semibold text-center mb-4 text-gray-700">Kelola Data</h3>
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    <a href="{{ route('admin.perpindahan.index') }}" class="bg-cyan-400 hover:bg-cyan-500 text-white p-4 text-center rounded-lg shadow transition">Perpindahan</a>
    <a href="{{ route('admin.pendatang.index') }}" class="bg-cyan-400 hover:bg-cyan-500 text-white p-4 text-center rounded-lg shadow transition">Pendatang</a>
    <a href="{{ route('admin.kelahiran.index') }}" class="bg-cyan-400 hover:bg-cyan-500 text-white p-4 text-center rounded-lg shadow transition">Kelahiran</a>
    <a href="{{ route('admin.kematian.index') }}" class="bg-cyan-400 hover:bg-cyan-500 text-white p-4 text-center rounded-lg shadow transition">Kematian</a>
  </div>
@endsection
