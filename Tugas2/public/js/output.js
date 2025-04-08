function dropdownName() {
    return {
        open: false,
        selectedInventoryId: '',
        selectedOption: 'Pilih Nama Barang',
        unit_price: '',
        items: [],

        // Panggil API saat halaman dimuat
        init() {
            axios.get('http://127.0.0.1:8000/api/inventories')
                .then(response => {
                    if (response.data.status === "success") {
                        this.items = response.data.data;
                    } else {
                        console.error("Format API tidak sesuai!");
                    }
                })
                .catch(error => {
                    console.error("Gagal mengambil data:", error);
                });
        },

        // Pilih nama barang & isi data otomatis
        selectItem(id, name) {
            this.selectedInventoryId = id;
            this.selectedOption = name;
            this.open = false;
        },
    };
}

const inputs = document.querySelectorAll(".restrict-input");

// Fungsi untuk memfilter input
function restrictInput(event) {
    const allowedPattern = /^[a-zA-Z0-9\s]*$/;
    if (!allowedPattern.test(event.target.value)) {
        event.target.value = event.target.value.replace(/[^a-zA-Z0-9\s]/g, "");
    }
}

// Fungsi untuk memfilter paste
function restrictPaste(event) {
    const pastedText = (event.clipboardData || window.clipboardData).getData("text");
    if (!/^[a-zA-Z0-9\s]*$/.test(pastedText)) {
        event.preventDefault();
        const validText = pastedText.replace(/[^a-zA-Z0-9\s]/g, "");
        event.target.value = validText;
    }
}

// Terapkan event listener ke semua input
inputs.forEach(input => {
    input.addEventListener("input", restrictInput);
    input.addEventListener("paste", restrictPaste);
});

document.getElementById("outputForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const apiUrl = "http://127.0.0.1:8000/api/inventory-outs";

    let idValue = document.getElementById("id").value;

    if (!idValue) {
        showAlert("error", "Gagal!", "Barang belum dipilih.");
        return;
    }

    let formData = new FormData();
    formData.append("inventory_id", idValue);
    formData.append("quantity", document.getElementById("quantity").value);

    let dropOutDate = document.getElementById("drop_out_date").value;
    if (!dropOutDate) {
        showAlert("error", "Gagal!", "Tanggal drop out wajib diisi.");
        return;
    }
    formData.append("drop_out_date", dropOutDate);

    axios.post(apiUrl, formData, {
        headers: {
            "Content-Type": "multipart/form-data",
            "Accept": "application/json",
            "Authorization": "Bearer YOUR_ACCESS_TOKEN"
        }
    })
    .then(response => {
        console.log("Response:", response.data);

        Swal.fire({
            title: "Berhasil!",
            text: "Barang berhasil Keluar.",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Beranda",
            cancelButtonText: "Input Kembali",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/dashboard";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                history.scrollRestoration = 'manual';
                setTimeout(() => {
                    window.scrollTo(0, 0);
                }, 0);
                window.location.reload();
            }
        });
    })
    .catch(error => {
        console.error("Error:", error);
        let message = error.response?.data?.message || "Terjadi kesalahan saat memproses permintaan.";
        showAlert("error", "Gagal!", message);
    });
});

function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1500
    });
}
