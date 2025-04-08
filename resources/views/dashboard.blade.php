@extends('layouts.app')

@section('content')
 <style>
        #scannerContainer {
            position: relative;
            width: 80vw;
            /* Lebar maksimal 80% dari viewport width */
            max-width: 600px;
            /* Maksimal 600px agar tidak terlalu lebar di desktop */
            aspect-ratio: 16 / 9;
            /* Proporsi tetap */
            overflow: hidden;
            margin: auto;
            /* Supaya tetap berada di tengah */
        }

        #preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #scannerLine {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: red;
            animation: scanAnimation 2s infinite linear;
        }

        #scannerDot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 12px;
            height: 12px;
            background: red;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px red;
        }

        @keyframes scanAnimation {
            0% {
                top: 0;
            }

            50% {
                top: 100%;
            }

            100% {
                top: 0;
            }
        }
    </style>
    <div >
        <div class="min-w-full">
            <!-- Statistik Utama -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Transaksi Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Transaksi Hari Ini</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $transaksiHariIni }}</div>
                        <div class="text-xl lg:text-base text-gray-500">Pendapatan: Rp
                            {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Total Transaksi Bulan Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Transaksi Bulan Ini</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $transaksBulanIni }}</div>
                        <div class="text-xl lg:text-base text-gray-500">Pendapatan: Rp
                            {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Total Pelanggan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Pelanggan</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $totalPelanggan }}</div>
                        <div class="text-sm text-gray-500">
                            <a href="{{ route('pelanggan.index') }}" class="text-indigo-600 hover:text-indigo-900">Lihat
                                Semua</a>
                        </div>
                    </div>
                </div>

                <!-- Total Layanan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Layanan</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $totalLayanan }}</div>
                        <div class="text-sm text-gray-500">
                            <a href="{{ route('layanan.index') }}" class="text-indigo-600 hover:text-indigo-900">Lihat
                                Semua</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Transaksi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900">Status Transaksi:</h2>
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                                Diproses: {{ $totalDiproses }}
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                                Selesai: {{ $totalSelesai }}
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-600">
                                Diambil: {{ $totalDiambil }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Cepat -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Menu Cepat</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <a id="scanButton"
                            class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-white hover:bg-yellow-700">
                            <img src="{{ asset('barcode-scan.png') }}" alt="Scan"
                                class="h-6 w-8 mr-2 lg:h-6 lg:w-6 lg:mr-2">
                            Scan Barcode
                        </a>
                        <a href="{{ route('transaksi.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700">
                            + Transaksi Baru
                        </a>
                        <a href="{{ route('pelanggan.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700">
                            + Pelanggan Baru
                        </a>
                        <a href="{{ route('layanan.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                            + Layanan Baru
                        </a>

                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaksi Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Pelanggan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transaksiTerbaru as $transaksi)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <a href="{{ route('transaksi.show', ['transaksi' => $transaksi->kode_transaksi]) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $transaksi->kode_transaksi }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaksi->pelanggan->nama }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('transaksi.index') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-900">Lihat Semua Transaksi →</a>
                        </div>
                    </div>
                </div>

                <!-- Layanan Terpopuler -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Layanan Terpopuler</h2>
                        <div class="space-y-4">
                            @foreach ($layananTerpopuler as $layanan)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $layanan->layanan->nama_layanan }}</div>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $layanan->total }} kali transaksi</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="scannerModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <div id="scannerContainer">
                    <video id="preview" class="w-full h-full"></video>
                    <div id="scannerDot"></div>
                    <div id="scannerLine"></div>
                </div>
                <button id="closeScanner" class="mt-2 p-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Tutup</button>
                <button id="toggleFlash" class="mt-2 p-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Nyalakan
                    Flash</button>

            </div>
        </div>
        <audio id="barcodeSound" src="{{ asset('barcode_sound.mp3') }}" preload="auto"></audio>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
    let flashEnabled = false;
    let track; // Untuk menyimpan track video
    let scanning = false; // Tambahkan flag untuk mencegah spam
    let lastScannedCode = ""; // Simpan kode terakhir yang dideteksi

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("toggleFlash").addEventListener("click", function() {
            if (track && track.getCapabilities().torch) {
                flashEnabled = !flashEnabled; // Toggle status flash
                track.applyConstraints({
                    advanced: [{
                        torch: flashEnabled
                    }]
                });
                this.textContent = flashEnabled ? "Matikan Flash" : "Nyalakan Flash";
            } else {
                Swal.fire({
                    title: "Flash tidak didukung",
                    text: "Perangkat ini tidak mendukung penggunaan flash.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });

        document.getElementById("scanButton").addEventListener("click", function() {
            let scannerModal = document.getElementById("scannerModal");
            scannerModal.classList.remove("hidden");

            // Cek apakah Quagga sudah berjalan sebelumnya
            if (Quagga.initialized) {
                Quagga.stop();
            }

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment",
                        width: {
                            ideal: 1920
                        },
                        height: {
                            ideal: 1080
                        },
                        advanced: [{
                            torch: flashEnabled
                        }] // Tambahkan torch jika didukung
                    }
                })
                .then(stream => {
                    let videoElement = document.getElementById("preview");
                    videoElement.srcObject = stream;
                    videoElement.play();

                    // Simpan track video untuk kontrol flash
                    track = stream.getVideoTracks()[0];

                    videoElement.addEventListener("loadedmetadata", () => {
                        startQuagga();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "Gagal mengakses kamera",
                        text: "Tidak bisa mengakses kamera. Pastikan izin kamera telah diberikan.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                });
        });

        // Fungsi untuk memulai Quagga setelah kamera aktif
        function startQuagga() {
            document.getElementById("scannerLine").style.display = "block";
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector("#preview"), // Pastikan elemen video sudah terlihat
                    constraints: {
                        width: {
                            ideal: 1920
                        },
                        height: {
                            ideal: 1080
                        },
                        facingMode: "environment"
                    }
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
                }
            }, function(err) {
                if (err) {
                    Swal.fire({
                        title: "Gagal mengaktifkan kamera",
                        text: "Pastikan izin sudah diberikan.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                Quagga.start();
                Quagga.initialized = true;
            });

            Quagga.onDetected(function(result) {
                let kodeTransaksi = result.codeResult.code; // Ambil hasil scan

                console.log("QR Code Terdeteksi:", kodeTransaksi); // Log hasil scan

                if (!scanning && kodeTransaksi !== lastScannedCode) {
                    scanning = true;
                    lastScannedCode = kodeTransaksi;

                    // Putar audio saat barcode berhasil ditemukan
                    document.getElementById("barcodeSound").play();

                    // Cek kode transaksi di database dan ambil ID-nya
                    fetch(`/get-transaksi-id/${kodeTransaksi}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log("Respon dari Server:", data); // Log hasil fetch dari server

                            if (data.exists) {
                                console.log("Transaksi ditemukan! ID:", data.id);

                                // Redirect ke halaman transaksi berdasarkan ID
                                window.location.href = `/transaksi/${data.id}`;
                            } else {
                                console.log("Transaksi tidak ditemukan untuk kode:", kodeTransaksi);

                                Swal.fire({
                                    title: "Transaksi tidak ditemukan!",
                                    text: "Kode transaksi ini tidak ada di database.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });

                                setTimeout(() => {
                                    scanning = false;
                                    lastScannedCode = "";
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            console.error("Error saat fetch data:",
                            error); // Log error jika fetch gagal
                            scanning = false;
                            lastScannedCode = "";
                        });
                }
            });


        }

        // Fungsi menutup scanner
        function closeScanner() {
            let scannerModal = document.getElementById("scannerModal");
            document.getElementById("scannerLine").style.display = "none";

            scannerModal.classList.add("hidden");

            // Stop Quagga dan kamera
            Quagga.stop();
            let videoElement = document.getElementById("preview");
            if (videoElement.srcObject) {
                let tracks = videoElement.srcObject.getTracks();
                tracks.forEach(track => track.stop());
            }
        }

        // Tombol untuk menutup scanner
        document.getElementById("closeScanner").addEventListener("click", closeScanner);
    });
</script>
