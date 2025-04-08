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
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="name"
                            id="name" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Nama Barang">
                    </div>
                </div>

                {{-- Nasional Serial Number --}}
                <div class="ns_number">
                    <label for="ns_number" class="block text-[16px] font-bold text-gray-900">
                        Nasional Serial Number Barang <span class="text-red-500 ml-1">*</span>
                    </label>

                    <div id="NSNumberContainer">
                        <div class="relative flex mt-3 outline outline-1 w-[47.25rem] items-center outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                            <input
                                type="text"
                                name="ns_number[]"
                                class="part-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
                                placeholder="Masukkan Nasional Serial Number">
                            <div class="absolute right-0 mr-4">
                                <img id="addNasionalSerialNumber" src="{{ asset('svg/plus-large-svgrepo-com.svg') }}" alt="Tambah" class="w-5 h-5 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Part Number --}}
                <div class="part_number">
                    <label for="part_number" class="block text-[16px] font-bold text-gray-900">
                        Part-Number Barang <span class="text-red-500 ml-1">*</span>
                    </label>

                    <div id="partNumberContainer">
                        <div class="relative flex mt-3 outline outline-1 w-[47.25rem] items-center outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                            <input
                                type="text"
                                name="part_number[]"
                                class="part-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
                                placeholder="Masukkan Part Number">
                            <div class="absolute right-0 mr-4">
                                <img id="addPartNumber" src="{{ asset('svg/plus-large-svgrepo-com.svg') }}" alt="Tambah" class="w-5 h-5 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Serial Number --}}
                <div class="serial_number">
                    <label for="serial_number" class="block text-[16px] font-bold text-gray-900">
                        Serial-Number Barang
                    </label>

                    <div id="serialNumberContainer">
                        <div class="relative flex mt-3 outline outline-1 w-[47.25rem] items-center outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                            <input
                                type="text"
                                name="serial_number[]"
                                class="serial-number-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5"
                                placeholder="Masukkan Serial-Number">
                            <div class="absolute right-0 mr-4">
                                <img id="addSerialNumber" src="{{ asset('svg/plus-large-svgrepo-com.svg') }}" alt="Tambah" class="w-5 h-5 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location --}}
                <div class="location">
                    <label for="location" class="block text-[16px] font-bold text-gray-900"> Lokasi Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
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
                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <div class="text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                        <input type="text" name="unit_price" id="unit_price" required class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Masukkan Harga Satuan Barang">
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="quantity">
                    <label for="quantity" class="block text-[16px] font-bold text-gray-900">
                        Jumlah Barang <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="number" name="quantity" id="quantity" required min="0" class=".restrict-input_number w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Jumlah Barang">
                    </div>
                </div>

                {{-- Unit --}}
                <div class="unit">
                    <label for="unit" class="block text-[16px] font-bold text-gray-900"> Satuan Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
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
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <div class="flex items-center text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                        <input type="text" name="total_price" id="total_price" class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Total Harga" readonly>
                    </div>
                </div>

                {{-- Entry Date --}}
                <div class="entry_date">
                    <label for="entry_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Barang Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="entry_date" id="entry_date" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Document Date --}}
                <div class="document_date">
                    <label for="document_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Surat Masuk <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="document_date" id="document_date" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" required>
                    </div>
                </div>

                {{-- Source --}}
                <div class="source">
                    <label for="source" class="block text-[16px] font-bold text-gray-900"> Sumber Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
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
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="date_of_manufacture" id="date_of_manufacture" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                    </div>
                </div>

                {{-- DOE --}}
                <div class="date_of_expired">
                    <label for="date_of_expired" class="block text-[16px] font-bold text-gray-900">Date of Expired (DOE) </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="date" name="date_of_expired" id="date_of_expired" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                    </div>
                </div>

                {{-- Minimum --}}
                <div class="minimum">
                    <label for="minimum" class="block text-[16px] font-bold text-gray-900"> Batas Minimum Barang <span class="text-red-500 ml-1">*</span> </label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input type="number" name="minimum" id="minimum" required min="0" class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6" placeholder="Masukkan Batas Minimum Barang">
                    </div>
                </div>

                {{-- Condition --}}
                <div class="condition">
                    <label for="condition" class="block text-[16px] font-bold text-gray-900"> Kondisi Barang <span class="text-red-500 ml-1">*</span></label>
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
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
                    <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                        <input
                            type="text"
                            name="category"
                            id="category" required
                            class="restrict-input w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-5" placeholder="Masukkan Kategori Barang">
                    </div>
                </div>

                {{-- Document Barang --}}
                <div class="col-span-full relative">
                    <label for="document" class="block text-[16px] font-bold text-gray-900">
                        Upload Dokumen Barang
                    </label>

                    <!-- Tampilan Awal (Drag & Drop) -->
                    <div id="uploadBoxDoc" class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                        <div class="text-center">
