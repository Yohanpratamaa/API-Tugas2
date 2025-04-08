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
    const pastedText = (event.clipboardData || window.clipboardData).getData(
        "text"
    );
    if (!/^[a-zA-Z0-9\s]*$/.test(pastedText)) {
        event.preventDefault();
        const validText = pastedText.replace(/[^a-zA-Z0-9\s]/g, "");
        event.target.value = validText;
    }
}

// Terapkan event listener ke semua input
inputs.forEach((input) => {
    input.addEventListener("input", restrictInput);
    input.addEventListener("paste", restrictPaste);
});

document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const barangId = urlParams.get("id");
    const apiUrl = `http://127.0.0.1:8000/api/inventory/${barangId}`;

    const unitPriceInput = document.getElementById("editUnitPrice");
    const quantityInput = document.getElementById("editQuantity");
    const totalPriceInput = document.getElementById("editTotalPrice");

    function formatNumber(value) {
        return new Intl.NumberFormat("id-ID").format(BigInt(value));
    }

    function cleanNumber(value) {
        // Hapus semua karakter non-digit kecuali desimal, lalu ambil bagian bulat
        const cleaned = String(value)
            .replace(/[^\d.]/g, "")
            .split(".")[0];
        return cleaned || "0"; // Default ke "0" jika kosong
    }

    function hitungTotal() {
        let unitPrice = BigInt(cleanNumber(unitPriceInput.dataset.raw || "0"));
        let quantity = BigInt(cleanNumber(quantityInput.dataset.raw || "0"));
        let total = unitPrice * quantity;
        totalPriceInput.value = formatNumber(total);
    }

    unitPriceInput.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "");
        if (rawValue === "") rawValue = "0";
        e.target.dataset.raw = rawValue;
        e.target.value = formatNumber(rawValue);
        hitungTotal();
    });

    quantityInput.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "");
        if (rawValue === "") rawValue = "0";
        e.target.dataset.raw = rawValue;
        e.target.value = formatNumber(rawValue);
        hitungTotal();
    });

    if (barangId) {
        axios
            .get(apiUrl)
            .then((response) => {
                const inventory = response.data.data;
                const partNumbers = Array.isArray(inventory.part_number)
                    ? inventory.part_number
                    : [inventory.part_number];
                const container = document.getElementById(
                    "partNumberContainer"
                );

                // Kosongkan container dan isi ulang dengan data
                container.innerHTML = "";

                // Tambahkan field pertama dengan tombol Tambah
                if (partNumbers.length > 0) {
                    createPartNumberField(
                        container,
                        partNumbers[0],
                        false,
                        true
                    );
                    partNumbers.slice(1).forEach((partNumber) => {
                        createPartNumberField(
                            container,
                            partNumber,
                            true,
                            false
                        );
                    });
                } else {
                    createPartNumberField(container, "", false, true);
                }

                // Isi dan bersihkan data dari API
                unitPriceInput.value = formatNumber(
                    cleanNumber(inventory.unit_price || "0")
                );
                unitPriceInput.dataset.raw = cleanNumber(
                    inventory.unit_price || "0"
                );
                quantityInput.value = formatNumber(
                    cleanNumber(inventory.quantity || "0")
                );
                quantityInput.dataset.raw = cleanNumber(
                    inventory.quantity || "0"
                );

                document.getElementById("editBarangId").value = barangId;
                document.getElementById("editName").value =
                    inventory.name || "";
                document.getElementById("editLocation").value =
                    inventory.location || "";
                document.getElementById("editUnit").value =
                    inventory.unit || "";
                document.getElementById("editEntryDate").value =
                    inventory.entry_date
                        ? inventory.entry_date.split("T")[0]
                        : "";
                document.getElementById("editSource").value =
                    inventory.source || "";
                document.getElementById("editDocumentDate").value =
                    inventory.document_date
                        ? inventory.document_date.split("T")[0]
                        : "";
                document.getElementById("editDOM").value =
                    inventory.date_of_manufacture
                        ? inventory.date_of_manufacture.split("T")[0]
                        : "";
                document.getElementById("editDOE").value =
                    inventory.date_of_expired
                        ? inventory.date_of_expired.split("T")[0]
                        : "";
                document.getElementById("editMinimum").value =
                    inventory.minimum || "";
                document.getElementById("editCondition").value =
                    inventory.condition || "";
                document.getElementById("editCategory").value =
                    inventory.category || "";

                // Handle image
                const imageUrl = inventory.image
                    ? `http://127.0.0.1:8000/storage/${inventory.image}`
                    : null;
                const previewContainerNoImg = document.getElementById(
                    "previewContainerNoImg"
                );
                const previewContainerImg = document.getElementById(
                    "previewContainerImg"
                );
                const previewIconNoImg =
                    document.getElementById("previewIconNoImg");
                const previewIconImg =
                    document.getElementById("previewIconImg");
                const fileNameNoImg = document.getElementById("fileNameNoImg");
                const fileNameImg = document.getElementById("fileNameImg");

                // Pastikan semua elemen ada sebelum mengaksesnya
                if (
                    previewContainerNoImg &&
                    previewContainerImg &&
                    previewIconNoImg &&
                    previewIconImg &&
                    fileNameNoImg &&
                    fileNameImg
                ) {
                    if (imageUrl) {
                        // Tampilkan preview image
                        previewContainerImg.style.display = "flex";
                        previewContainerNoImg.style.display = "none";
                        previewIconImg.src = imageUrl;
                        fileNameImg.textContent = getImageName(imageUrl);
                    } else {
                        // Tampilkan "tidak ada image"
                        previewContainerNoImg.style.display = "flex";
                        previewContainerImg.style.display = "none";
                        previewIconNoImg.src = "\\svg\\img-box-svgrepo-com.svg";
                        fileNameNoImg.textContent = "Tidak ada gambar";
                    }
                } else {
                    console.error(
                        "One or more preview elements are missing in the DOM"
                    );
                }

                const documentUrl = inventory.document
                    ? `http://127.0.0.1:8000/storage/${inventory.document}`
                    : null;
                const previewContainerNoDoc = document.getElementById(
                    "previewContainerNoDoc"
                );
                const previewContainerDoc = document.getElementById(
                    "previewContainerDoc"
                );
                const previewIconNoDoc =
                    document.getElementById("previewIconNoDoc");
                const previewIconDoc =
                    document.getElementById("previewIconDoc");
                const fileNameNoDoc = document.getElementById("fileNameNoDoc");
                const fileNameDoc = document.getElementById("fileNameDoc");

                // Pastikan semua elemen ada sebelum mengaksesnya
                if (
                    previewContainerNoDoc &&
                    previewContainerDoc &&
                    previewIconNoDoc &&
                    previewIconDoc &&
                    fileNameNoDoc &&
                    fileNameDoc
                ) {
                    if (documentUrl) {
                        // Tampilkan preview dokumen
                        previewContainerDoc.style.display = "flex";
                        previewContainerNoDoc.style.display = "none";
                        previewIconDoc.src = getDocumentIcon(documentUrl);
                        fileNameDoc.textContent = getDocumentName(documentUrl);
                    } else {
                        // Tampilkan "tidak ada dokumen"
                        previewContainerNoDoc.style.display = "flex";
                        previewContainerDoc.style.display = "none";
                        previewIconNoDoc.src = getDocumentIcon(null);
                        fileNameNoDoc.textContent = getDocumentName(null);
                    }
                }

                hitungTotal();
            })
            .catch((error) => {
                console.error("Error fetching Inventory:", error);
                alert("Gagal mengambil data barang!");
            });
    } else {
        unitPriceInput.value = formatNumber("0");
        unitPriceInput.dataset.raw = "0";
        quantityInput.value = formatNumber("0");
        quantityInput.dataset.raw = "0";
        hitungTotal();
    }
});

