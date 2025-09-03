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

    {{-- Data untuk Chart --}}
    <div id="chart-data"
        data-labels-bulan-ini='@json(array_keys($statistikBulanIni->toArray()))'
        data-labels-bulan-lalu='@json(array_keys($statistikBulanLalu->toArray()))'
        data-data-bulan-ini='@json($statistikBulanIni)'
        data-data-bulan-lalu='@json($statistikBulanLalu)'>
    </div>

    {{-- Grafik Statistik --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Perbandingan Jumlah Warga per Desa</h2>
            <canvas id="desaChart" height="120"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Komposisi Jenis Kelamin</h2>
            <canvas id="chartGender" height="120"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Distribusi Kelompok Usia</h2>
            <canvas id="chartUsia" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const el = document.getElementById('chart-data');

    const labelsBulanIni = JSON.parse(el.dataset.labelsBulanIni);
    const labelsBulanLalu = JSON.parse(el.dataset.labelsBulanLalu);
    const dataBulanIni = JSON.parse(el.dataset.dataBulanIni);
    const dataBulanLalu = JSON.parse(el.dataset.dataBulanLalu);

    const labels = [...new Set([...labelsBulanIni, ...labelsBulanLalu])];

    // Chart: Desa
    new Chart(document.getElementById('desaChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Bulan Ini',
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 6,
                    data: labels.map(label => dataBulanIni[label] || 0)
                },
                {
                    label: 'Bulan Lalu',
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 6,
                    data: labels.map(label => dataBulanLalu[label] || 0)
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: context => context.dataset.label + ': ' + (context.raw || 0).toLocaleString("id-ID")
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => Number.isInteger(value) ? value.toLocaleString("id-ID") : null
                    }
                }
            }
        }
    });

    // Data Gender & Usia
    const dataUsia = JSON.parse('@json($dataUsia)');
    const dataGender = JSON.parse('@json($dataGender)');


    // Chart: Gender
    new Chart(document.getElementById('chartGender'), {
        type: 'doughnut',
        data: {
            labels: dataGender.labels,
            datasets: [{
                data: dataGender.counts,
                backgroundColor: ['#3B82F6', '#F472B6'],
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: context => context.label + ': ' + context.raw.toLocaleString("id-ID")
                    }
                }
            }
        }
    });

    // Chart: Usia
    new Chart(document.getElementById('chartUsia'), {
        type: 'bar',
        data: {
            labels: dataUsia.labels,
            datasets: [{
                label: 'Jumlah',
                data: dataUsia.counts,
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => context.dataset.label + ': ' + context.raw.toLocaleString("id-ID")
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => Number.isInteger(value) ? value.toLocaleString("id-ID") : null
                    }
                }
            }
        }
    });
});
</script>
@endsection
