document.addEventListener("DOMContentLoaded", function () {
    const expiredApiUrl = "http://127.0.0.1:8000/api/notifications/expired";
    const container = document.getElementById("notificationsContainer");
    const template = document.getElementById("expired-template");

    async function fetchExpiredNotification() {
        try {
            const response = await axios.get(expiredApiUrl);
            const { data: notifications } = response;

            if (!Array.isArray(notifications) || notifications.length === 0) {
                container.innerHTML = `<p class="text-gray-500 text-center">Tidak ada notifikasi stok saat ini.</p>`;
                return;
            }

            container.innerHTML = ""; // Reset sebelum memasukkan notifikasi baru

            notifications.forEach((notification) => {
                const clone = template.content.cloneNode(true);

                clone.querySelector(".notification-icon").src = "{{ asset('img/expired.png') }}";
                clone.querySelector(".notification-message").textContent = notification.message;
                clone.querySelector(".notification-read-status").textContent =
                    notification.read ? "Sudah Dibaca" : "Belum Dibaca";
                clone.querySelector(".notification-time").textContent = formatDateTime(notification.created_at);
                clone.querySelector(".notification-status").textContent = `Expired ${formatDate(notification.date_of_expired)}`;

                container.appendChild(clone);
            });
        } catch (error) {
            console.error("Gagal mengambil notifikasi stok:", error);
            container.innerHTML = `<p class="text-red-500 text-center">Gagal memuat notifikasi stok.</p>`;
        }
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        }) + " WIB";
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" });
    }

    fetchExpiredNotification();
    setInterval(fetchExpiredNotification, 30000);
});
