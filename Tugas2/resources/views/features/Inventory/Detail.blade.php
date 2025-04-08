@extends('layouts.app')

@section('features')

    <div class="w-full h-full flex flex-col items-center justify-center">
        <div class="flex w-full py-8">
            <a href="/dashboard" class="pl-10">
                <img src="{{ asset('img/IHome.png') }}" alt="">
            </a>
            <a href="#" class="pl-3 font-semibold text-gray-500">/ Inventory </a>
            <a href="/inventories" class="pl-1 font-semibold text-gray-500">/ Lihat Daftar Barang </a>
            <a href="/detailBarang" class="pl-1 font-semibold {{ request()->is('detailBarang') ? 'text-orange-500' : ''}}">/ Detail Barang </a>
        </div>

        <div class="flex w-full justify-start pl-10 pb-10">
            <a href="/inventories">
                <button class="flex items-center justify-center text-gray-500 border border-gray-300 w-full h-[42px] hover:bg-gray-100 font-semibold p-4 rounded-lg">
                    <img src="{{ asset('img/IBack.png') }}" alt="" class="pr-6">
                    Kembali
                </button>
            </a>
            <div class="px-4">
            <button onclick="downloadPDF('{{ $id ?? '' }}')" class="flex items-center text-white justify-center p-4 w-[184px] h-[42px] bg-[#22385e] outline outline-1 font-semibold hover:bg-blue-950 hover:text-white rounded-lg transition-all duration-300">
                <img src="{{ asset('img/IEksporPDF.png') }}" alt="" class="mr-[10px]">
                Download PDF
            </button>
            </div>
        </div>

        <div class="flex flex-col w-[1155px] h-full border border-gray-200 rounded-2xl mb-[80px]">
            <div class="flex">
                <div>
                    <img id="itemImage" alt="" class="w-[555px] h-[370px] ml-5 mt-5 rounded-lg">
                    <div class="hidden" id="noImageText" >
                        <h1 class="flex justify-center text-xl text-gray-500 ml-5 mt-5 w-[555px] rounded-lg h-[370px] items-center bg-gray-200"> Tidak Ada Gambar</h1>
                    </div>
                </div>
                <div class="flex flex-col w-[555px] h-[390px] justify-center ml-[100px] ">
                    <h1 class="font-bold text-2xl"> <span id="itemName"></span></h1>
                    <ul>
                        <li class="mt-3"> Kategori : <span id="itemCategory"></span> </li>
                        <li class="mt-1"> Satuan : <span id="itemUnit"></span></li>
                        <li class="mt-1"> Jumlah : <span id="itemQuantity"></span> </li>
                        <li class="mt-1"> Batas Minimum : <span id="itemMinimum"></span> </li>
                        <li class="mt-1"> Date Of Manufacture : <span id="itemDOM"></span> </li>
                        <li class="mt-1"> Date Of Expired : <span id="itemDOE"></span> </li>
                        <li class="mt-1 flex"> Status Stok :
                            <div id="statusContainer" class="flex">
                                <div id="statusCircle" class="w-[20px] h-[20px] mx-1 rounded-full"></div>
                                <span id="itemStockStatus"></span>
                            </div>
                        </li>
                    </ul>
                    <div class="flex mt-6 gap-x-5">
                        <div id="buttonEdit"></div>
                        <div id="buttonDelete"></div>
                    </div>
                </div>

            </div>

            <div class="mt-6 mx-5">
                <h1 class="font-bold text-lg my-2">
                    Riwayat barang masuk/keluar
                </h1>
                <table class="table-auto w-full text-left border-collapse border border-gray-400 rounded-xl" id="inventoryTable">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border text-medium border-gray-300 w-[200px]">Tanggal</th>
                            <th class="p-4 border text-medium border-gray-300 w-[200px]">Nama Barang</th>
                            <th class="p-4 border text-medium border-gray-300 w-[200px]">Serial Number</th>
                            <th class="p-4 border border-gray-300 w-[180px]">Kategori</th>
                            <th class="p-4 border border-gray-300 w-[100px]">Satuan</th>
                            <th class="p-4 border border-gray-300 w-[180px]">Jumlah Barang</th>
                            <th class="p-4 border border-gray-300 w-[180px]">Batas Minimum</th>
                            <th class="p-4 border border-gray-300 w-[150px]">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <div class="my-6 mx-5">
                <h1 class="font-bold text-lg my-2">
                    Dokumen Pendukung Barang
                </h1>
                <table class="table-auto w-full text-left border-collapse border border-gray-400 rounded-xl" id="documentTable">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border text-medium border-gray-300 w-[200px]">Nama Dokumen</th>
                            <th class="p-4 border border-gray-300 w-[200px]">Tanggal Upload</th>
                            <th class="p-4 border border-gray-300 w-[180px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="documentTableBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/detail.js') }}"></script>

@endsection