// Fungsi untuk menentukan ikon dokumen berdasarkan ekstensi
function getDocumentIcon(documentUrl) {
    if (!documentUrl) return "/svg/file-svgrepo-com.svg";
    const ext = documentUrl.split(".").pop().toLowerCase();
    switch (ext) {
        case "pdf":
            return "\\svg\\pdf-svgrepo-com.svg";
        case "doc":
        case "docx":
            return "/svg/word-icon.svg";
        case "xls":
        case "xlsx":
            return "/svg/excel-icon.svg";
        default:
            return "/svg/default-doc-icon.svg";
    }
}

// Fungsi untuk mendapatkan nama file dari URL
function getDocumentName(documentUrl) {
    if (!documentUrl) return "Tidak ada dokumen";
    return documentUrl.split("/").pop();
}

// Fungsi untuk mendapatkan nama file dari URL
function getImageName(imageUrl) {
    if (!imageUrl) return "Tidak ada gambar";
    return imageUrl.split("/").pop();
}

function getImageIcon(imageUrl) {
    if (!imageUrl) return "\\svg\\img-box-svgrepo-com.svg";
    return imageUrl.split("/").pop();
}

// ======================= IMAGE ===========================
const imageInput = document.getElementById("image");
const imageReplaceInput = document.getElementById("imageReplace");
let shouldDeleteImage = false;

