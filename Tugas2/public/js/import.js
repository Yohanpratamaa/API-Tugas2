function showImport() {
    Swal.fire({
        title: '<span style="color:#22385e; font-size:30px; font-weight:bold;">Import Data Barang</span>',
        html: '<p style="color:#555; font-size:14px;">Apakah Anda yakin ingin mengimpor data barang?</p>',
        imageUrl: "/img/ImportPDF.png",
        imageWidth: 131,
        imageHeight: 115,
        showCancelButton: true,
        confirmButtonText:
            '<span style="color:white; font-weight:bold;">Pilih File</span>',
        cancelButtonText:
            '<span style="color:white; font-weight:bold;">Download Template</span>',
        reverseButtons: true,
        customClass: {
            popup: "swal2-popup",
            confirmButton: "swal2-confirm",
            cancelButton: "swal2-cancel",
        },
        didOpen: () => {
            document.querySelector(".swal2-confirm").style.cssText =
                "background-color: #22385e; padding: 10px 15px; border-radius: 5px;";
            document.querySelector(".swal2-cancel").style.cssText =
                "background-color: #d33; padding: 10px 15px; border-radius: 5px;";
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '<span style="color:#22385e; font-size:24px; font-weight:bold;">Upload Dokumen</span>',
                html: `
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                        <div class="text-center">
                            <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm-2 13v-4H8l4-4 4 4h-2v4h-4z"/>
                            </svg>
                            <div class="mt-4 flex justify-center items-center text-sm text-gray-600">
                                <label for="excelFile" class="relative cursor-pointer rounded-md bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                    <span id="fileLabel">Choose File</span>
                                    <input type="file" id="excelFile" accept=".xlsx,.xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </label>
                                <span id="fileName" class="ml-3 text-gray-500">No file chosen</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">XLSX, XLS max 10MB</p>
                        </div>
                    </div>
                    <div id="importMessage" class="mt-4 text-sm"></div>
                `,
                showCancelButton: true,
                confirmButtonText:
                    '<span style="color:white; font-weight:bold;">Import</span>',
                cancelButtonText:
                    '<span style="color:white; font-weight:bold;">Batal</span>',
                customClass: {
                    popup: "swal2-popup",
                    confirmButton: "swal2-confirm",
                    cancelButton: "swal2-cancel",
                },
                width: "600px",
                didOpen: () => {
                    document.querySelector(".swal2-confirm").style.cssText =
                        "background-color: #22385e; padding: 10px 15px; border-radius: 5px;";
                    document.querySelector(".swal2-cancel").style.cssText =
                        "background-color: #d33; padding: 10px 15px; border-radius: 5px;";

                    // Update nama file pas user pilih file
                    const fileInput = document.getElementById("excelFile");
                    const fileNameSpan = document.getElementById("fileName");
                    fileInput.addEventListener("change", () => {
                        if (fileInput.files.length > 0) {
                            fileNameSpan.textContent = fileInput.files[0].name;
                        } else {
                            fileNameSpan.textContent = "No file chosen";
                        }
                    });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const fileInput = document.getElementById("excelFile");
                    const file = fileInput.files[0];
                    const messageDiv = document.getElementById("importMessage");

                    if (!file) {
                        messageDiv.innerHTML =
                            '<span class="text-red-500">Pilih file terlebih dahulu!</span>';
                        return;
                    }

                    const formData = new FormData();
                    formData.append("file", file);
                    formData.append(
                        "_token",
                        document.querySelector('meta[name="csrf-token"]')
                            .content
                    );

                    // Langsung import tanpa preview
                    axios
                        .post("/api/inventories/importExcel", formData)
                        .then((response) => {
                            messageDiv.innerHTML = `<span class="text-green-500">${response.data.message}</span>`;
                            setTimeout(() => {
                                Swal.close();
                                // Update tabel tanpa reload halaman
                                fetchAndPopulateTable();
                            }, 2000);
                        })
                        .catch((error) => {
                            messageDiv.innerHTML = `<span class="text-red-500">${
                                error.response?.data?.message ||
                                "Terjadi kesalahan"
                            }</span>`;
                        });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.location.href = "/inventories/template";
        }
    });
}
