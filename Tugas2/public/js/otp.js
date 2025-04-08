document.addEventListener("DOMContentLoaded", function () {
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyBtn = document.getElementById("verifyOtpBtn");
    const resendOtpBtn = document.getElementById("resendOtpBtn");
    const apiUrl = "http://localhost:8000/api/verify-otp";
    const resendOtpUrl = "http://localhost:8000/api/resend-otp";
    // Fokus otomatis ke input berikutnya
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        // Hapus karakter jika tekan backspace
        input.addEventListener("keydown", (e) => {
            if (
                e.key === "Backspace" &&
                index > 0 &&
                input.value.length === 0
            ) {
                otpInputs[index - 1].focus();
            }
        });
    });

    verifyBtn.addEventListener("click", async function (event) {
        event.preventDefault();

        const otpCode = Array.from(otpInputs)
            .map((input) => input.value)
            .join("");

        if (otpCode.length !== 4) {
            return showAlert("warning", "Oops...", "Masukkan 4 kode OTP");
        }

        try {
            const response = await axios.post(apiUrl, { otp: otpCode });

            if (response.data.status === "success") {
                showAlert("success", "Kode OTP Benar!", "Anda akan diarahkan ke halaman Dashboard...");
                localStorage.setItem("token", response.data.token);
                setTimeout(() => {
                    window.location.href = "/dashboard"
                }, 1000);

            } else {
                showAlert("error", "Kode OTP Salah!", response.data.message || "Verifikasi OTP gagal.");
            }
        } catch (error) {
            showAlert("error", "Terjadi kesalahan", error.response?.data?.message || "Terjadi kesalahan.");
        }
    });

    // resendOtpBtn.addEventListener("click", async function () {
    //     const userEmail = resendOtpBtn.getAttribute("data-email");

    //     if (!userEmail) {
    //         return showAlert("warning", "Oops...", "Email tidak ditemukan. Silakan login ulang.");
    //     }

    //     try {
    //         const response = await axios.post(resendOtpUrl, { email: userEmail });

    //         if (response.data.status === "success") {
    //             showAlert("success", "OTP Dikirim Ulang", "Silakan cek email Anda.");
    //         } else {
    //             showAlert("error", "Gagal Mengirim OTP", response.data.message || "Silakan coba lagi.");
    //         }
    //     } catch (error) {
    //         showAlert("error", "Terjadi kesalahan", error.response?.data?.message || "Terjadi kesalahan.");
    //     }
    // });
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
