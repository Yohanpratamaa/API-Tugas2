<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Arsipel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="relative bg-cover bg-center bg-no-repeat min-h-screen" style="background-image: url('{{ asset('img/bg.png') }}');">
    <div class="container flex flex-col w-full min-w-full h-full min-h-full items-center justify-center">
        <x-logo></x-logo>
        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>
