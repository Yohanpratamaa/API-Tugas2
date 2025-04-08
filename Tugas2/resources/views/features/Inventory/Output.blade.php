@extends('layouts.app')

@section('features')

    <div class="w-full h-full flex flex-col justify-center items-center">
        <div class="flex w-full py-8">
            <a href="/dashboard" class="pl-10">
                <img src="{{ asset('img/IHome.png') }}" alt="">
            </a>
            <a href="#" class="pl-3 font-semibold text-gray-500">/ Inventory </a>
            <a href="/outBarang" class="pl-1 font-semibold {{ request()->is('outBarang') ? 'text-blue-500' : ''}}">/ Input Barang Keluar </a>
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

                        {{-- Drop Out Date --}}
                        <div class="drop_out mt-4">
                            <label for="drop_out_date" class="block text-[16px] font-bold text-gray-900"> Tanggal Keluar Barang <span class="text-red-500 ml-1">*</span> </label>
                            <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                                <input type="date" name="drop_out_date" id="drop_out_date" required class=" w-full block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none ml-6">
                            </div>
                        </div>

                        {{-- Quantity --}}
                         <div class="quantity mt-4">
                            <label for="quantity" class="block text-[16px] font-bold text-gray-900"> Jumlah Barang Keluar <span class="text-red-500 ml-1">*</span> </label>
                            <div class="flex mt-3 outline outline-1 outline-gray-300 rounded-xl h-[58px] focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-500">
                                <input type="number" name="quantity" id="quantity" required min="0" class=" w-full block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none ml-5" placeholder="Masukkan Jumlah Barang">
                            </div>
                        </div>

                    </div>

                    {{-- Button Submit --}}
                    <button type="submit" class="flex justify-center items-center my-7 rounded-full col-span-full bg-blue-500 px-3 py-1.5 text-md/6 font-bold text-white shadow-xs hover:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 text-center" style="height: 50px;"> Input Barang Keluar </button>

                </div>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/output.js') }}"></script>
@endsection
