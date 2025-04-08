const uploadBox = document.getElementById("uploadBox");
const previewContainer = document.getElementById("previewContainer");
const imageInput = document.getElementById("image");

// Fungsi untuk menangani preview gambar (klik atau drag and drop)
function previewFile(event) {
    let file;
    if (event.target && event.target.files) {
        file = event.target.files[0];
    } else if (event.dataTransfer && event.dataTransfer.files) {
        file = event.dataTransfer.files[0];
    }

    if (!file) return;

    const fileNameDisplay = document.getElementById("fileName");
    const previewIcon = document.getElementById("previewIcon");

    fileNameDisplay.textContent = file.name;
    previewIcon.src = URL.createObjectURL(file); // Menampilkan gambar yang diupload

    uploadBox.classList.add("hidden");
    previewContainer.classList.remove("hidden");
}

// Fungsi untuk membatalkan upload gambar
function cancelImage() {
    imageInput.value = "";
    previewContainer.classList.add("hidden");
    uploadBox.classList.remove("hidden");
}

// Event listener untuk drag and drop gambar
uploadBox.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = "orange";       
    uploadBox.style.borderWidth = "2px";
    uploadBox.style.backgroundColor = "#fff3e6";
});

uploadBox.addEventListener("dragleave", (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = "";
    uploadBox.style.borderWidth = "";
    uploadBox.style.backgroundColor = "";
});

uploadBox.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = "";
    uploadBox.style.borderWidth = "";
    uploadBox.style.backgroundColor = "";
    previewFile(e);
});

// Event listener untuk input file gambar (klik)
imageInput.addEventListener("change", previewFile);
