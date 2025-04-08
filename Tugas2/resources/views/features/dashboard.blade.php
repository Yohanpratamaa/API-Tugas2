@extends('layouts.app')

@section('features')

    <div class="relative px-[70px] mb-10">

        {{-- Header --}}
        <div class="flex justify-between pt-[45px] pb-[30px]">

            <h1 class="text-3xl font-bold"> Selamat Datang kembali ! </h1>

        </div>
        {{-- Header End --}}

        {{-- Transaction In and Out --}}
        <div class="flex justify-start gap-12">
            <div class="relative w-[532px] h-[9rem] rounded-2xl bg-[#F9771F]">
                <div class="flex min-h-full items-center">
                    <div class="flex flex-col text-white">
                        <div class="absolute top-0 left-0 py-[1.25rem] px-[1rem] flex items-center">
                            <div>
                                <img src="{{ asset('img/Icon.png') }}" class="px-[1rem]" alt="">
                            </div>
                            <h1> TOTAL BARANG MASUK </h1>
                        </div>
                        <div class="flex px-[1.5rem] pt-[2rem]">
                            <h1 class="text-[50px] font-semibold px-[5px]" id="totalItemsIn"></h1>
                            <h1 class="pt-[40px]"> Transactions </h1>
                        </div>
                    </div>
                    <div class="flex justify-end w-full h-full px-[2rem]">
                        <img src="{{ asset('img/IconIn.png') }}" alt="" class="opacity-50">
                    </div>
                </div>
            </div>
            <div class="relative w-[532px] h-[9rem] rounded-2xl bg-[#F9771F]">
                <div class="flex min-h-full items-center">
                    <div class="flex flex-col text-white">
                        <div class="absolute top-0 left-0 py-[1.25rem] px-[1rem] flex items-center">
                            <div>
                                <img src="{{ asset('img/Icon.png') }}" class="px-[1rem]" alt="">
                            </div>
                            <h1> TOTAL BARANG KELUAR </h1>
                        </div>
                        <div class="flex px-[1.5rem] pt-[2rem]">
                            <h1 class="text-[50px] font-semibold px-[5px]" id="totalItemsOut"></h1>
                            <h1 class="pt-[40px]"> Transactions </h1>
                        </div>
                    </div>
                    <div class="flex justify-end w-full h-full px-[2rem]">
                        <img src="{{ asset('img/IconOut.png') }}" alt="" class="opacity-50">
                    </div>
                </div>
            </div>
        </div>
        {{-- Transaction In and Out End --}}

        {{-- Table --}}
        <div class="pt-[30px]">
            <div class="overflow-y-auto"> <!-- Div pembungkus untuk scroll horizontal -->
                <table id="dashboardTable" class="table-auto w-screen h-full text-left border-collapse border border-gray-400 rounded-xl">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border border-gray-300 w-[200px]">Tanggal & Waktu</th>
                            <th class="p-4 border border-gray-300 w-[150px]">Nasional-Serial-Number</th>
                            <th class="p-4 border border-gray-300 w-[150px]">Part-Number</th>
                            <th class="p-4 border border-gray-300 w-[150px]">Serial-Number</th>
                            <th class="p-4 border border-gray-300 w-[180px]">Nama Barang</th>
                            <th class="p-4 border border-gray-300 w-[130px]">Kategori</th>
                            <th class="p-4 border border-gray-300 w-[80px]">Satuan</th>
                            <th class="p-4 border border-gray-300 w-[160px]">Jumlah Barang</th>
                            <th class="p-4 border border-gray-300 w-[150px]">Batas Minimum</th>
                            <th class="p-4 border border-gray-300 w-[180px]">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Table End --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/grafik.js') }}"></script>
    <script src="{{ asset('js/tableDashboard.js') }}"></script>
    <script src="{{ asset('js/count.js') }}"></script>

@endsection
