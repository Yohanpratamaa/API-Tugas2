document.addEventListener("DOMContentLoaded", function () {
    const apiMasukUrl = "http://localhost:8000/api/inventories";
    const apiKeluarUrl = "http://localhost:8000/api/inventory-outs-all";

    // Fungsi untuk memformat tanggal menjadi YYYY-MM-DD
    function formatDateOnly(dateString) {
        if (!dateString) return null;
        const date = new Date(dateString);
        return date.toISOString().split('T')[0]; // Hanya ambil bagian tanggal
    }

    // Fungsi untuk menghitung data per tanggal
    function aggregateDataByDate(data, dateField) {
        const counts = {};
        data.forEach(item => {
            const date = formatDateOnly(item[dateField]);
            if (date) {
                counts[date] = (counts[date] || 0) + 1;
            }
        });
        return counts;
    }

    // Fungsi untuk fetch data dengan fallback jika gagal
    function fetchData(url) {
        return axios.get(url)
            .then(response => {
                if (response.data.status === "success" && Array.isArray(response.data.data)) {
                    return response.data.data;
                }
                return []; // Kembalikan array kosong jika data tidak valid
            })
            .catch(error => {
                console.error(`Error fetching ${url}:`, error);
                return []; // Kembalikan array kosong jika gagal
            });
    }

    Promise.all([fetchData(apiMasukUrl), fetchData(apiKeluarUrl)])
        .then(([masukData, keluarData]) => {
            // Debugging
            console.log("Masuk Data:", masukData);
            console.log("Keluar Data:", keluarData);

            // 1. Proses data Masuk
            const masukByDate = aggregateDataByDate(masukData, 'created_at');
            const tanggalMasuk = Object.keys(masukByDate).sort();
            const dataMasuk = tanggalMasuk.map(date => masukByDate[date] || 0);
            const rataRataMasuk = dataMasuk.length > 0 ? dataMasuk.reduce((a, b) => a + b, 0) / dataMasuk.length : 0;

            // 2. Proses data Keluar
            const keluarByDate = aggregateDataByDate(keluarData, 'drop_out_date');
            const tanggalKeluar = Object.keys(keluarByDate).sort();
            const dataKeluar = tanggalKeluar.map(date => keluarByDate[date] || 0);
            const rataRataKeluar = dataKeluar.length > 0 ? dataKeluar.reduce((a, b) => a + b, 0) / dataKeluar.length : 0;

            console.log("Tanggal Masuk:", tanggalMasuk);
            console.log("Data Masuk:", dataMasuk);
            console.log("Rata-rata Masuk:", rataRataMasuk);
            console.log("Tanggal Keluar:", tanggalKeluar);
            console.log("Data Keluar:", dataKeluar);
            console.log("Rata-rata Keluar:", rataRataKeluar);

            // Jika tidak ada data masuk, set default
            const defaultTanggalMasuk = tanggalMasuk.length > 0 ? tanggalMasuk : ["Tidak ada data"];
            const defaultDataMasuk = dataMasuk.length > 0 ? dataMasuk : [0];

            // Jika tidak ada data keluar, set default
            const defaultTanggalKeluar = tanggalKeluar.length > 0 ? tanggalKeluar : ["Tidak ada data"];
            const defaultDataKeluar = dataKeluar.length > 0 ? dataKeluar : [0];

            // Grafik Barang Masuk
            const ctxMasuk = document.getElementById('chartBarangMasuk')?.getContext('2d');
            if (ctxMasuk) {
                new Chart(ctxMasuk, {
                    type: 'line',
                    data: {
                        labels: defaultTanggalMasuk,
                        datasets: [
                            {
                                label: 'Transaksi Barang Masuk',
                                data: defaultDataMasuk,
                                borderColor: 'blue',
                                borderWidth: 2,
                                fill: false
                            },
                            {
                                label: 'Rata-rata Transaksi Barang Masuk',
                                data: Array(defaultTanggalMasuk.length).fill(rataRataMasuk),
                                borderColor: 'red',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                fill: false
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Transaksi'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tanggal'
                                }
                            }
                        }
                    }
                });
            } else {
                console.error("Canvas 'chartBarangMasuk' not found in the DOM");
            }

            // Grafik Barang Keluar
            const ctxKeluar = document.getElementById('chartBarangKeluar')?.getContext('2d');
            if (ctxKeluar) {
                new Chart(ctxKeluar, {
                    type: 'line',
                    data: {
                        labels: defaultTanggalKeluar,
                        datasets: [
                            {
                                label: 'Transaksi Barang Keluar',
                                data: defaultDataKeluar,
                                borderColor: 'green',
                                borderWidth: 2,
                                fill: false
                            },
                            {
                                label: 'Rata-rata Transaksi Barang Keluar',
                                data: Array(defaultTanggalKeluar.length).fill(rataRataKeluar),
                                borderColor: 'orange',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                fill: false
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Transaksi'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tanggal'
                                }
                            }
                        }
                    }
                });
            } else {
                console.error("Canvas 'chartBarangKeluar' not found in the DOM");
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);

            // Tampilkan grafik kosong jika gagal
            const ctxMasuk = document.getElementById('chartBarangMasuk')?.getContext('2d');
            const ctxKeluar = document.getElementById('chartBarangKeluar')?.getContext('2d');

            if (ctxMasuk) {
                new Chart(ctxMasuk, {
                    type: 'line',
                    data: {
                        labels: ["Tidak ada data"],
                        datasets: [{
                            label: 'Transaksi Barang Masuk',
                            data: [0],
                            borderColor: 'blue',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        plugins: { legend: { position: 'bottom' } },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Jumlah Transaksi' } },
                            x: { title: { display: true, text: 'Tanggal' } }
                        }
                    }
                });
            }

            if (ctxKeluar) {
                new Chart(ctxKeluar, {
                    type: 'line',
                    data: {
                        labels: ["Tidak ada data"],
                        datasets: [{
                            label: 'Transaksi Barang Keluar',
                            data: [0],
                            borderColor: 'green',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        plugins: { legend: { position: 'bottom' } },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Jumlah Transaksi' } },
                            x: { title: { display: true, text: 'Tanggal' } }
                        }
                    }
                });
            }
        });
});
