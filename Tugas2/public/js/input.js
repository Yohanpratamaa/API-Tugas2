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

    document.querySelectorAll(".part-number-input").forEach(input => {
        if (input.value.trim() !== "") {
            formData.append("part_number[]", input.value.trim());
        }
    });

    formData.append("location", document.getElementById("location").value || null);
    formData.append("unit_price", document.getElementById("unit_price").value ? parseInt(document.getElementById("unit_price").value.replace(/\D/g, ""), 10) : null);
    formData.append("quantity", document.getElementById("quantity").value || "0");
    formData.append("unit", document.getElementById("unit").value || null);
    formData.append("minimum", document.getElementById("minimum").value || null);

    ["entry_date","document_date"].forEach(field => {
        const valuedate = document.getElementById(field).value;
        if (valuedate) formData.append(field, valuedate);
    });

    formData.append("source", document.getElementById("source").value || null);

    ["date_of_manufacture", "date_of_expired"].forEach(field => {
        const value = document.getElementById(field).value;
        if (value) formData.append(field, value);
    });

    formData.append("condition", document.getElementById("condition").value || null);
    formData.append("category", document.getElementById("category").value || null);

    // Tambahkan File
    let documentInput = document.getElementById("document").files[0];
    if (documentInput) {
        formData.append("document", documentInput);
    }

    let imageInput = document.getElementById("image").files[0];
    if (imageInput) {
        formData.append("image", imageInput);
    }

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
                    window.location.href = "/inventories";
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

document.addEventListener("DOMContentLoaded", function () {
    const hargaSatuanInput = document.getElementById("unit_price");
    const jumlahBarangInput = document.getElementById("quantity");
    const totalHargaInput = document.getElementById("total_price");

    hargaSatuanInput.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "");
        if (rawValue === "") rawValue = "0";
        e.target.dataset.raw = rawValue;

        e.target.value = new Intl.NumberFormat("id-ID").format(BigInt(rawValue));

        hitungTotal();
    });

    jumlahBarangInput.addEventListener("input", function () {
        hitungTotal();
    });

    function hitungTotal() {
        let hargaSatuan = BigInt(hargaSatuanInput.dataset.raw || "0");
        let jumlahBarang = BigInt(jumlahBarangInput.value || "0");
        let total = hargaSatuan * jumlahBarang;

        totalHargaInput.value = new Intl.NumberFormat("id-ID").format(total);
    }
});

// Nasional Serial Number
document.getElementById("addNasionalSerialNumber").addEventListener("click", function() {
    let container = document.getElementById("NSNumberContainer");
    const minIcon = "\\svg\\minus-svgrepo-com.svg";

    // Buat elemen div baru untuk input tambahan
    let newDiv = document.createElement("div");
    newDiv.classList.add("relative", "flex", "mt-3", "outline", "outline-1", "w-[47.25rem]", "items-center", "outline-gray-300", "rounded-xl", "h-[58px]", "focus-within:outline-2", "focus-within:-outline-offset-2", "focus-within:outline-orange-500");

    // Tambahkan input baru
    newDiv.innerHTML = `
        <input
            type="text"
            name="ns_number[]"
            class="part-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
            placeholder="Masukkan Nasional Serial Number">
        <div class="absolute right-0 mr-4">
            <img class="removeNSNumber w-5 h-5 cursor-pointer" src="${minIcon}" alt="Hapus">
        </div>
    `;

    // Tambahkan elemen baru ke dalam container
    container.appendChild(newDiv);

    // Tambahkan event listener ke tombol hapus
    newDiv.querySelector(".removeNSNumber").addEventListener("click", function() {
        newDiv.remove();
    });
});

// Part Number
document.getElementById("addPartNumber").addEventListener("click", function() {
    let container = document.getElementById("partNumberContainer");
    const minIcon = "\\svg\\minus-svgrepo-com.svg";

    // Buat elemen div baru untuk input tambahan
    let newDiv = document.createElement("div");
    newDiv.classList.add("relative", "flex", "mt-3", "outline", "outline-1", "w-[47.25rem]", "items-center", "outline-gray-300", "rounded-xl", "h-[58px]", "focus-within:outline-2", "focus-within:-outline-offset-2", "focus-within:outline-orange-500");

    // Tambahkan input baru
    newDiv.innerHTML = `
        <input
            type="text"
            name="part_number[]"
            class="part-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
            placeholder="Masukkan Part-Number">
        <div class="absolute right-0 mr-4">
            <img class="removePartNumber w-5 h-5 cursor-pointer" src="${minIcon}" alt="Hapus">
        </div>
    `;

    // Tambahkan elemen baru ke dalam container
    container.appendChild(newDiv);

    // Tambahkan event listener ke tombol hapus
    newDiv.querySelector(".removePartNumber").addEventListener("click", function() {
        newDiv.remove();
    });
});

// Serial Number
document.getElementById("addSerialNumber").addEventListener("click", function() {
    let container = document.getElementById("serialNumberContainer");
    const minIcon = "\\svg\\minus-svgrepo-com.svg";

    // Buat elemen div baru untuk input tambahan
    let newDiv = document.createElement("div");
    newDiv.classList.add("relative", "flex", "mt-3", "outline", "outline-1", "w-[47.25rem]", "items-center", "outline-gray-300", "rounded-xl", "h-[58px]", "focus-within:outline-2", "focus-within:-outline-offset-2", "focus-within:outline-orange-500");

    // Tambahkan input baru
    newDiv.innerHTML = `
        <input
            type="text"
            name="serial_number[]"
            class="part-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
            placeholder="Masukkan Serial-Number">
        <div class="absolute right-0 mr-4">
            <img class="removeSerialNumber w-5 h-5 cursor-pointer" src="${minIcon}" alt="Hapus">
        </div>
    `;

    // Tambahkan elemen baru ke dalam container
    container.appendChild(newDiv);

    // Tambahkan event listener ke tombol hapus
    newDiv.querySelector(".removeSerialNumber").addEventListener("click", function() {
        newDiv.remove();
    });
});
