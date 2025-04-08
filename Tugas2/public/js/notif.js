document.addEventListener("DOMContentLoaded", function () {
    const movementApiUrl = "http://127.0.0.1:8000/api/notifications/movement";
    const stockApiUrl = "http://127.0.0.1:8000/api/notifications/stock";
    const notificationsContainer = document.getElementById("notificationsContainer");
    const clearButton = document.getElementById("clearNotifications");

    async function fetchAllNotifications() {
        try {
            const [movementResponse, stockResponse] = await Promise.all([
                axios.get(movementApiUrl),
                axios.get(stockApiUrl)
            ]);

            const allNotifications = [
                ...movementResponse.data.map(n => ({ ...n, category: 'movement' })),
                ...stockResponse.data.map(n => ({ ...n, category: 'stock' }))
            ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            renderNotifications(allNotifications);
        } catch (error) {
            console.error("Gagal mengambil notifikasi:", error);
            notificationsContainer.innerHTML = `
                <p class="text-red-500 text-center">Gagal memuat notifikasi.</p>
            `;
        }
    }

    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            notificationsContainer.innerHTML = `
                <p class="text-gray-500 text-center">Tidak ada notifikasi saat ini.</p>
            `;
            return;
        }

        notificationsContainer.innerHTML = "";

        notifications.forEach(notification => {
            let notificationHTML = '';
            
            if (notification.category === 'movement') {
                const isIncoming = notification.type === 'Masuk';
                notificationHTML = createMovementNotification(notification, isIncoming);
            } else {
                notificationHTML = createStockStatusNotification(notification);
            }

            notificationsContainer.innerHTML += notificationHTML;
        });
    }

    function createMovementNotification(notification, isIncoming) {
        return `
            <div class="relative flex items-center border-2 rounded-2xl w-[1074px] h-[129px] mt-10">
                <div class="mx-[40px]">
                    <img src="{{ asset('img/INotif${isIncoming ? 'In' : 'Out'}.png') }}" alt="">
                </div>
                <div class="flex flex-col">
                    <h1 class="text-2xl font-semibold">${notification.message}</h1>
                    <p class="text-gray-500">${formatDateTime(notification.created_at)}</p>
                </div>
                <div class="px-[30px] py-[20px] tanggal right-0 absolute">
                    <h1 class="text-white w-full font-bold bg-${isIncoming ? 'green' : 'red'}-400 text-center py-5 px-10 rounded-xl">
                        ${notification.type} ${notification.quantity} Unit
                    </h1>
                </div>
            </div>
        `;
    }

    function createStockStatusNotification(notification) {
        const isLowStock = notification.type === 'low_stock';
        return `
            <div class="relative flex items-center border-2 rounded-2xl w-[1074px] h-[129px] mt-10">
                <div class="mx-[40px]">
                    <img src="{{ asset('img/INotif${isLowStock ? 'Warning' : 'Expired'}.png') }}" alt="">
                </div>
                <div class="flex flex-col">
                    <h1 class="text-2xl font-semibold">${notification.message}</h1>
                    <p class="text-gray-500">${formatDateTime(notification.created_at)}</p>
                </div>
                <div class="px-[30px] py-[20px] tanggal right-0 absolute">
                    <h1 class="text-white w-full font-bold bg-yellow-400 text-center py-5 px-10 rounded-xl">
                        ${isLowStock ? 'Stok Menipis' : 'Kadaluarsa'}
                    </h1>
                </div>
            </div>
        `;
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

    if (clearButton) {
        clearButton.addEventListener("click", async function () {
            try {
                await Promise.all([
                    axios.delete(`${movementApiUrl}/clear`),
                    axios.delete(`${stockApiUrl}/clear`)
                ]);
                notificationsContainer.innerHTML = `
                    <p class="text-gray-500 text-center">Tidak ada notifikasi saat ini.</p>
                `;
            } catch (error) {
                console.error("Gagal menghapus notifikasi:", error);
            }
        });
    }

    // Initial fetch
    fetchAllNotifications();

    // Refresh notifications every 30 seconds
    setInterval(fetchAllNotifications, 30000);
});