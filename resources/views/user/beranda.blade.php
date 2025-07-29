@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Statistik Kependudukan</h1>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-stat-card title="Total Warga" :value="$jumlahWarga" color="blue" />
        <x-stat-card title="Total Pendatang" :value="$jumlahPendatang" color="green" />
        <x-stat-card title="Total Perpindahan" :value="$jumlahPerpindahan" color="yellow" />
        <x-stat-card title="Total Kelahiran" :value="$jumlahKelahiran" color="indigo" />
        <x-stat-card title="Total Kematian" :value="$jumlahKematian" color="red" />
        <x-stat-card title="Total Kartu Keluarga" :value="$jumlahKartuKeluarga" color="purple" />
    </div>

    {{-- Grafik Statistik --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Perbandingan Jumlah Warga per Desa</h2>
        <canvas id="desaChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('desaChart').getContext('2d');

    const labels = @json(array_keys($statistikBulanIni->toArray() + $statistikBulanLalu->toArray()));
    const dataBulanIni = @json($statistikBulanIni);
    const dataBulanLalu = @json($statistikBulanLalu);

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Bulan Ini',
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    data: labels.map(label => dataBulanIni[label] || 0),
                },
                {
                    label: 'Bulan Lalu',
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    data: labels.map(label => dataBulanLalu[label] || 0),
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Statistik Warga Bulanan per Desa',
                },
            },
        },
    });
</script>
@endsection