<svg class="mx-auto size-12 text-gray-300" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>doc-document</title> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="icon" fill="#d1d1d1" transform="translate(71.778133, 42.666667)"> <path d="M28.864,383.082667 L34.368,383.082667 C47.5093333,383.082667 57.2373333,380.928 63.5733333,376.661333 C69.12,372.970667 73.1306667,367.296 75.584,359.573333 C77.3973333,353.856 78.3146667,347.776 78.3146667,341.312 C78.3146667,334.378667 77.2693333,327.957333 75.1573333,322.048 C73.024,316.138667 70.144,311.530667 66.4746667,308.202667 C62.976,305.088 58.9866667,302.954667 54.528,301.845333 C50.0693333,300.736 43.3493333,300.16 34.368,300.16 L28.864,300.16 L28.864,383.082667 Z M-1.42108547e-14,405.696 L-1.42108547e-14,277.568 L35.456,277.568 C50.5386667,277.568 62.5706667,279.018667 71.616,281.92 C83.4346667,285.717333 92.4586667,292.757333 98.7306667,303.018667 C105.002667,313.301333 108.16,326.186667 108.16,341.674667 C108.16,357.589333 105.002667,370.581333 98.7306667,380.650667 C90.9013333,393.344 78.8053333,401.088 62.4,403.797333 C54.8693333,405.056 45.2266667,405.696 33.472,405.696 L-1.42108547e-14,405.696 Z M186.389333,297.915733 C175.957333,297.915733 167.914667,302.2464 162.24,310.929067 C157.12,318.779733 154.56,328.827733 154.56,341.115733 C154.56,355.345067 157.589333,366.395733 163.690667,374.225067 C169.408,381.649067 177.024,385.339733 186.474667,385.339733 C196.842667,385.339733 204.928,380.987733 210.709333,372.219733 C215.829333,364.5184 218.389333,354.3424 218.389333,341.6704 C218.389333,327.739733 215.338667,316.881067 209.258667,309.0304 C203.541333,301.627733 195.904,297.915733 186.389333,297.915733 M186.474667,275.3024 C206.613333,275.3024 222.037333,281.681067 232.768,294.481067 C243.072,306.705067 248.213333,322.427733 248.213333,341.6704 C248.213333,362.705067 242.133333,379.4304 229.973333,391.8464 C219.477333,402.577067 204.970667,407.931733 186.474667,407.931733 C166.357333,407.931733 150.912,401.553067 140.181333,388.7744 C129.877333,376.5504 124.714667,360.571733 124.714667,340.859733 C124.714667,320.251733 130.816,303.7184 142.997333,291.3024 C153.536,280.635733 168.042667,275.3024 186.474667,275.3024 M362.7456,375.477333 L371.172267,398.538667 C363.556267,402.229333 356.942933,404.704 351.268267,406.005333 C345.5936,407.285333 338.596267,407.925333 330.2976,407.925333 C317.7536,407.925333 307.3856,406.133333 299.1936,402.528 C287.716267,397.450667 279.076267,389.472 273.252267,378.570667 C268.004267,368.8 265.380267,356.853333 265.380267,342.752 C265.380267,318.965333 272.6336,300.917333 287.1616,288.693333 C297.700267,279.776 311.6096,275.317333 328.846933,275.317333 C336.206933,275.317333 342.884267,275.978667 348.9216,277.322667 C354.9376,278.688 361.7856,281.077333 369.422933,284.512 L359.758933,306.229333 C349.582933,300.682667 339.812267,297.909333 330.468267,297.909333 C319.204267,297.909333 310.5856,301.770667 304.612267,309.472 C298.340267,317.557333 295.204267,328.224 295.204267,341.493333 C295.204267,355.232 298.4896,365.984 305.060267,373.728 C311.630933,381.472 320.6976,385.333333 332.2816,385.333333 C337.508267,385.333333 342.350933,384.650667 346.7456,383.221333 C351.140267,381.813333 356.4736,379.232 362.7456,375.477333 M248.221867,7.10542736e-15 L13.5552,7.10542736e-15 L13.5552,234.666667 L56.2218667,234.666667 L56.2218667,192 L56.2218667,169.6 L56.2218667,42.6666667 L230.5152,42.6666667 L312.221867,124.373333 L312.221867,169.6 L312.221867,192 L312.221867,234.666667 L354.888533,234.666667 L354.888533,106.666667 L248.221867,7.10542736e-15 L248.221867,7.10542736e-15 Z" id="DOC"> </path> </g> </g> </g></svg>
                            <div class="mt-4 flex justify-center items-center text-sm text-gray-600">
                                <label for="document" class="relative cursor-pointer rounded-md bg-white font-semibold text-orange-600 hover:text-orange-500">
                                    <span>Upload Document</span>
                                    <input id="document" type="file" class="sr-only" accept=".pdf,.doc,.xlsx,.docx" onchange="previewDocument(event)">
                                </label>
                                <span class="ml-1 text-gray-400">or drag and drop</span>
                            </div>
                            <p class="text-xs text-gray-600">PDF, DOC, DOCX, XSLX max 10MB</p>
                        </div>
                    </div>

                    <!-- Tampilan Setelah Dokumen Dipilih -->

                    <div id="previewContainerDoc" class="relative hidden">
                        <button type="button" class="bg-red-500 absolute -top-2 -left-1 z-20 px-[10px] py-1 text-sm rounded-full text-white hover:bg-red-600" onclick="cancelDocument()"><span class="font-bold">X</span></button>
                        <div class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3">
                            <div class="flex relative">
                                <div class="flex items-center space-x-3">
                                    <!-- Ikon dokumen -->
                                    <img id="previewIconDoc" src="" class="w-8 object-cover rounded-md" alt="Preview">

                                    <!-- Nama file -->
                                    <span id="fileNameDoc" class="text-gray-700 w-[500px] text-wrap"></span>
                                </div>

                                <!-- Tombol Ganti File -->
                                <label for="document" class="cursor-pointer absolute right-0 bg-gray-200 px-8 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-300">
                                    Ganti File
                                    <input id="document" type="file" class="hidden" accept=".pdf,.doc,.xlsx,.txt" onchange="previewDocument(event)">
                                </label>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gambar Barang --}}
                <div class="col-span-full">
                    <label for="image" class="block text-[16px] font-bold text-gray-900">
                        Upload Gambar Barang
                    </label>

                    <!-- Tampilan Awal (Drag & Drop) -->
                    <div id="uploadBox" class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                        <div class="text-center">
                            <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
                            </svg>
                            <div class="flex items-center justify-center mt-4 text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer rounded-md bg-white font-semibold text-orange-600 focus-within:ring-2 focus-within:ring-orange-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-orange-500">
                                    <span>Upload Image</span>
                                    <input id="image" type="file" class="sr-only" accept=".jpg,.png" onchange="previewFile(event)">
                                </label>
                                <span class="ml-1 text-gray-400">or drag and drop</span>
                            </div>
                            <p class="text-xs text-gray-600">PNG, JPG, JPEG max 10MB</p>
                        </div>
                    </div>

                    <!-- Tampilan Setelah Gambar Dipilih -->
                    <div id="previewContainer" class="mt-2 relative items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 hidden">
                        <button type="button" class="bg-red-500 absolute -top-2 -left-1 z-20 px-[10px] py-1 text-sm rounded-full text-white hover:bg-red-600" onclick="cancelImage()"><span class="font-bold">X</span></button>
                        <div class="flex relative">
                            <div class="flex items-center space-x-3">
                                <img id="previewIcon" src="" class="w-10 h-10 object-cover rounded-md" alt="Preview">
                                <span id="fileName" class="text-gray-700 w-[500px] text-wrap"></span>
                            </div>

                            <label for="image" class="cursor-pointer mt-1 absolute right-0 bg-gray-200 px-8 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-300">
                                Ganti File
                                <input id="image" type="file" class="hidden" accept=".jpg,.png" onchange="previewFile(event)">
                            </label>

                        </div>
                    </div>
                </div>

                {{-- Button Submit --}}
                <button type="submit" class="flex justify-center w-full items-center my-6 rounded-full col-span-full bg-orange-500 px-3 py-1.5 text-md/6 font-bold text-white shadow-xs hover:bg-orange-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600 text-center" style="height: 50px;"> Input Barang Masuk </button>

            </form>

        </div>
    </div>

    <script src="{{ asset('js/input.js') }}"></script>
    <script src="{{ asset('js/previewImg.js') }}"></script>
    <script src="{{ asset('js/previewDoc.js') }}"></script>

@endsection


