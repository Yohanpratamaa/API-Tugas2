function inventoryDropdown() {
    return {
        open: false,
        selectedLocation: "Pilih Lokasi", // Lokasi yang dipilih
        items: [], // Data asli dari API
        locations: [], // Daftar lokasi unik dari API
        filteredItems: [],

        // Ambil data dari API saat halaman dimuat
        async init() {
            try {
                let response = await fetch("http://127.0.0.1:8000/api/inventories");
                let result = await response.json();
                if (result.status === "success") {
                    this.items = result.data; // Simpan data API
                    this.locations = [...new Set(result.data.map(item => item.location))]; // Ambil lokasi unik
                    this.filteredItems = this.items; // Tampilkan semua data awal
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },

        // Filter data berdasarkan lokasi yang dipilih
        filterLocation(location) {
            this.selectedLocation = location || "Pilih Lokasi";
            this.open = false;

            if (!location || location === "Pilih Lokasi") {
                this.filteredItems = this.items;
            } else {
                this.filteredItems = this.items.filter(item => item.location === location); // Filter data
            }
        }
    };
}

