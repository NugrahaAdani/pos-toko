import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const trendCanvas = document.getElementById('chartTren');

if (trendCanvas) {
    import('chart.js/auto').then(({ default: Chart }) => {
        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: JSON.parse(trendCanvas.dataset.labels),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: JSON.parse(trendCanvas.dataset.values),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.12)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback(value) {
                                return `Rp${new Intl.NumberFormat('id-ID').format(value)}`;
                            },
                        },
                    },
                },
            },
        });
    });
}
