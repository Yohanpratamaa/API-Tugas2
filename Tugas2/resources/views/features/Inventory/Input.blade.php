@extends('layouts.app')

@section('features')

    <div class="w-full h-full flex flex-col justify-center items-center">
        <div class="flex w-full py-8">
            <a href="/dashboard" class="pl-10">
                <img src="{{ asset('img/IHome.png') }}" alt="">
            </a>
            <a href="#" class="pl-3 font-semibold text-gray-500">/ Inventory </a>
            <a href="/inputBarang" class="pl-1 font-semibold {{ request()->is('inputBarang') ? 'text-blue-500' : ''}}">/ Input Barang Masuk </a>
        </div>
        <div class="flex flex-col items-center justify-center w-[47.25rem] h-full pt-[20px]">

            <form method="POST" class="w-full grid grid-cols-1 gap-4" id="inputForm">
                @csrf

                {{-- Nama Barang --}}
                <div class="name">
                    <label for="name" class="block text-[16px] font-bold text-gray-900"> Nama Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input
                            type="text"
                            name="name"
                            id="name" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Nama Barang">
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="quantity">
                    <label for="quantity" class="block text-[16px] font-bold text-gray-900">
                        Jumlah Barang <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="number" name="quantity" id="quantity" required min="0" class=".restrict-input_number w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Jumlah Barang">
                    </div>
                </div>

                {{-- Unit --}}
                <div class="unit">
                    <label for="unit" class="block text-[16px] font-bold text-gray-900"> Satuan Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input
                            type="text"
                            name="unit"
                            id="unit" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Satuan Barang">
                    </div>
                </div>

                {{-- Entry Date --}}
                <div class="entry_date">
                    <label for="entry_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Barang Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="date" name="entry_date" id="entry_date" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Button Submit --}}
                <button type="submit" class="flex justify-center w-full items-center my-6 rounded-full col-span-full bg-blue-500 px-3 py-1.5 text-md/6 font-bold text-white shadow-xs hover:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 text-center" style="height: 50px;"> Input Barang Masuk </button>

            </form>

        </div>
    </div>

    <script src="{{ asset('js/input.js') }}"></script>
    <script src="{{ asset('js/previewImg.js') }}"></script>
    <script src="{{ asset('js/previewDoc.js') }}"></script>

@endsection


