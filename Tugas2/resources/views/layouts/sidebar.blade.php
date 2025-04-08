<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sidebar</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="sidebar sticky h-screen flex flex-col px-[23px] items-center border-1" style="background-color: rgba(34, 56, 94, 1);">
        <x-logo-sidebar></x-logo-sidebar>
        <div>
            <div x-data="{ open: false }" class="relative mb-[30px]">
                <a href="/dashboard">
                    <button
                        type="button"
                        class="flex items-center {{ request()->is('dashboard') ? 'bg-orange-500' : ''}} hover:bg-orange-500 rounded-md px-[37px] py-[10px] min-w-full transition-all duration-300"
                        style="height: 52px; font-size: 20px;">

                        <img src="{{ asset('img/IDash.png') }}" alt="" style="margin-right: 20px">
                        <p class="text-white" style="margin-right: 50px">Dashboard</p>
                    </button>
                </a>
            </div>

            <div x-data="{ open: {{ request()->is('inputBarang') || request()->is('outBarang') || request()->is('inventories') ? 'true' : 'false' }} }" class="mb-[30px]">

                <!-- Button Inventory -->
                <button
                    @click="open = !open"
                    :class="open ? 'bg-orange-500' : ''"
                    type="button"
                    class="flex items-center hover:bg-orange-500 rounded-md px-[37px] py-[10px] min-w-full transition-all duration-300"
                    style="height: 52px; font-size: 20px;">


                    <img src="{{ asset('img/IInventory.png') }}" alt="" style="margin-right: 20px">
                    <p class="text-white" style="margin-right: 50px">Inventory</p>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 max-h-0"
                    x-transition:enter-end="opacity-100 scale-100 max-h-[500px]"
                    x-transition:leave="transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 scale-100 max-h-[500px]"
                    x-transition:leave-end="opacity-0 scale-95 max-h-0"
                    class="overflow-hidden"
                    @click.away="open = false">

                    <div class="pt-[20px] flex flex-col gap-5 text-white">
                        <a href="/inputBarang" class="pl-[50px] {{ request()->is('inputBarang') ? 'text-orange-500' : ''}} text-sm hover:text-orange-500 transition-all duration-300">
                            Input Barang Masuk
                        </a>
                        <a href="/outBarang" class="pl-[50px] {{ request()->is('outBarang') ? 'text-orange-500' : ''}} text-sm hover:text-orange-500 transition-all duration-300">
                            Input Barang Keluar
                        </a>
                    </div>
                </div>
            </div>

</body>
</html>
