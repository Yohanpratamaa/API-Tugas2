// Restrict input ke karakter alfanumerik dan spasi
const inputs = document.querySelectorAll(".restrict-input");

function restrictInput(event) {
    const allowedPattern = /^[a-zA-Z0-9\s]*$/;
    if (!allowedPattern.test(event.target.value)) {
        event.target.value = event.target.value.replace(/[^a-zA-Z0-9\s]/g, "");
    }
}

function restrictPaste(event) {
    const pastedText = (event.clipboardData || window.clipboardData).getData("text");
    if (!/^[a-zA-Z0-9\s]*$/.test(pastedText)) {
        event.preventDefault();
        const validText = pastedText.replace(/[^a-zA-Z0-9\s]/g, "");
        event.target.value = validText;
    }
}

inputs.forEach((input) => {
    input.addEventListener("input", restrictInput);
    input.addEventListener("paste", restrictPaste);
});

// Format angka untuk quantity
function formatNumber(value) {
    return new Intl.NumberFormat("id-ID").format(BigInt(value));
}

function cleanNumber(value) {
    const cleaned = String(value).replace(/[^\d]/g, "");
    return cleaned || "0"; // Default ke "0" jika kosong
}

document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const barangId = urlParams.get("id");
    const apiUrl = `http://127.0.0.1:8000/api/inventory/${barangId}`;

    const quantityInput = document.getElementById("editQuantity");

    // Format quantity saat input
    quantityInput.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "");
        if (rawValue === "") rawValue = "0";
        e.target.dataset.raw = rawValue;
        e.target.value = formatNumber(rawValue);
    });

    if (barangId) {
        axios
            .get(apiUrl)
            .then((response) => {
                const inventory = response.data.data;

                // Isi field dengan data dari API
                document.getElementById("editBarangId").value = barangId;
                document.getElementById("editName").value = inventory.name || "";
                document.getElementById("editQuantity").value = formatNumber(
                    cleanNumber(inventory.quantity || "0")
                );
                document.getElementById("editQuantity").dataset.raw = cleanNumber(
                    inventory.quantity || "0"
                );
                document.getElementById("editUnit").value = inventory.unit || "";
                document.getElementById("editEntryDate").value = inventory.entry_date
                    ? inventory.entry_date.split("T")[0]
                    : "";
                document.getElementById("editCategory").value = inventory.category || "";
            })
            .catch((error) => {
                console.error("Error fetching Inventory:", error);
                alert("Gagal mengambil data barang!");
            });
    } else {
        // Default value jika tidak ada ID
        quantityInput.value = formatNumber("0");
        quantityInput.dataset.raw = "0";
    }
});

// Fungsi untuk update barang
function updateBarang(event) {
    event.preventDefault();

    const id = document.getElementById("editBarangId").value;

    // Buat FormData untuk mengirim data
    const formData = new FormData();
    formData.append("name", document.getElementById("editName").value);
    formData.append("quantity", document.getElementById("editQuantity").dataset.raw || "0");
    formData.append("unit", document.getElementById("editUnit").value);
    formData.append("entry_date", document.getElementById("editEntryDate").value);
    formData.append("category", document.getElementById("editCategory").value);

    // Tambahkan method _method untuk PATCH karena FormData tidak mendukung PATCH secara langsung
    formData.append("_method", "PATCH");

    // Kirim request ke server
    axios
        .post(`http://127.0.0.1:8000/api/inventory/${id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            console.log(response.data);
            showAlert("success", "Berhasil!", "Data barang berhasil diperbarui!");
            setTimeout(() => {
                window.location.href = "/";
            }, 1500);
        })
        .catch((error) => {
            console.error("Error updating barang:", error);
            showAlert("error", "Gagal!", "Terjadi kesalahan saat memperbarui data barang.");
        });
}

// Tambahkan event listener untuk form submit
document.getElementById("editBarangForm").addEventListener("submit", updateBarang);

// Fungsi untuk menampilkan alert
function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1500,
    });
}
