// Fungsi buat format tanggal
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
// Fungsi buat format part_number
function formatPartNumber(partNumber) {
    // Kalau partNumber adalah string JSON, parse dulu
    if (typeof partNumber === "string") {
        try {
            partNumber = JSON.parse(partNumber);
        } catch (e) {
            return partNumber; // Kalau gagal parse, kembalikan apa adanya
        }
    }
    // Kalau partNumber adalah array, join dengan koma
    if (Array.isArray(partNumber)) {
        return partNumber.join(", ");
    }
    // Kalau bukan array, kembalikan apa adanya
    return partNumber;
}

// Fungsi buat isi tabel dengan data
function populateInventoryTable(data) {
    const tableBody = document.getElementById("tableBody");
    const inventoryTable = document.getElementById("inventoryTable");

    // Kosongin tabel dulu
    tableBody.innerHTML = "";

    if (data.status === "success" && data.data.length > 0) {
        data.data.forEach((item) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="border p-4 text-gray-500">${formatDateTime(
                    item.created_at
                )}</td>
                <td class="border p-4 text-gray-500">${formatPartNumber(
                    item.part_number
                )}</td>
                <td class="border p-4 text-gray-500">${formatPartNumber(
                    item.part_number
                )}</td>
                <td class="border p-4 text-gray-500">${formatPartNumber(
                    item.part_number
                )}</td>
                <td class="border p-4 text-gray-500">${item.name}</td>
                <td class="border p-4 text-gray-500">${item.category}</td>
                <td class="border p-4 text-gray-500">${item.quantity} ${
                item.unit
            }</td>
                <td class="border p-4 text-gray-500">${item.minimum} ${
                item.unit
            }</td>
                <td class="border p-4 text-gray-500">${item.location}</td>
                <td class="border p-4">
                    <button class="bg-white border border-gray-300 rounded-md shadow-xs text-gray-400 text-sm w-full detail-button font-semibold hover:bg-gray-100 px-4 py-2"
                        data-id="${item.id}">
                        Detail
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        inventoryTable.classList.remove("hidden");

        // Pasang event listener ke tombol "Detail"
        attachDetailButtonListeners();
    } else {
        tableBody.innerHTML = `<tr><td colspan="8" class="p-4 text-center text-gray-500">Tidak ada data tersedia.</td></tr>`;
    }
}

// Fungsi buat pasang event listener ke tombol "Detail"
function attachDetailButtonListeners() {
    document.querySelectorAll(".detail-button").forEach((button) => {
        button.addEventListener("click", function () {
            const itemId = this.getAttribute("data-id");
            window.location.href = `/detailBarang?id=${itemId}`;
        });
    });
}

// Fungsi buat ambil data dari API dan isi tabel
function fetchAndPopulateTable() {
    const apiUrl = "http://localhost:8000/api/inventories";
    axios
        .get(apiUrl)
        .then((response) => {
            console.log("Data dari API:", response.data);
            populateInventoryTable(response.data);
        })
        .catch((error) => {
            console.error("Gagal ambil data:", error.message);
            const tableBody = document.getElementById("tableBody");
            tableBody.innerHTML = `<tr><td colspan="8" class="p-4 text-center text-red-500">Gagal memuat data: ${error.message}</td></tr>`;
        });
}

// Jalanin pas halaman pertama kali dimuat
document.addEventListener("DOMContentLoaded", function () {
    fetchAndPopulateTable();
});
