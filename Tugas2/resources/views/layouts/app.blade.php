<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YAP Storage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 m-0 p-0 overflow-auto w-full h-full">

    <section class="flex">
        {{-- sidebar Start --}}
        <x-sidebar></x-sidebar>
        {{-- sidebar End --}}

        {{-- Content Start --}}
        <div class="content relative flex-1 h-screen">

            {{-- Circle Background --}}
            <div class="bg-gray-500 absolute right-0 top-90  w-[200px] h-[400px] rounded-l-full blur-lg">
</div>
            {{-- Circle Background End --}}

            @yield('content')

        </div>
        {{-- Content End --}}
    </section>

</body>
</html>
