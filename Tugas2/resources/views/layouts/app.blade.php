<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Arsipel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-full w-[282px] bg-gray-900 z-10 shadow-lg">
            @include('layouts.sidebar')
        </aside>

        <!-- Content -->
        <main class=" flex-1 ml-[282px] overflow-auto">
            <div class="h-[65px] py-[20px] border-b-2 relative flex items-center justify-end px-8">
                <div class="w-[1px] h-[20px] bg-gray-300 mx-4"></div>
                <h1 class="font-semibold">Dashboard</h1>
            </div>

            <div class=" features">
                @yield('features')
            </div>
        </main>
    </div>

</body>
</html>