// Fungsi untuk preview image saat upload
function previewImage(event) {
    const file = event.target.files[0];
    const previewContainerNoImg = document.getElementById(
        "previewContainerNoImg"
    );
    const previewContainerImg = document.getElementById("previewContainerImg");
    const previewIconImg = document.getElementById("previewIconImg");
    const fileNameImg = document.getElementById("fileNameImg");

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewContainerImg.style.display = "flex";
            previewContainerNoImg.style.display = "none";
            previewIconImg.src = e.target.result;
            fileNameImg.textContent = file.name;
            shouldDeleteImage = false;
        };
        reader.readAsDataURL(file);
    }
}

function cancelImage() {
    if (imageInput) imageInput.value = "";
    if (imageReplaceInput) imageReplaceInput.value = "";
    if (previewContainerImg) previewContainerImg.style.display = "none";
    if (previewContainerNoImg) previewContainerNoImg.style.display = "flex";
    if (previewIconNoImg) previewIconNoImg.src = getImageIcon(null);
    if (fileNameNoImg) fileNameNoImg.textContent = getImageName(null);
    shouldDeleteImage = true;
    console.log("shouldDeleteImage:", shouldDeleteImage);
}

// Event listener untuk input file dengan pengecekan null
if (imageInput) imageInput.addEventListener("change", previewImage);
if (imageReplaceInput)
    imageReplaceInput.addEventListener("change", previewImage);

// ======================= DOKUMEN ===========================
let shouldDeleteDocument = false;
const documentInput = document.getElementById("document");
const documentReplaceInput = document.getElementById("documentReplace");

function previewDocument(event) {
    const file = event.target.files[0];
    const previewContainerNoDoc = document.getElementById(
        "previewContainerNoDoc"
    );
    const previewContainerDoc = document.getElementById("previewContainerDoc");
    const previewIconDoc = document.getElementById("previewIconDoc");
    const fileNameDoc = document.getElementById("fileNameDoc");

    if (file) {
        previewContainerDoc.style.display = "flex";
        previewContainerNoDoc.style.display = "none";
        previewIconDoc.src = getDocumentIcon(file.name);
        fileNameDoc.textContent = file.name;
        shouldDeleteDocument = false;
    }
}

function cancelDocument() {
    if (documentInput) documentInput.value = "";
    if (documentReplaceInput) documentReplaceInput.value = "";
    if (previewContainerDoc) previewContainerDoc.style.display = "none";
    if (previewContainerNoDoc) previewContainerNoDoc.style.display = "flex";
    if (previewIconNoDoc) previewIconNoDoc.src = getDocumentIcon(null);
    if (fileNameNoDoc) fileNameNoDoc.textContent = getDocumentName(null);
    shouldDeleteDocument = true;
}

// Event listener untuk input file dengan pengecekan null
if (documentInput) documentInput.addEventListener("change", previewDocument);
if (documentReplaceInput)
    documentReplaceInput.addEventListener("change", previewDocument);

