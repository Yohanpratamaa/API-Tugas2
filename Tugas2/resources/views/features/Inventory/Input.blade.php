@extends('layouts.app')

@section('features')

    <div class="w-full h-full flex flex-col justify-center items-center">
        <div class="flex w-full py-8">
            <a href="/dashboard" class="pl-10">
                <img src="{{ asset('img/IHome.png') }}" alt="">
            </a>
            <a href="#" class="pl-3 font-semibold text-gray-500">/ Inventory </a>
            <a href="/inputBarang" class="pl-1 font-semibold {{ request()->is('inputBarang') ? 'text-orange-500' : ''}}">/ Input Barang Masuk </a>
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

                {{-- Location --}}
                <div class="location">
                    <label for="location" class="block text-[16px] font-bold text-gray-900"> Lokasi Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input
                            type="text"
                            name="location"
                            id="location" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Lokasi Barang">
                    </div>
                </div>

                {{-- Unit Price --}}
                <div class="unit_price">
                    <label for="unit_price" class="block text-[16px] font-bold text-gray-900">
                        Harga Barang Satuan <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <div class="text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                        <input type="text" name="unit_price" id="unit_price" required class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Masukkan Harga Satuan Barang">
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

                {{-- Total Price --}}
                <div class="total_price">
                    <label for="total_price" class="block text-[16px] font-bold text-gray-900">
                        Total Harga <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <div class="flex items-center text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                        <input type="text" name="total_price" id="total_price" class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Total Harga" readonly>
                    </div>
                </div>

                {{-- Entry Date --}}
                <div class="entry_date">
                    <label for="entry_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Barang Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="date" name="entry_date" id="entry_date" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Document Date --}}
                <div class="document_date">
                    <label for="document_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Surat Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="date" name="document_date" id="document_date" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Source --}}
                <div class="source">
                    <label for="source" class="block text-[16px] font-bold text-gray-900"> Sumber Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input
                        type="text"
                        name="source"
                        id="source"
                        required
                        class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6" placeholder="Masukkan Sumber Barang">
                    </div>
                </div>

                {{-- DOM --}}
                <div class="date_of_manufacture">
                    <label for="date_of_manufacture" class="block text-[16px] font-bold text-gray-900">Date of Manufacture (DOM) </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="date" name="date_of_manufacture" id="date_of_manufacture" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                    </div>
                </div>

                {{-- DOE --}}
                <div class="date_of_expired">
                    <label for="date_of_expired" class="block text-[16px] font-bold text-gray-900">Date of Expired (DOE) </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="date" name="date_of_expired" id="date_of_expired" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                    </div>
                </div>

                {{-- Minimum --}}
                <div class="minimum">
                    <label for="minimum" class="block text-[16px] font-bold text-gray-900"> Batas Minimum Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input type="number" name="minimum" id="minimum" required min="0" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6" placeholder="Masukkan Batas Minimum Barang">
                    </div>
                </div>

                {{-- Condition --}}
                <div class="condition">
                    <label for="condition" class="block text-[16px] font-bold text-gray-900"> Kondisi Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input
                            type="text"
                            name="condition"
                            id="condition" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Kondisi Barang">
                    </div>
                </div>

                {{-- Category --}}
                <div class="category">
                    <label for="category" class="block text-[16px] font-bold text-gray-900"> Kategori Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                        <input
                            type="text"
                            name="category"
                            id="category" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Kategori Barang">
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


