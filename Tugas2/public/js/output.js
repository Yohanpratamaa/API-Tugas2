function dropdownName() {
    return {
        open: false,
        selectedInventoryId: '',
        selectedOption: 'Pilih Nama Barang',
        unit_price: '',
        partNumbers: [],
        nsNumbers: [],
        serialNumbers: [],
        document: '', // URL dokumen
        image: '',
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
        selectItem(id, name, unit_price, part_number, document, image) {
            this.selectedInventoryId = id;
            this.selectedOption = name;
            this.unit_price = unit_price;
            this.partNumbers = part_number || [];
            this.image = image ? `http://127.0.0.1:8000/storage/${image}` : '';
            this.document = document ? `http://127.0.0.1:8000/storage/${document}` : '';
            this.open = false;
        },

        // Fungsi untuk mendapatkan ikon berdasarkan tipe dokumen
        getDocumentIcon(docUrl) {
            if (!docUrl) return '/svg/file-svgrepo-com.svg';
            const extension = docUrl.split('.').pop().toLowerCase();
            switch (extension) {
                case 'pdf':
                    return '/svg/pdf-svgrepo-com.svg';
                case 'doc':
                case 'docx':
                    return '/svg/doc-svgrepo-com.svg';
                case 'xlsx':
                    return 'https://img.icons8.com/color/48/000000/microsoft-excel-2019.png';
                default:
                    return '/svg/file-svgrepo-com.svg';
            }
        },

        getImageIcon(imgUrl) {
            if (!imgUrl) return '\\svg\\img-box-svgrepo-com.svg';
        },

        // Fungsi untuk mendapatkan nama file dari URL
        getDocumentName(docUrl) {
            if (!docUrl) return 'Tidak ada dokumen';
            return docUrl.split('/').pop();
        },

        getImageName(imgUrl) {
            if (!imgUrl) return 'Tidak ada gambar';
            return imgUrl.split('/').pop();
        }
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
    formData.append("destination", document.getElementById("destination").value);

    formData.append("unit_price", document.getElementById("unit_price").value ? parseFloat(document.getElementById("unit_price").value.replace(/,/g, "")) : null);

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