// Function to create part number field
function createPartNumberField(
    container,
    partNumber,
    showRemove = false,
    showAdd = false
) {
    const minIcon = "/svg/minus-svgrepo-com.svg";
    const plusIcon = "/svg/plus-large-svgrepo-com.svg";

    let newDiv = document.createElement("div");
    newDiv.classList.add(
        "relative",
        "flex",
        "mt-3",
        "outline",
        "outline-1",
        "w-[47.25rem]",
        "items-center",
        "outline-gray-300",
        "rounded-xl",
        "h-[58px]",
        "focus-within:outline-2",
        "focus-within:-outline-offset-2",
        "focus-within:outline-orange-500"
    );

    newDiv.innerHTML = `
        <input
            type="text"
            name="part_number[]"
            class="text-base text-gray-900 w-full block focus:outline-none grow min-w-0 ml-5 part-number-input pl-1 placeholder:text-gray-400 pr-3 py-1.5"
            value="${partNumber || ""}"
            placeholder="Masukkan Serial Number/Part Number Barang">
        <div class="absolute mr-4 right-0">
            ${
                showRemove
                    ? `<img class="h-[20px] w-[20px] cursor-pointer removePartNumber" src="${minIcon}" alt="Hapus">`
                    : ""
            }
            ${
                showAdd
                    ? `<img id="addPartNumber" class="h-[20px] w-[20px] cursor-pointer" src="${plusIcon}" alt="Tambah">`
                    : ""
            }
        </div>
    `;

    container.appendChild(newDiv);

    if (showRemove) {
        newDiv
            .querySelector(".removePartNumber")
            .addEventListener("click", function () {
                newDiv.remove();
            });
    }

    if (showAdd) {
        newDiv
            .querySelector("#addPartNumber")
            .addEventListener("click", function () {
                createPartNumberField(container, "", true, false); // Tambah field baru dengan tombol Hapus
            });
    }
}

// Add new part number field
document.getElementById("addPartNumber").addEventListener("click", function () {
    const container = document.getElementById("partNumberContainer");
    createPartNumberField(container, "", true);
});

function updateBarang(event) {
    event.preventDefault();

    const id = document.getElementById("editBarangId").value;

    // Ambil semua part numbers dari input fields
    const partNumberInputs = document.querySelectorAll(
        'input[name="part_number[]"]'
    );
    const partNumbers = Array.from(partNumberInputs)
        .map((input) => input.value)
        .filter((value) => value.trim() !== ""); // Filter nilai kosong

    // Buat FormData untuk mengirim data termasuk file
    const formData = new FormData();
    formData.append("name", document.getElementById("editName").value);
    formData.append("location", document.getElementById("editLocation").value);
    partNumbers.forEach((partNumber, index) => {
        formData.append(`part_number[${index}]`, partNumber);
    });
    formData.append(
        "unit_price",
        document.getElementById("editUnitPrice").dataset.raw || "0"
    );
    formData.append("quantity", document.getElementById("editQuantity").value);
    formData.append(
        "total_price",
        document.getElementById("editTotalPrice").value
    );
    formData.append("unit", document.getElementById("editUnit").value);
    formData.append(
        "entry_date",
        document.getElementById("editEntryDate").value
    );
    formData.append("source", document.getElementById("editSource").value);
    formData.append(
        "document_date",
        document.getElementById("editDocumentDate").value
    );
    formData.append(
        "date_of_manufacture",
        document.getElementById("editDOM").value
    );
    formData.append(
        "date_of_expired",
        document.getElementById("editDOE").value
    );
    formData.append("minimum", document.getElementById("editMinimum").value);
    formData.append(
        "condition",
        document.getElementById("editCondition").value
    );
    formData.append("category", document.getElementById("editCategory").value);

    // Tambahkan file document jika ada
    const documentInput = document.getElementById("document");

    if (documentInput && documentInput.files.length > 0) {
        formData.append("document", documentInput.files[0]);
    } else if (shouldDeleteDocument) {
        formData.append("delete_document", true);
    }

    // Tambahkan file image jika ada
    const imageInput = document.getElementById("image");

    if (imageInput && imageInput.files.length > 0) {
        formData.append("image", imageInput.files[0]);
    } else if (shouldDeleteImage) {
        formData.append("delete_image", true);
    }

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
            showAlert(
                "success",
                "Berhasil!",
                "Data barang berhasil diperbarui!"
            );
            setTimeout(() => {
                window.location.href = "/inventories";
            }, 1500);
        })
        .catch((error) => {
            console.error("Error updating barang:", error);
            showAlert(
                "error",
                "Gagal!",
                "Terjadi kesalahan saat memperbarui data barang."
            );
        });
}

// Tambahkan event listener untuk form submit
document
    .getElementById("editBarangForm")
    .addEventListener("submit", updateBarang);

function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1500,
    });
}
