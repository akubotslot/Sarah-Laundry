@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg lg:text-2xl font-bold">Detail Transaksi</h2>
                <div class="flex space-x-1 lg:space-x-2">
                    <a href="{{ route('transaksi.index') }}"
                        class="bg-gray-500 text-white px-2 py-2 rounded-md hover:bg-gray-600
                text-sm lg:text-base lg:px-4 lg:py-2">
                        Kembali
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="bg-indigo-600 text-white px-2 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 inline-flex items-center
                    text-sm lg:text-base lg:px-4 lg:py-2">
                            <span>Cetak</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <div class="py-1">
                                <a href="{{ route('transaksi.print', $transaksi) }}" target="_blank"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Print Thermal
                                </a>
                                <a href="{{ route('transaksi.print.a6', $transaksi) }}" target="_blank"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Print (A6)
                                </a>
                                <a href="{{ route('transaksi.pdf', $transaksi) }}" target="_blank"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Download PDF (A6)
                                </a>
                                <a href="{{ route('isirouteuntukdownloadbarcode', $transaksi->kode_transaksi) }}"
                                    target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Download Barcode
                                </a>
                                <a href="{{ route('transaksi.wa', $transaksi->kode_transaksi) }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Pesan Whatsapp
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('transaksi.edit', $transaksi) }}"
                        class="bg-yellow-500 text-white px-2 py-2 rounded-md hover:bg-yellow-600
                text-sm lg:text-base lg:px-4 lg:py-2">
                        Edit
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Transaksi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Informasi Transaksi</h3>
                    <div class="space-y-3">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Kode</span>
                            <span class="text-gray-600 px-2">:</span>
                            <span class="font-medium">{{ $transaksi->kode_transaksi }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Tanggal Masuk</span>
                            <span class="text-gray-600 px-2">:</span>
                            <span class="font-medium">{{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Status</span>
                            <span class="text-gray-600 px-2">:</span>
                            @if ($transaksi->status === 'Selesai')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @elseif($transaksi->status === 'Proses')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Proses
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Diambil
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Pelanggan -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-3">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Nama</span>
                            <span class="text-gray-600 px-2">:</span>
                            <span class="font-medium">{{ $transaksi->pelanggan->nama }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">No. WhatsApp</span>
                            <span class="text-gray-600 px-2">:</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $transaksi->pelanggan->nomor_whatsapp) }}"
                                target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ $transaksi->pelanggan->nomor_whatsapp }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Layanan -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Detail Layanan</h3>
                <div class="bg-gray-50 rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Layanan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ukuran
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaksi->details as $detail)
                                <tr>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $detail->layanan->nama_layanan }}
                                        <br>
                                        <span class="text-gray-500">
                                            Rp
                                            {{ number_format($detail->layanan->harga_per_satuan, 0, ',', '.') }}/{{ $detail->layanan->satuan === 'Meter' ? 'Meter' : $detail->layanan->satuan }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        {{ rtrim(rtrim(number_format($detail->jumlah_satuan, 2), '0'), '.') }}
                                        {{ $detail->layanan->satuan === 'Meter' ? 'Pcs' : $detail->layanan->satuan }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        @if ($detail->layanan->satuan === 'Meter' && $detail->panjang_cm && $detail->lebar_cm)
                                            {{ $detail->panjang_cm }}cm x {{ $detail->lebar_cm }}cm
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        Rp {{ number_format($detail->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Total Keseluruhan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
