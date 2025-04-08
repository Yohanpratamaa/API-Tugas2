// Ambil elemen yang dibutuhkan
const uploadBoxDoc = document.getElementById("uploadBoxDoc");
const previewContainerDoc = document.getElementById("previewContainerDoc");
const documentInput = document.getElementById("document");

// Fungsi untuk menangani preview dokumen (klik atau drag and drop)
function previewDocument(event) {
    let file;
    if (event.target && event.target.files) {
        // Dari input file biasa (klik)
        file = event.target.files[0];
    } else if (event.dataTransfer && event.dataTransfer.files) {
        // Dari drag and drop
        file = event.dataTransfer.files[0];
    }

    if (!file) return;

    document.getElementById("fileNameDoc").textContent = file.name;

    const previewIcon = document.getElementById("previewIconDoc");
    if (file.type.includes("pdf") || file.name.endsWith(".pdf")) {
        previewIcon.src = "/svg/pdf-svgrepo-com.svg";
    } else if (file.type.includes("word") || file.name.endsWith(".doc") || file.name.endsWith(".docx")) {
        previewIcon.src = "/svg/doc-svgrepo-com.svg";
    } else if (file.type.includes("excel") || file.name.endsWith(".xls") || file.name.endsWith(".xlsx")) {
        previewIcon.src = "/svg/excel-file-type-svgrepo-com.svg";
    } else {
        previewIcon.src = "/svg/file-svgrepo-com.svg";
    }

    previewContainerDoc.classList.remove("hidden");
    uploadBoxDoc.classList.add("hidden");
}

// Fungsi untuk membatalkan upload
function cancelDocument() {
    documentInput.value = "";
    previewContainerDoc.classList.add("hidden");
    uploadBoxDoc.classList.remove("hidden");
    uploadBoxDoc.classList.add("flex");
}

// Event listener untuk drag and drop
uploadBoxDoc.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadBoxDoc.style.borderColor = "orange";       // Ubah warna border
    uploadBoxDoc.style.borderWidth = "2px";          // Tebalkan border
    uploadBoxDoc.style.backgroundColor = "#fff3e6";  // Warna latar belakang lembut
});

uploadBoxDoc.addEventListener("dragleave", (e) => {
    e.preventDefault();
    uploadBoxDoc.style.borderColor = "";             // Kembali ke warna default
    uploadBoxDoc.style.borderWidth = "";             // Kembali ke lebar default
    uploadBoxDoc.style.backgroundColor = "";         // Hapus warna latar belakang
});

uploadBoxDoc.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadBoxDoc.style.borderColor = "";             // Kembali ke warna default
    uploadBoxDoc.style.borderWidth = "";             // Kembali ke lebar default
    uploadBoxDoc.style.backgroundColor = "";         // Hapus warna latar belakang
    previewDocument(e);
});

// Event listener untuk input file biasa (klik "Upload a document")
documentInput.addEventListener("change", previewDocument);
