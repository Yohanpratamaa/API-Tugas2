document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");
    const apiUrl = `http://127.0.0.1:8000/api/inventory/${id}`;

    if (!id) {
        document.getElementById("loading").innerText =
            "Barang tidak ditemukan.";
        return;
    }

    function inventoryDetail() {
        axios
            .get(apiUrl)
            .then((response) => {
                if (response.data.status === "success") {
                    const item = response.data.data;
                    const edit = document.getElementById("buttonEdit");
                    const deleteButton =
                        document.getElementById("buttonDelete");
                    const editIcon = "/img/IEdit.png";
                    const deleteIcon = "/img/IDelete.png";

                    // Button Edit
                    edit.innerHTML = `
                        <button onclick="openEditPage('${item.id}')" class="flex items-center gap-x-2 px-3 w-[154px] h-[42px] text-gray-500 border border-gray-500 hover:bg-gray-100 rounded-lg">
                            <img src="${editIcon}" alt="" class="w-[24px]">
                            Edit Barang
                        </button>
                    `;

                    // Button Delete
                    deleteButton.innerHTML = `
                        <button type="button" onclick="confirmDelete('${item.id}')" class="flex items-center gap-x-2 px-3 w-[154px] h-[42px] text-gray-500 border border-gray-500 hover:bg-gray-100 rounded-lg">
                            <img src="${deleteIcon}" alt="" class="w-[20px]">
                            Hapus Barang
                        </button>
                `;

                    // Name Barang
                    if (document.getElementById("itemName")) {
                        document.getElementById("itemName").innerText =
                            item.name;
                    }

                    // Unit Barang
                    if (document.getElementById("itemUnit")) {
                        document.getElementById("itemUnit").innerText =
                            item.unit;
                    }

                    // Kategori Barang
                    if (document.getElementById("itemCategory")) {
                        document.getElementById("itemCategory").innerText =
                            item.category;
                    }

                    // Lokasi Barang
                    if (document.getElementById("itemLocation")) {
                        document.getElementById("itemLocation").innerText =
                            item.location;
                    }

                    // Minimum Barang
                    if (document.getElementById("itemMinimum")) {
                        document.getElementById(
                            "itemMinimum"
                        ).innerText = `${item.minimum} ${item.unit}`;
                    }

                    // Status Barang
                    if (document.getElementById("itemStockStatus")) {
                        document.getElementById("itemStockStatus").innerText =
                            item.stock_status;
                    }

                    if (item.stock_status === "Aman") {
                        statusContainer.classList.add("text-green-500");
                        statusCircle.classList.add("bg-green-600");
                    } else if (item.stock_status === "Tidak Aman") {
                        statusContainer.classList.add("text-red-500");
                        statusCircle.classList.add("bg-red-600");
                    }

                    // Jumlah Barang
                    if (document.getElementById("itemQuantity")) {
                        document.getElementById(
                            "itemQuantity"
                        ).innerText = `${item.quantity} ${item.unit}`;
                    }

                    // Harga Satuan Barang
                    if (document.getElementById("itemPrice")) {
                        document.getElementById("itemPrice").innerText =
                            new Intl.NumberFormat("id-ID").format(
                                item.unit_price
                            );
                    }

                    // Tanggal Masuk Barang
                    if (document.getElementById("itemDOE")) {
                        document.getElementById("itemDOE").innerText =
                            item.date_of_expired;
                    }

                    // Tanggal Masuk Barang
                    if (document.getElementById("itemDOM")) {
                        document.getElementById("itemDOM").innerText =
                            item.date_of_manufacture;
                    }

                    // Tanggal Masuk Barang
                    if (document.getElementById("entryDate")) {
                        document.getElementById("entryDate").innerText =
                            item.entry_date;
                    }

                    // Kondisi Barang
                    if (document.getElementById("condition")) {
                        document.getElementById("condition").innerText =
                            item.condition;
                    }

                    // Cek jika part_number ada
                    const partNumbersList =
                        document.getElementById("partNumbersList");
                    if (partNumbersList) {
                        partNumbersList.innerHTML = "";
                        if (item.part_number) {
                            JSON.parse(item.part_number).forEach((part) => {
                                const li = document.createElement("li");
                                li.textContent = part;
                                partNumbersList.appendChild(li);
                            });
                        } else {
                            partNumbersList.innerHTML =
                                "<p class='text-gray-500'>Tidak ada Part Number</p>";
                        }
                    }

                    // Gambar Barang
                    if (item.image && document.getElementById("itemImage")) {
                        document.getElementById(
                            "itemImage"
                        ).src = `/storage/${item.image}`;
                        document
                            .getElementById("itemImage")
                            .classList.remove("hidden");
                        document
                            .getElementById("noImageText")
                            .classList.add("hidden");
                    } else {
                        document
                            .getElementById("itemImage")
                            .classList.add("hidden");
                        document
                            .getElementById("noImageText")
                            .classList.remove("hidden");
                    }

                    // Dokumen Barang
                    if (
                        item.document &&
                        document.getElementById("documentLink")
                    ) {
                        document.getElementById(
                            "documentLink"
                        ).href = `/storage/${item.document}`;
                        document
                            .getElementById("documentLink")
                            .classList.remove("hidden");
                        document
                            .getElementById("noDocumentText")
                            .classList.add("hidden");
                    }
                }
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    }

    function loadById(id) {
        const apiUrlMasuk = `http://127.0.0.1:8000/api/inventory/${id}`; // Data masuk berdasarkan id dari inventories
        const apiUrlKeluar = `http://127.0.0.1:8000/api/inventory-out/${id}`; // Data keluar berdasarkan id dari inventory_out
        const tableBody = document.getElementById("tableBody");
        const inventoryTable = document.getElementById("inventoryTable");

        // Fungsi untuk menambahkan baris ke tabel
        function appendRow(item) {
            const row = document.createElement("tr");
            row.innerHTML = `
                        <td class="p-4 border border-gray-300">
                ${
                    item.item_status === "Masuk"
                        ? `<p class="text-gray-500">${item.entry_date}</p>`
                        : item.item_status === "Keluar"
                        ? `<p class="text-gray-500">${item.drop_out_date}</p>`
                        : ""
                }
            </td>
                <td class="p-4 border border-gray-300 text-gray-500">${item.name || '-'}</td>
                <td class="p-4 border border-gray-300 text-gray-500">${item.part_number || '-'}</td>
                <td class="p-4 border border-gray-300 text-gray-500">${item.category || '-'}</td>
                <td class="p-4 border border-gray-300 text-gray-500">${item.unit || '-'}</td>
                <td class="p-4 border border-gray-300 text-gray-500">${item.quantity ? `${item.quantity} ${item.unit}` : '-'}</td>
                <td class="p-4 border border-gray-300 text-gray-500">${item.minimum ? `${item.minimum} ${item.unit}` : '-'}</td>
                <td class="p-4 border border-gray-300 text-center">
                    ${item.item_status === "Masuk" ? `
                        <button type="button" class="text-center w-[140px] h-[40px] justify-center gap-x-1.5 rounded-md py-2 text-sm font-semibold ring-1 shadow-xs ring-green-400 text-green-300 ring-inset hover:bg-gray-50">Masuk</button>
                    ` : item.item_status === "Keluar" ? `
                        <button type="button" class="text-center w-[140px] h-[40px] justify-center gap-x-1.5 rounded-md py-2 text-sm font-semibold ring-1 shadow-xs ring-red-400 text-red-500 ring-inset hover:bg-gray-50">Keluar</button>
                    ` : ""}
                </td>
            `;
            tableBody.appendChild(row);
        }

        // Fungsi untuk menampilkan data
        function processAndDisplayData(data) {
            tableBody.innerHTML = "";
            if (data.length > 0) {
                data.sort((a, b) => {
                    const dateA = new Date(a.item_status === "Keluar" ? a.drop_out_date : a.created_at);
                    const dateB = new Date(b.item_status === "Keluar" ? b.drop_out_date : b.created_at);
                    return dateB - dateA;
                });
                console.log("Processed Data (Sorted):", data);
                data.forEach(item => {
                    appendRow(item);
                });
                inventoryTable.classList.remove("hidden");
            } else {
                tableBody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data tersedia untuk ID ini.</td></tr>`;
            }
        }

        let processedData = [];

        // Ambil data Masuk
        axios.get(apiUrlMasuk)
            .then(masukResponse => {
                const masukData = (masukResponse.data.status === "success" && masukResponse.data.data) || null;
                console.log("Masuk Data by ID:", masukData);

                if (masukData) {
                    processedData.push({
                        ...masukData,
                        item_status: "Masuk"
                    });
                }

                // Ambil data Keluar secara terpisah
                return axios.get(apiUrlKeluar)
                    .then(keluarResponse => {
                        const keluarData = (keluarResponse.data.status === "success" && keluarResponse.data.data) || null;
                        console.log("Keluar Data by ID:", keluarData);

                        if (keluarData && masukData) {
                            return axios.get(`http://127.0.0.1:8000/api/inventory/${keluarData.inventory_id}`)
                                .then(response => {
                                    const relatedMasukData = (response.data.status === "success" && response.data.data) || null;
                                    console.log("Related Masuk Data:", relatedMasukData);

                                    if (relatedMasukData) {
                                        processedData.push({
                                            ...relatedMasukData,
                                            item_status: "Keluar",
                                            drop_out_date: keluarData.drop_out_date || null,
                                            quantity: keluarData.quantity || relatedMasukData.quantity,
                                            created_at: keluarData.created_at || relatedMasukData.created_at
                                        });
                                    }
                                    processAndDisplayData(processedData);
                                });
                        } else {
                            processAndDisplayData(processedData);
                        }
                    })
                    .catch(error => {
                        console.warn("Tidak ada data keluar untuk ID ini:", error.response?.status);
                        processAndDisplayData(processedData); // Lanjutkan dengan data Masuk saja
                    });
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                tableBody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-500">Gagal memuat data.</td></tr>`;
            });
    }

    function loadDocument(id) {
        const apiUrl = `http://127.0.0.1:8000/api/inventory/${id}`;
        const documentTableBody = document.getElementById("documentTableBody");
        const documentTable = document.getElementById("documentTable");

        axios
            .get(apiUrl)
            .then((response) => {
                if (response.data.status === "success" && response.data.data) {
                    const doc = response.data.data;
                    const baseStorageUrl = 'http://127.0.0.1:8000/storage/';
                    const documentUrl = doc.document ? `${baseStorageUrl}${doc.document}` : '#';

                    // Cek apakah ada dokumen
                    if (!doc.document) {
                        documentTableBody.innerHTML = `
                            <tr>
                                <td colspan="4" class="p-4 border border-gray-300 text-center text-gray-500">Tidak ada Dokumen yang terdedia !</td>
                            </tr>
                        `;
                    } else {
                        // Gunakan document_date atau created_at sebagai tanggal upload
                        const uploadDate = new Date(doc.document_date || doc.created_at).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                        documentTableBody.innerHTML = `
                            <tr>
                                <td class="p-4 border text-gray-500">${doc.document_name || doc.document || 'Tidak ada dokumen'}</td>
                                <td class="p-4 border text-gray-500">${uploadDate}</td>
                                <td class="p-4 border">
                                    <a
                                        href="${documentUrl}"
                                        download="${doc.document_name || 'document'}"
                                        class="text-center w-[140px] h-[40px] flex items-center justify-center gap-x-1.5 rounded-md py-2 text-sm font-semibold ring-1 shadow-xs ring-gray-300 text-gray-500 ring-inset hover:bg-gray-100"
                                    >
                                        Download
                                    </a>
                                </td>
                            </tr>
                        `;
                    }
                    documentTable.classList.remove("hidden");
                }
            })
            .catch((error) => {
                console.error("Error fetching document:", error);
                documentTableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="p-4 text-center text-red-500">Tidak ada Data Yang Tersedia</td>
                    </tr>
                `;
            });
    }

    loadDocument(id);
    loadById(id);
    inventoryDetail();
});

function confirmDelete(id) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F9771F",
        cancelButtonColor: "#22385e",
        confirmButtonText: "Ya, hapus!",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            axios
                .delete(`http://127.0.0.1:8000/api/inventory/${id}`)
                .then((response) => {
                    console.log("Hasil" + response.data);
                    showAlert(
                        "success",
                        "Berhasil!",
                        "Pengguna berhasil dihapus."
                    );
                    setTimeout(() => {
                        window.location.replace("/inventories");
                    }, 1500);
                })
                .catch((error) => {
                    console.error("Error deleting user:", error);
                    showAlert(
                        "error",
                        "Gagal!",
                        "Terjadi kesalahan saat menghapus pengguna."
                    );
                });
        }
    });
}

