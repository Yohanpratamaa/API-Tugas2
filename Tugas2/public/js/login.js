document.addEventListener("DOMContentLoaded", () => {
    const roleButtons = document.querySelectorAll(".role-btn");
    const roleInput = document.getElementById("role");

    roleButtons.forEach(button => {
        button.addEventListener("click", () => {
            roleInput.value = button.getAttribute("data-role");
            roleButtons.forEach(btn => btn.classList.remove("bg-orange-500"));
            button.classList.add("bg-orange-500", "text-white");
        });
    });

    document.getElementById("loginForm").addEventListener("submit", async (event) => {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const role = document.getElementById("role").value;

        if (!role) {
            return showAlert("warning", "Oops...", "Pilih role terlebih dahulu!");
        }

        try {
            const response = await axios.post("http://localhost:8000/api/login", { email, password, role });

            if (response.data.status === "success") {
                showAlert("success", "Login Berhasil!", "Anda akan diarahkan ke halaman OTP...");
                localStorage.setItem("user_id", response.data.user_id);
                setTimeout(() => {
                    window.location.href = "/otp"
                }, 1000);

            } else {
                showAlert("error", "Login Gagal", response.data.message);
            }
        } catch (error) {
            showAlert("error", "Login Gagal", error.response?.data?.message || "Terjadi kesalahan. Silakan coba lagi.");
        }
    });
});


// document.addEventListener("DOMContentLoaded", () => {
//     const roleButtons = document.querySelectorAll(".role-btn");
//     const roleInput = document.getElementById("role");

//     // Mengatur role berdasarkan tombol yang diklik
//     roleButtons.forEach(button => {
//         button.addEventListener("click", () => {
//             roleInput.value = button.getAttribute("data-role");
//             roleButtons.forEach(btn => btn.classList.remove("bg-orange-500"));
//             button.classList.add("bg-orange-500", "text-white");
//         });
//     });

//     document.getElementById("loginForm").addEventListener("submit", async (event) => {
//         event.preventDefault();

//         const email = document.getElementById("email").value;
//         const password = document.getElementById("password").value;
//         const selectedRole = document.getElementById("role").value;

//         if (!selectedRole) {
//             return showAlert("warning", "Oops...", "Pilih role terlebih dahulu!");
//         }

//         try {
//             // 1️⃣ Login ke API pertama (autentikasi)
//             const loginResponse = await axios.post("http://localhost:8000/api/login", { email, password });
//             console.log("Login Response:", loginResponse.data);

//             if (loginResponse.data.status !== "success") {
//                 return showAlert("error", "Login Gagal", loginResponse.data.message);
//             }

//             const userId = loginResponse.data.id;
//             console.log("Idnya: "+userId); // Simpan user_id dari API pertama

//             // 2️⃣ Ambil role dari API kedua berdasarkan user_id
//             const roleResponse = await axios.get(`http://localhost:8000/api/user/${userId}`);

//             if (roleResponse.data.status !== "success") {
//                 return showAlert("error", "Gagal", "Tidak dapat mengambil role pengguna!");
//             }

//             const userRole = roleResponse.data.role; // Role dari API kedua

//             // 3️⃣ Cek apakah role yang dipilih cocok dengan role dari API kedua
//             if (userRole !== selectedRole) {
//                 return showAlert("error", "Login Gagal", "Role yang dipilih tidak sesuai dengan yang terdaftar!");
//             }

//             // 4️⃣ Jika role cocok, lanjutkan login
//             showAlert("success", "Login Berhasil!", "Anda akan diarahkan ke halaman OTP...");
//             localStorage.setItem("user_id", userId);

//             setTimeout(() => {
//                 window.location.href = "/otp";
//             }, 1000);

//         } catch (error) {
//             console.error("Error:", error);
//             showAlert("error", "Login Gagal", error.response?.data?.message || "Terjadi kesalahan. Silakan coba lagi.");
//         }
//     });
// });



function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1500
    });
}
