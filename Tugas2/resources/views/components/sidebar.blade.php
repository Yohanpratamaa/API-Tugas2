<div class="sidebar">
    <aside class="w-75 h-screen bg-gray-600">
        <div class="w-full h-full flex flex-col gap-y-10 items-center">
            <img src="{{ asset('img/icon.png') }}" alt="" class="w-1/3 mt-10">
            <ul class="text-2xl flex flex-col gap-y-7 text-white h-full">
                <li class="flex items-center hover:bg-gray-700 w-full px-8 py-4 rounded-full">
                    <x-svgHome class="mt-1 mr-2"></x-svgHome>
                    <a href="/dashboard">Dashboard</a>
                </li>
                <li class="flex hover:bg-gray-700 w-full px-8 py-4 rounded-full">
                    <x-svgInput class="mt-1 mr-2"></x-svgInput>
                    <a href="/users">Input Barang</a>
                </li>
            </ul>
        </div>
    </aside>
</div>
