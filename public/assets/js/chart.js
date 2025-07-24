const ctx = document.getElementById('pendudukChart').getContext('2d');
const pendudukChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Desa A', 'Desa B', 'Desa C', 'Desa D'],
        datasets: [{
            label: 'Jumlah Penduduk',
            data: [120, 150, 180, 100],
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
