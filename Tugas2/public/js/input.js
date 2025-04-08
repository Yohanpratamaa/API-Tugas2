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

document.getElementById("inputForm").addEventListener("submit", function(event) {
    const apiUrl = "http://127.0.0.1:8000/api/inventory"
    event.preventDefault();

    let formData = new FormData();
    formData.append("name", document.getElementById("name").value || null);
    formData.append("quantity", document.getElementById("quantity").value || "0");
    formData.append("unit", document.getElementById("unit").value || null);

    ["entry_date"].forEach(field => {
        const valuedate = document.getElementById(field).value;
        if (valuedate) formData.append(field, valuedate);
    });

    formData.append("category", document.getElementById("category").value || null);

    axios.post(apiUrl, formData, {
        headers: {
            "Content-Type": "multipart/form-data",
            "Accept": "application/json",
            "Authorization": "Bearer YOUR_ACCESS_TOKEN"
        }
    })
    .then(response => {
        if (response.data.status === "success") {

            Swal.fire({
                title: "Berhasil!",
                text: "Barang berhasil ditambahkan.",
                icon: "success",
                showCancelButton: true,
                confirmButtonText: "Lihat Barang",
                cancelButtonText: "Input Kembali",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    history.scrollRestoration = 'manual';
                    setTimeout(() => {
                        window.scrollTo(0, 0);
                    }, 0);
                    window.location.reload();
                }
            });
        }
    })
    .catch(error => {
        if (error.response) {
            console.error("Error:", error.response.data);

            // Cek jika error karena nama barang sudah ada
            if (error.response.status === 422) {
                Swal.fire({
                    title: "Nama Barang Sudah Terdaftar!",
                    text: "Silakan gunakan Nama barang yang lain atau Edit Barang !!",
                    icon: "warning",
                    confirmButtonText: "OK",
                    showCancelButton: true,
                    cancelButtonText: "Edit Barang",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.scrollTo(0, 0);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = "/editBarang";
                    }
                });
            } else {
                showAlert("error", "Gagal!", error.response.data.message || "Terjadi kesalahan, silakan coba lagi.");
            }
        } else {
            showAlert("error", "Gagal!", "Tidak dapat terhubung ke server.");
        }
    });
});