function openEditPage(id) {
    window.location.href = `/editBarang?id=${id}`;
}

function openEditPage(id) {
    window.location.href = `/editBarang?id=${id}`;

    axios
        .get(`http://127.0.0.1:8000/api/inventory/${id}`)
        .then((response) => {
            const inventory = response.data.data;
            console.log("Inventory Edit" + inventory);

            document.getElementById("editBarangId").value = id;
            document.getElementById("editName").value = inventory.name || "";
            console.log("Nama: " + inventory.name);
            document.getElementById("editLocation").value =
                inventory.location || "";
            document.getElementById("editUnitPrice").value =
                inventory.unit_price || "";
            document.getElementById("editQuantity").value =
                inventory.quantity || "";
            document.getElementById("editEntryDate").value =
                inventory.entry_date || "";
            document.getElementById("editDocumentDate").value =
                inventory.document_date || "";
            document.getElementById("editDOM").value =
                inventory.date_of_manufacture || "";
            document.getElementById("editDOE").value =
                inventory.date_of_expired || "";
            document.getElementById("editMinimum").value =
                inventory.minimum || "";
            document.getElementById("editCondition").value =
                inventory.condition || "";
            document.getElementById("editCategory").value =
                inventory.category || "";
        })
        .catch((error) => {
            console.error("Error fetching Inventory:", error);
            showAlert(
                "error",
                "Gagal!",
                "Terjadi kesalahan saat mengambil data pengguna."
            );
        });
}

function downloadPDF(id) {
    if (!id) {
        showAlert("error", "Gagal!", "ID barang tidak ditemukan.");
        return;
    }
    window.location.href = `/inventory/export-pdf/${id}`;
}

function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1500,
    });
}
