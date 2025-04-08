document.addEventListener('DOMContentLoaded', function () {
    const movementApiUrl = "http://127.0.0.1:8000/api/notifications/movement";
    const container = document.getElementById('notificationsContainer');
    const template = document.getElementById('movement-notification-template');

    async function fetchMovementNotifications() {
        try {
            const response = await axios.get(movementApiUrl);
            const notifications = response.data;

            if (notifications.length === 0) {
                container.innerHTML = `
                    <p class="text-gray-500 text-center">Tidak ada notifikasi saat ini.</p>
                `;
                return;
            }

            container.innerHTML = '';

            notifications.forEach(notification => {
                const isIncoming = notification.type === 'Masuk';
                const isOutcoming = notification.type === 'Keluar';
                const clone = template.content.cloneNode(true);

                // Set icon
                const icon = clone.querySelector('.notification-icon');
                icon.src = isIncoming ? window.NOTIFICATION_IMAGES.in : window.NOTIFICATION_IMAGES.out;

                // Set message and time
                clone.querySelector('.notification-message').textContent = notification.message;
                clone.querySelector('.notification-time').textContent = formatDateTime(notification.created_at);

                // Set status with color
                const status = clone.querySelector('.notification-status');
                if (isIncoming) {
                    status.classList.add('bg-green-600');
                    status.textContent = `Masuk ${notification.quantity} Unit`;
                } else if (isOutcoming) {
                    status.classList.add('bg-red-600');
                    status.textContent = `Keluar ${notification.quantity} Unit`;
                }

                // Tambahkan kelas untuk garis warna
                const garis = clone.querySelector('.garis');
                if (isIncoming) {
                    garis.classList.add('bg-green-600');
                } else if (isOutcoming) {
                    garis.classList.add('bg-red-600');
                }

                container.appendChild(clone);
            });
        } catch (error) {
            console.error("Gagal mengambil notifikasi:", error);
            container.innerHTML = `
                <p class="text-red-500 text-center">Gagal memuat notifikasi.</p>
            `;
        }
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const isToday = date.toDateString() === today.toDateString();

        const time = date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        return isToday
            ? `Hari ini | ${time} WIB`
            : `${date.toLocaleDateString('id-ID')} | ${time} WIB`;
    }

    // Fetch pertama kali saat halaman dimuat
    fetchMovementNotifications();

    // Perbarui setiap 30 detik
    setInterval(fetchMovementNotifications, 30000);
});
