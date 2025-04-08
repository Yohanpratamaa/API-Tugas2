document.addEventListener('DOMContentLoaded', function() {
    const stockApiUrl = "http://127.0.0.1:8000/api/notifications/stock";
    const container = document.getElementById('notificationsContainer');
    const template = document.getElementById('stock-template');

    async function fetchStockNotifications() {
        try {
            const response = await axios.get(stockApiUrl);
            const notifications = response.data;

            if (notifications.length === 0) {
                container.innerHTML = `
                    <p class="text-gray-500 text-center">Tidak ada notifikasi stok saat ini.</p>
                `;
                return;
            }

            container.innerHTML = '';

            notifications
            .filter(notification => notification.type === 'low_stock')
            .forEach(notification => {
                const clone = template.content.cloneNode(true);

                // Set icon
                const icon = clone.querySelector('.notification-icon');
                icon.src = window.NOTIFICATION_IMAGES.warning;

                // Set message dan waktu
                clone.querySelector('.notification-message').textContent = notification.message;
                clone.querySelector('.notification-time').textContent = formatDateTime(notification.created_at);

                // Set status
                const status = clone.querySelector('.notification-status');
                status.classList.add('bg-red-800');
                status.textContent = `Tersisa ${notification.quantity} Unit`;

                container.appendChild(clone);
            });
        } catch (error) {
            console.error("Gagal mengambil notifikasi stok:", error);
            container.innerHTML = `
                <p class="text-red-500 text-center">Gagal memuat notifikasi stok.</p>
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

    // Initial fetch
    fetchStockNotifications();

    // Refresh every 30 seconds
    setInterval(fetchStockNotifications, 30000);
});