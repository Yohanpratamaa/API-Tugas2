@extends('layouts.app')

@section('features')

    <div class="w-full h-full flex flex-col justify-center items-center">
        <div class="flex w-full py-8">
            <a href="/dashboard" class="pl-10">
                <img src="{{ asset('img/IHome.png') }}" alt="">
            </a>
            <a href="#" class="pl-3 font-semibold text-gray-500">/ Inventory </a>
            <a href="/outBarang" class="pl-1 font-semibold {{ request()->is('outBarang') ? 'text-orange-500' : ''}}">/ Input Barang Keluar </a>
        </div>
        <form action="" method="POST" id="outputForm" enctype="multipart/form-data">

            <div class="flex flex-col items-center justify-center w-[47.25rem] h-full pt-[20px]">
                <div class="w-full grid grid-cols-1 gap-4">

                    <div x-data="dropdownName()">

                        {{-- Nama Barang --}}
                        <div class="relative w-full inline-block text-left">
                            <div>
                                <input type="hidden" name="id" id="id" x-model="selectedInventoryId" required>
                                <label class="block text-[16px] font-bold text-gray-900">Nama Barang <span class="text-red-500 ml-1">*</span></label>
                                <button @click="open = !open" type="button"
                                    class="flex items-center mt-3 w-[47.25rem] h-[58px] rounded-xl bg-white px-6 py-2 text-gray-500 text-base ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                                    <span x-text="selectedOption"></span>
                                    <svg class="absolute right-0 mr-4 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div x-show="open" x-transition @click.away="open = false"~
                                class="absolute z-40 w-[47.25rem] rounded-xl bg-white ring-1 shadow-lg ring-black/5 max-h-46 overflow-y-auto hide-scrollbar">
                                <div class="py-1">
                                    <template x-for="(item, id) in items" :key="id">
                                        <a href="#" @click.prevent="selectItem(item.id, item.name, item.unit_price, item.part_number, item.document, item.image)"
                                            class="block px-4 py-3 text-sm text-gray-400 hover:bg-gray-100"
                                            x-text="item.name"></a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Nasional Serial Number --}}
                        <div class="nsNumber mt-4">
                            <label class="block text-[16px] font-bold text-gray-900">
                                Nasional-Serial-Number <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div id="nsNumberContainer">
                                <!-- Input default jika partNumbers masih kosong -->
                                <template x-if="nsNumbers.length === 0">
                                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                        <input type="text" name="ns_number[]" value=""
                                            class="w-full py~~-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" readonly
                                            placeholder="Nasional Serial Number Akan Terisi Otomatis">
                                    </div>
                                </template>

                                <!-- Looping untuk partNumbers dari API -->
                                <template x-for="(part, index) in nsNumbers" :key="index">
                                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                        <input type="text" name="ns_number[]" :value="part"
                                            class="w-full py-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" readonly>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Part Number --}}
                        <div class="partNumber mt-4">
                            <label class="block text-[16px] font-bold text-gray-900">
                                Part-Number <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div id="partNumberContainer">
                                <!-- Input default jika partNumbers masih kosong -->
                                <template x-if="partNumbers.length === 0">
                                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                        <input type="text" name="part_number[]" value=""
                                            class="w-full py-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" readonly
                                            placeholder="Part Number Akan Terisi Otomatis">
                                    </div>
                                </template>

                                <!-- Looping untuk partNumbers dari API -->
                                <template x-for="(part, index) in partNumbers" :key="index">
                                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                        <input type="text" name="part_number[]" :value="part"
                                            class="w-full py-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" readonly>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Part Number --}}
                        <div class="partNumber mt-4">
                            <label class="block text-[16px] font-bold text-gray-900">
                                Serial-Number Barang
                            </label>
                            <div id="serialNumberContainer">
                                <!-- Input default jika serialNumbers masih kosong -->
                                <template x-if="serialNumbers.length === 0">
                                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                        <input type="text" name="serial_number[]" value=""
                                            class="w-full py-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" readonly
                                            placeholder="Serial Number Akan Terisi Otomatis">
                                    </div>
                                </template>

                                <!-- Looping untuk partNumbers dari API -->
                                <template x-for="(part, index) in serialNumbers" :key="index">
                                    <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                        <input type="text" name="serial_number[]" :value="part"
                                            class="w-full py-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" readonly>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="location mt-4">
                            <label class="block text-[16px] font-bold text-gray-900">Lokasi Tujuan Barang <span class="text-red-500 ml-1">*</span></label>
                            <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px]">
                                <input type="text" name="destination" id="destination" required
                                    class="restrict-input w-full py-1.5 pr-3 pl-5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none"
                                    placeholder="Masukkan Lokasi Tujuan Barang">
                            </div>
                        </div>

                        {{-- Unit Price --}}
                        <div class="unit_price mt-4">
                            <label for="unit_price" class="block text-[16px] font-bold text-gray-900">
                                Harga Barang Satuan <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="flex items-center mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                <div class="text-base text-gray-500 select-none text-md ml-5 mr-1">Rp.</div>
                                <input type="text" name="unit_price" id="unit_price" x-model="unit_price" required class="w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none" placeholder="Harga akan Otomatis Terisi" readonly>
                            </div>
                        </div>

                        {{-- Drop Out Date --}}
                        <div class="drop_out mt-4">
                            <label for="drop_out_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Keluar Barang <span class="text-red-500 ml-1">*</span> </label>
                            <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                <input type="date" name="drop_out_date" id="drop_out_date" required class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                            </div>
                        </div>

                        {{-- Quantity --}}
                         <div class="quantity mt-4">
                            <label for="quantity" class="block text-[16px] font-bold text-gray-900"> Jumlah Barang Keluar <span class="text-red-500 ml-1">*</span> </label>
                            <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-orange-500">
                                <input type="number" name="quantity" id="quantity" required min="0" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none ml-5" placeholder="Masukkan Jumlah Barang">
                            </div>
                        </div>

                        {{-- Document Barang --}}
                        <div class="col-span-full mt-4" >
                            {{-- Label --}}
                            <label for="document" class="block text-[16px] font-bold text-gray-900">
                                Dokumen Barang
                            </label>

                            {{-- Tampilan Preview Tidak ada Dokumen --}}
                            <div id="previewContainerDoc" x-show="!document" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3">
                                <div class="flex relative">
                                    <div class="flex items-center space-x-3">
                                        <!-- Ikon dokumen -->
                                        <img id="previewIconDoc" :src="getDocumentIcon()" class="w-8 object-cover rounded-md" alt="Preview">
                                        <!-- Nama file -->
                                        <span id="fileNameDoc" x-text="getDocumentName(document)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tampilan Preview Dokumen -->
                            <div id="previewContainerDoc" x-show="document" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3">
                                <div class="flex relative">
                                    <div class="flex items-center space-x-3">
                                        {{-- <input type="hidden" id="document" name="document" :value="document"> --}}
                                        <!-- Ikon dokumen -->
                                        <img id="previewIconDoc" :src="getDocumentIcon(document)" class="w-8 object-cover rounded-md" alt="Preview">
                                        <!-- Nama file -->
                                        <span id="fileNameDoc" x-text="getDocumentName(document)"></span>
                                    </div>
                                    <!-- Tombol Download -->
                                    {{-- <a :href="document" target="_blank" class="absolute right-0 bg-gray-300 text-white px-4 py-1 rounded-md hover:bg-gray-400">
                                        Download
                                    </a> --}}
                                </div>
                            </div>
                        </div>

                        {{-- Gambar Barang --}}
                        <div class="col-span-full mt-4">

                            {{-- Label Gambar --}}
                            <label for="image" class="block text-[16px] font-bold text-gray-900">
                                Gambar Barang
                            </label>

                            {{-- Tampilan Preview Tidak ada Gambar --}}
                            <div x-show="!image" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3">
                                <div class="flex relative">
                                    <div class="flex items-center space-x-3">
                                        <!-- Ikon dokumen -->
                                        <img id="previewIconDoc" :src="getImageIcon()" class="w-8 object-cover rounded-md" alt="Preview">
                                        <!-- Nama file -->
                                        <span id="fileNameDoc" x-text="getImageName(image)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tampilan Preview Gambar -->
                            <div id="previewContainer" x-show="image" class="mt-2 items-center justify-between rounded-lg border border-gray-300 bg-gray-100 px-4 py-3">
                                <div class="flex relative">
                                    <div class="flex items-center space-x-3">
                                        {{-- <input type="hidden" id="image" name="image" :value="image"> --}}
                                        <img :src="image" class="w-10 h-10 object-cover rounded-md" alt="Preview">
                                        <span x-text="getImageName(image)"></span>
                                    </div>
                                    <!-- Tombol Download/View -->
                                    {{-- <a :href="image" target="_blank" class="absolute right-0 mt-1 bg-gray-300 text-white px-4 py-1 rounded-md hover:bg-gray-400">
                                        View
                                    </a> --}}
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Button Submit --}}
                    <button type="submit" class="flex justify-center items-center my-7 rounded-full col-span-full bg-orange-500 px-3 py-1.5 text-md/6 font-bold text-white shadow-xs hover:bg-orange-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600 text-center" style="height: 50px;"> Input Barang Keluar </button>

                </div>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/output.js') }}"></script>
@endsection
