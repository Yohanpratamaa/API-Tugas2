document.addEventListener("DOMContentLoaded", function () {
    const apiMasukUrl = "http://localhost:8000/api/inventories";
    const apiKeluarUrl = "http://localhost:8000/api/inventory-outs-all";

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
            let hasData = false;

            // Debugging data awal
            console.log("Masuk Data:", masukData);
            console.log("Keluar Data:", keluarData);

            // 1. Hitung item dengan status "Masuk"
            // Semua item dari apiMasukUrl dianggap "Masuk"
            const totalItemsIn = masukData.length;
            const totalItemsInElement = document.getElementById("totalItemsIn");
            if (totalItemsInElement) {
                totalItemsInElement.innerText = totalItemsIn;
            } else {
                console.error("Element 'totalItemsIn' not found in the DOM");
            }

            // 2. Hitung item dengan status "Keluar"
            // Semua item dari apiKeluarUrl dianggap "Keluar"
            const totalItemsOut = keluarData.length;
            const totalItemsOutElement = document.getElementById("totalItemsOut");
            if (totalItemsOutElement) {
                totalItemsOutElement.innerText = totalItemsOut;
            } else {
                console.error("Element 'totalItemsOut' not found in the DOM");
            }

            // Cek apakah ada data yang berhasil diambil
            if (totalItemsIn > 0 || totalItemsOut > 0) {
                hasData = true;
            }

            // Tampilkan hasil perhitungan di konsol
            console.log("Total Items Masuk:", totalItemsIn);
            console.log("Total Items Keluar:", totalItemsOut);

            // Jika tidak ada data sama sekali
            if (!hasData) {
                console.log("Tidak ada data barang masuk atau keluar.");
                const totalItemsInElement = document.getElementById("totalItemsIn");
                const totalItemsOutElement = document.getElementById("totalItemsOut");
                if (totalItemsInElement) totalItemsInElement.innerText = "0";
                if (totalItemsOutElement) totalItemsOutElement.innerText = "0";
            }
        })
        .catch(error => {
            console.error("Error in Promise.all:", error);
            // Tampilkan 0 jika gagal memuat data
            const totalItemsInElement = document.getElementById("totalItemsIn");
            const totalItemsOutElement = document.getElementById("totalItemsOut");
            if (totalItemsInElement) totalItemsInElement.innerText = "0";
            if (totalItemsOutElement) totalItemsOutElement.innerText = "0";
        });
});
