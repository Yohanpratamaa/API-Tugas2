document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("userForm");
    const apiInput = "http://127.0.0.1:8000/api/user";
    const apiUrl = "http://localhost:8000/api/users";
    const tableBody = document.getElementById("tableBody");
    const inventoryTable = document.getElementById("inventoryTable");
    const editIcon = "/img/IEdit.png";
    const deleteIcon = "/img/IDelete.png";

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = {
            fullname: document.getElementById("fullname").value,
            username: "Null",
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            phone: document.getElementById("phone").value,
            lokasi: document.getElementById("lokasi").value,
            role: document.getElementById("role").value
        };

        axios.post(apiInput, formData, {
            headers: {
                "Content-Type": "application/json",
            }
        })
        .then(response => {
            console.log(response.data);
            if (response.data.status === "success") {
                showAlert("success", "Berhasil!", "Pengguna berhasil ditambahkan.");
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert("error", "Gagal!", response.data.message);
            }
        })
        .catch(error => {
            console.error(error);
            showAlert("error", "Gagal!", error.response?.data?.message || "Terjadi kesalahan. Silakan coba lagi.");
        });
    });

    function loadUsers() {
        axios.get(apiUrl)
            .then(response => {
                console.log(response.data);
                tableBody.innerHTML = "";

                if (response.data.status === "success" && response.data.data.length > 0) {
                    response.data.data.forEach(item => {
                        const row = document.createElement("tr");

                        row.innerHTML = `
                            <td class="p-4 border text-gray-500">${item.role}</td>
                            <td class="p-4 border text-gray-500">${item.fullname}</td>
                            <td class="p-4 border text-gray-500">${item.email}</td>
                            <td class="p-4 border text-gray-500">********</td>
                            <td class="p-4 border text-gray-500">${item.phone}</td>
                            <td class="p-4 border text-gray-500">${item.lokasi}</td>
                            <td class="p-4 border">
                                <div class="flex">
                                    <button type="button" onclick="openEditModal('${item.id}')" class="flex items-center w-[40px] h-full justify-center rounded-md p-2 ring-1 mr-[10px] shadow-xs ring-gray-300 ring-inset hover:bg-gray-100">
                                        <img src="${editIcon}" alt="" class="w-[18px] h-[20px]">
                                    </button>
                                    <button type="button" onclick="confirmDeleteUser('${item.id}')" class="flex items-center w-[40px] h-full justify-center rounded-md p-2 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-100">
                                        <img src="${deleteIcon}" alt="" class="w-[16px] h-[20px]">
                                    </button>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    inventoryTable.classList.remove("hidden");
                } else {
                    tableBody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data tersedia.</td></tr>`;
                }

            })
            .catch(error => {
                console.error("Error fetching data:", error);
                tableBody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-500">Tidak ada Data Yang Tersedia !</td></tr>`;
            });
        }

    loadUsers();
});

function confirmDeleteUser(id) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F9771F",
        cancelButtonColor: "#22385e",
        confirmButtonText: "Ya, hapus!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`http://127.0.0.1:8000/api/user/${id}`)
            .then(response => {
                console.log("Hasil" + response.data);
                showAlert("success", "Berhasil!", "Pengguna berhasil dihapus.");
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error("Error deleting user:", error);
                showAlert("error", "Gagal!", "Terjadi kesalahan saat menghapus pengguna.");
            });
        }
    });
}

// Fungsi untuk membuka modal dan mengisi data pengguna
function openEditModal(id) {
    axios.get(`http://127.0.0.1:8000/api/user/${id}`)
        .then(response => {
            const user = response.data.data;

            document.getElementById("editUserId").value = id;
            document.getElementById("editFullname").value = user.fullname || "";
            document.getElementById("editEmail").value = user.email || "";
            document.getElementById("editRole").value = user.role || "";
            document.getElementById("editPhone").value = user.phone || "";
            document.getElementById("editLokasi").value = user.lokasi || "";

            // Kosongkan password agar pengguna hanya mengisi jika ingin mengubah
            document.getElementById("editPassword").value = "";

            document.getElementById("editUserModal").classList.remove("hidden");
        })
        .catch(error => {
            console.error("Error fetching user:", error);
            showAlert("error", "Gagal!", "Terjadi kesalahan saat mengambil data pengguna.");
        });
}

// Fungsi untuk menutup modal
function closeEditModal() {
    document.getElementById("editUserModal").classList.add("hidden");
}

// Fungsi untuk mengupdate data pengguna
function updateUser(event) {
    event.preventDefault();

    const id = document.getElementById("editUserId").value;
    const updatedUser = {
        fullname: document.getElementById("editFullname").value,
        email: document.getElementById("editEmail").value,
        role: document.getElementById("editRole").value,
        phone: document.getElementById("editPhone").value,
        lokasi: document.getElementById("editLokasi").value
    };

    const password = document.getElementById("editPassword").value;
    if (password) {
        updatedUser.password = password;
    }

    axios.patch(`http://127.0.0.1:8000/api/user/${id}`, updatedUser)
        .then(response => {
            console.log(response.data);
            showAlert("success", "Berhasil!", "Pengguna berhasil diedit !");
            setTimeout(() => {
                closeEditModal();
                location.reload();
            }, 1500);
        })
        .catch(error => {
            console.error("Error updating user:", error);
            showAlert("error", "Gagal!", "Terjadi kesalahan saat menghapus pengguna.");
            alert("Gagal memperbarui data pengguna!");
        });
}

// Tambahkan event listener untuk form submit
document.getElementById("editUserForm").addEventListener("submit", updateUser);

function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1500
    });
}
