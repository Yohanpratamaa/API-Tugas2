document.addEventListener("DOMContentLoaded", function () {
    const apiMasukUrl = "http://localhost:8000/api/inventories";
    const apiKeluarUrl = "http://localhost:8000/api/inventory-outs-all";
    const tableBody = document.getElementById("tableBody");
    const dashboardTable = document.getElementById("dashboardTable");

    // Fungsi untuk memformat tanggal
    function formatDateTime(dateString) {
        if (!dateString) return "-";
        const date = new Date(dateString);
        return date.toLocaleString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    // Fungsi untuk menambahkan baris ke tabel
    function appendRow(item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="p-4 border border-gray-300 text-gray-500">
                ${
                    item.item_status.trim() === "Masuk"
                    ? formatDateTime(item.created_at)
                    : item.item_status.trim() === "Keluar"
                    ? formatDateTime(item.updated_at)
                    : "-"
                }
            </td>
            <td class="p-4 border border-gray-300 text-gray-500">${item.name || "-"}</td>
            <td class="p-4 border border-gray-300 text-gray-500">${item.category || "-"}</td>
            <td class="p-4 border border-gray-300 text-gray-500">${item.unit || "-"}</td>
            <td class="p-4 border border-gray-300 text-gray-500">${item.quantity ? `${item.quantity} ${item.unit || ''}` : "-"}</td>
            <td class="p-4 border border-gray-300 text-gray-500">${item.entry_date || "-"}</td>
            <td class="p-4 border border-gray-300 text-center">
                ${
                    item.item_status === "Masuk"
                        ? `<button class="text-center w-[140px] h-[40px] rounded-md py-2 text-sm font-semibold ring-1 ring-green-400 text-green-300 hover:bg-gray-50">Masuk</button>`
                        : `<button class="text-center w-[140px] h-[40px] rounded-md py-2 text-sm font-semibold ring-1 ring-red-400 text-red-500 hover:bg-gray-50">Keluar</button>`
                }
            </td>
        `;
        tableBody.appendChild(row);
    }

    // Fungsi untuk fetch data
    function fetchData(url) {
        return axios.get(url)
            .then(response => {
                if (response.data.status === "success" && Array.isArray(response.data.data)) {
                    return response.data.data;
                }
                return [];
            })
            .catch(error => {
                console.error(`Error fetching ${url}:`, error);
                return [];
            });
    }

    // Fungsi untuk render tabel berdasarkan filter
    function renderTable(data, filter) {
        tableBody.innerHTML = "";
        const filteredData = filter === "all" ? data : data.filter(item => item.item_status === filter);

        if (filteredData.length > 0) {
            filteredData.forEach(item => appendRow(item));
            dashboardTable.classList.remove("hidden");
        } else {
            tableBody.innerHTML = `<tr><td colspan="8" class="p-4 text-center text-gray-500">Tidak ada data untuk filter ini.</td></tr>`;
            dashboardTable.classList.add("hidden");
        }
    }

    // Ambil dan proses data
    Promise.all([fetchData(apiMasukUrl), fetchData(apiKeluarUrl)])
        .then(([masukData, keluarData]) => {
            // Proses data Masuk
            const processedMasukData = masukData.map(item => ({
                ...item,
                item_status: "Masuk"
            }));

            // Proses data Keluar
            const processedKeluarData = keluarData
                .map(keluarItem => {
                    const matchingMasukItem = masukData.find(
                        masuk => masuk.id === keluarItem.inventory_id
                    );
                    if (matchingMasukItem) {
                        return {
                            ...matchingMasukItem,
                            item_status: "Keluar",
                            created_at: null,
                            updated_at: keluarItem.updated_at || null,
                            quantity: keluarItem.quantity || matchingMasukItem.quantity || 0,
                        };
                    }
                    return null;
                })
                .filter(item => item !== null);

            // Gabungkan semua data dan urutkan berdasarkan tanggal
            const allData = [...processedMasukData, ...processedKeluarData].sort((a, b) => {
                const dateA = a.item_status === "Masuk"
                    ? new Date(a.created_at || "1970-01-01")
                    : new Date(a.updated_at || "1970-01-01");
                const dateB = b.item_status === "Masuk"
                    ? new Date(b.created_at || "1970-01-01")
                    : new Date(b.updated_at || "1970-01-01");
                return dateB - dateA;
            });

            console.log("All Processed Data:", allData);

            // Render tabel awal dengan semua data
            renderTable(allData, "all");

            // Event listener untuk filter dari Alpine.js
            window.addEventListener('filter-changed', (event) => {
                const filter = event.detail;
                renderTable(allData, filter);
            });
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            tableBody.innerHTML = `<tr><td colspan="8" class="p-4 text-center text-red-500">Gagal memuat data.</td></tr>`;
        });
});
