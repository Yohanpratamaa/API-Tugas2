@extends('layouts.app')

@section('features')

    <div class="w-full h-full flex flex-col justify-center items-center">
        <div class="flex w-full py-8">
            <a href="/dashboard" class="pl-10">
                <img src="{{ asset('img/IHome.png') }}" alt="">
            </a>
            <a href="#" class="pl-3 font-semibold text-gray-500">/ Inventory </a>
            <a href="/editBarang" class="pl-1 font-semibold {{ request()->is('editBarang') ? 'text-orange-500' : ''}}">/ Edit Barang </a>
        </div>
        <div class="flex flex-col items-center justify-center w-[47.25rem] h-full pt-[20px]">

            <form class="w-full grid grid-cols-1 gap-4" id="editBarangForm">

                <input type="hidden" id="editBarangId">

                {{-- Nama Barang --}}
                <div class="name">
                    <label for="name" class="block text-[16px] font-bold text-gray-900"> Nama Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="name"
                            id="editName" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Nama Barang">
                    </div>
                </div>

                {{-- Part Number --}}
                <div class="part_number">
                    <label for="part_number" class="block text-[16px] font-bold text-gray-900">
                        Serial Number/Part Number Barang <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <div id="partNumberContainer">
                            <!-- First input with add button -->
                            <div class="relative flex mt-3 outline outline-1 w-[47.25rem] items-center outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                <input
                                    type="text"
                                    name="part_number[]"
                                    class="part-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
                                    placeholder="Masukkan Serial Number/Part Number Barang">
                                <div class="absolute right-0 mr-4">
                                    <img id="addPartNumber" class="w-[20px] h-[20px] cursor-pointer" src="{{ asset('svg/plus-large-svgrepo-com.svg') }}" alt="Tambah">
                                </div>
                            </div>
                            <!-- Additional part number fields will be dynamically generated here -->
                        </div>
                    </div>
                </div>

                {{-- Location --}}
                <div class="location">
                    <label for="location" class="block text-[16px] font-bold text-gray-900"> Lokasi Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="location"
                            id="editLocation" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Lokasi Barang">
                    </div>
                </div>

                {{-- Unit Price --}}
                <div class="unit_price">
                    <label for="unit_price" class="block text-[16px] font-bold text-gray-900">
                        Harga Barang Satuan <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <div class="text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                        <input type="text" name="unit_price" id="editUnitPrice" required class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Masukkan Harga Satuan Barang">
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="quantity">
                    <label for="quantity" class="block text-[16px] font-bold text-gray-900">
                        Jumlah Barang <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="number" name="quantity" id="editQuantity" required min="0" class="w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Jumlah Barang">
                    </div>
                </div>

                {{-- Unit --}}
                <div class="unit">
                    <label for="unit" class="block text-[16px] font-bold text-gray-900"> Satuan Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="unit"
                            id="editUnit" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Satuan Barang">
                    </div>
                </div>

                {{-- Total Price --}}
                <div class="total_price">
                    <label for="total_price" class="block text-[16px] font-bold text-gray-900">
                        Total Harga <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <div class="flex items-center text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                        <input type="number" name="total_price" id="editTotalPrice" class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Total Harga" readonly>
                    </div>
                </div>

                {{-- Entry Date --}}
                <div class="entry_date">
                    <label for="entry_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Barang Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="entry_date" id="editEntryDate" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Document Date --}}
                <div class="document_date">
                    <label for="document_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Surat Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="editDocumentDate" id="editDocumentDate" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Source --}}
                <div class="source">
                    <label for="source" class="block text-[16px] font-bold text-gray-900"> Sumber Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="text" name="source" id="editSource" required class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6" placeholder="Masukkan Sumber Barang">
                    </div>
                </div>

                {{-- DOM --}}
                <div class="date_of_manufacture">
                    <label for="EditDOM" class="block text-[16px] font-bold text-gray-900">Date of Manufacture (DOM) </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="EditDOM" id="editDOM" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                    </div>
                </div>

                {{-- DOE --}}
                <div class="date_of_expired">
                    <label for="date_of_expired" class="block text-[16px] font-bold text-gray-900">Date of Expired (DOE) </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="editDOE" id="editDOE" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                    </div>
                </div>

                {{-- Minimum --}}
                <div class="minimum">
                    <label for="minimum" class="block text-[16px] font-bold text-gray-900"> Batas Minimum Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="number" name="editMinimum" id="editMinimum" required min="0" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6" placeholder="Masukkan Batas Minimum Barang">
                    </div>
                </div>

                {{-- Condition --}}
                <div class="condition">
                    <label for="condition" class="block text-[16px] font-bold text-gray-900"> Kondisi Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="condition"
                            id="editCondition" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Kondisi Barang">
                    </div>
                </div>

                {{-- Category --}}
                <div class="category">
                    <label for="category" class="block text-[16px] font-bold text-gray-900"> Kategori Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="category"
                            id="editCategory" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Kategori Barang">
                    </div>
                </div>

                {{-- Document --}}
                <div class="col-span-full mt-4">
                    {{-- Label --}}
                    <label for="document" class="block text-[16px] font-bold text-gray-900">
                        Dokumen Barang
                    </label>

                    {{-- Tampilan Preview Tidak ada Dokumen --}}
                    <div id="previewContainerNoDoc" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3" style="display: none;">
                        <div class="flex relative w-full">
                            <div class="flex items-center space-x-3">
                                <!-- Ikon dokumen -->
                                <img id="previewIconNoDoc" src="/svg/file-svgrepo-com.svg" class="w-8 object-cover rounded-md" alt="Preview">
                                <!-- Nama file -->
                                <span id="fileNameNoDoc">Tidak ada dokumen</span>
                            </div>
                            <label for="document" class="text-center absolute right-0 bg-gray-200 w-[150px] py-2 text-sm rounded-md text-gray-700 hover:bg-gray-300">
                                Tambah Dokumen
                                <input id="document" type="file" class="hidden" accept=".pdf,.doc,.xlsx,.txt" onchange="previewDocument(event)">
                            </label>
                        </div>
                    </div>

                    <!-- Tampilan Preview Dokumen -->
                    <div id="previewContainerDoc" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3" style="display: none;">
                        <div class="flex relative w-full">
                            <div class="flex items-center space-x-3">
                                <!-- Ikon dokumen -->
                                <img id="previewIconDoc" src="" class="w-8 object-cover rounded-md" alt="Preview">
                                <!-- Nama file -->
                                <span id="fileNameDoc"></span>
                            </div>

                            <!-- Tombol Ganti File -->
                            <label for="document" class="cursor-pointer absolute right-0 bg-gray-200 px-8 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-300">
                                Ganti File
                                <input id="document" type="file" class="hidden" accept=".pdf,.doc,.xlsx,.txt" onchange="previewDocument(event)">
                            </label>
                            <button type="button" class="bg-red-500 absolute -top-6 -left-6 z-20 px-[10px] py-1 text-sm rounded-full text-white hover:bg-red-600" onclick="cancelDocument()"><span class="font-bold">X</span></button>
                        </div>
                    </div>
                </div>

                {{-- Image --}}
                <div class="col-span-full mt-4">
                    {{-- Label --}}
                    <label for="image" class="block text-[16px] font-bold text-gray-900">
                        Image Barang
                    </label>

                    {{-- Tampilan Preview Tidak ada Image --}}
                    <div id="previewContainerNoImg" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3" style="display: none;">
                        <div class="flex relative w-full">
                            <div class="flex items-center space-x-3">
                                <!-- Ikon Image -->
                                <img id="previewIconNoImg" src="\svg\img-box-svgrepo-com.svg" class="w-8 object-cover rounded-md" alt="Preview">
                                <!-- Nama file -->
                                <span id="fileNameNoImg">Tidak ada gambar</span>
                            </div>
                            <label for="image" class="text-center absolute right-0 bg-gray-200 w-[150px] py-2 text-sm rounded-md text-gray-700 hover:bg-gray-300">
                                Tambah Gambar
                                <input id="image" type="file" class="hidden" accept=".jpg,.png" onchange="previewImage(event)">
                            </label>
                        </div>
                    </div>

                    <!-- Tampilan Preview Image -->
                    <div id="previewContainerImg" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3" style="display: none;">
                        <div class="flex relative w-full">
                            <div class="flex items-center space-x-3">
                                <!-- Ikon Image -->
                                <img id="previewIconImg" src="" class="w-10 h-10 object-cover rounded-md" alt="Preview">
                                <!-- Nama file -->
                                <span id="fileNameImg"></span>
                            </div>

                            <!-- Tombol Ganti File -->
                            <label for="image" class="cursor-pointer mt-1 absolute right-0 bg-gray-200 px-8 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-300">
                                Ganti File
                                <input id="image" type="file" class="hidden" accept=".jpg,.png" onchange="previewImage(event)">
                            </label>
                            <button type="button" class="bg-red-500 absolute -top-6 -left-6 z-20 px-[10px] py-1 text-sm rounded-full text-white hover:bg-red-600" onclick="cancelImage()"><span class="font-bold">X</span></button>
                        </div>
                    </div>
                </div>

                {{-- Button Submit --}}
                <button type="submit" class="flex justify-center w-full items-center my-6 rounded-full col-span-full bg-orange-500 px-3 py-1.5 text-md/6 font-bold text-white shadow-xs hover:bg-orange-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600 text-center" style="height: 50px;"> Edit Barang </button>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/edit.js') }}"></script>

@endsection


