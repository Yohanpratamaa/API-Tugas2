@extends('layouts.app')

@section('features')

    <div class="flex flex-col w-full h-full" x-data="inventoryDropdown()" x-init="init()">
        <div class="relative flex w-full outline outline-1 outline-gray-400">
          <img src="{{ asset('img/HeaderLihatBarang.png') }}" alt="" class="w-full h-full lg:h-[148px] object-cover">
          <p class="absolute px-[3rem] py-[1.5rem] bottom-0 text-white text-[48px] font-bold"> Daftar Barang </p>
        </div>

        <div class="mx-[36px]">
            <div class="overflow-y-auto">
                <table class="table-auto w-screen h-full text-left border-collapse border mb-6 border-gray-400 rounded-xl" id="inventoryTable">
                    <thead>
                        <tr class="bg-gray-100">
                          <th class="p-4 border border-gray-300 w-[200px]">Dibuat pada</th>
                          <th class="p-4 border border-gray-300 w-[170px]">Nasional-Serial-Number</th>
                          <th class="p-4 border border-gray-300 w-[170px]">Part-Number</th>
                          <th class="p-4 border border-gray-300 w-[170px]">Serial-Number</th>
                          <th class="p-4 border border-gray-300 w-[170px]">Nama Barang</th>
                          <th class="p-4 border border-gray-300 ">Kategori</th>
                          <th class="p-4 border border-gray-300 w-[150px]">Jumlah Barang</th>
                          <th class="p-4 border border-gray-300 w-[150px]">Batas Minimum</th>
                          <th class="p-4 border border-gray-300 ">Lokasi Gudang</th>
                          <th class="p-4 border border-gray-300 ">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tableshow.js') }}"></script>
    <script src="{{ asset('js/filter.js') }}"></script>
    <script src="{{ asset('js/import.js') }}"></script>

    <script>
        document.getElementById('exportButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Pilih Format Ekspor',
                text: 'Silakan pilih format untuk mengekspor data barang.',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'PDF',
                denyButtonText: 'Excel',
                confirmButtonColor: '#22385e',
                denyButtonColor: '#28a745',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika memilih PDF
                    window.location.href = "{{ route('inventories.exportdaftarbarang.pdf') }}";
                } else if (result.isDenied) {
                    // Jika memilih Excel (ganti rute sesuai kebutuhan)
                    window.location.href = "/editBarang";
                }
            });
        });
        </script>

@endsection
