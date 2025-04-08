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
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg lg:text-2xl font-bold">Daftar Transaksi</h2>
                <a href="{{ route('transaksi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700
                    text-base lg:text-xs">
                    Tambah Transaksi
                </a>
            </div>

            <div class="mb-4">
                <div class="relative flex items-center">
                    <input type="text" id="searchInput" placeholder="Cari kode transaksi atau nama pelanggan..."
                        class="w-full mr-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        data-placeholder-mobile="Cari kode transaksi..."
                        data-placeholder-desktop="Cari kode transaksi atau nama pelanggan...">
            
                    <div class="absolute right-16 top-2.5 text-gray-400"> <!-- Ubah right dari 3 ke 16 -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="button" id="scanButton"
                        class="p-2 bg-gray-300 rounded-lg hover:bg-gray-400 flex items-center">
                        <img src="{{ asset('barcode-scan.png') }}" alt="Scan" class="h-6 w-8 lg:h-6 lg:w-6">
                    </button>
                </div>
            </div>

            <form action="{{ route('transaksi.index') }}" method="GET" class="mb-4" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ request('tanggal_selesai') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <div id="loading" class="hidden">
                    <div class="flex justify-center items-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                        <span class="ml-2 text-gray-500">Mencari...</span>
                    </div>
                </div>

                <div id="tableContainer">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Layanan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="transactionTableBody">
                            @include('transaksi.partials.transaction-rows')
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4" id="pagination">
                {{ $transaksis->links() }}
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        let flashEnabled = false;
        let track; // Untuk menyimpan track video
        let scanning = false; // Tambahkan flag untuk mencegah spam
        let lastScannedCode = ""; // Simpan kode terakhir yang dideteksi

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

        function hapusPenggunaan(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

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
                            console.error("Error saat fetch data:", error); // Log error jika fetch gagal
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

        const searchInput = document.getElementById('searchInput');
        const placeholderMobile = searchInput.getAttribute('data-placeholder-mobile');
        const placeholderDesktop = searchInput.getAttribute('data-placeholder-desktop');

        function updatePlaceholder() {
            const screenWidth = window.innerWidth;
            if (screenWidth < 768) {
                searchInput.placeholder = placeholderMobile;
            } else {
                searchInput.placeholder = placeholderDesktop;
            }
        }

        updatePlaceholder();
        window.addEventListener('resize', updatePlaceholder);

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Live Search Implementation
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterForm = document.getElementById('filterForm');
            const loading = document.getElementById('loading');
            const tableContainer = document.getElementById('tableContainer');
            const transactionTableBody = document.getElementById('transactionTableBody');
            const pagination = document.getElementById('pagination');

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            function showLoading() {
                loading.classList.remove('hidden');
                tableContainer.classList.add('opacity-50');
            }

            function hideLoading() {
                loading.classList.add('hidden');
                tableContainer.classList.remove('opacity-50');
            }

            function updateQueryString(params) {
                const newUrl = `${window.location.pathname}?${params.toString()}`;
                window.history.pushState({
                    path: newUrl
                }, '', newUrl);
            }

            const performSearch = debounce(() => {
                const searchTerm = searchInput.value;
                const formData = new FormData(filterForm);
                const params = new URLSearchParams();

                // Tambahkan parameter pencarian
                if (searchTerm) {
                    params.append('search', searchTerm);
                }

                // Tambahkan parameter filter
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }

                showLoading();

                fetch(`${window.location.pathname}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        transactionTableBody.innerHTML = html;
                        hideLoading();
                        updateQueryString(params);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mencari data'
                        });
                    });
            }, 300);

            // Event listeners
            searchInput.addEventListener('input', performSearch);

            // Handle filter form inputs
            filterForm.querySelectorAll('input[type="date"], select').forEach(input => {
                input.addEventListener('change', performSearch);
            });

            // Prevent form submission and use AJAX instead
            filterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                performSearch();
            });
        });
    </script>
@endpush
