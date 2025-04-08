@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-6">Buat Transaksi</h2>

            <form action="{{ route('transaksi.store') }}" method="POST" id="transaksiForm">
                @csrf
                <div class="space-y-4">
                    <!-- Informasi Transaksi -->
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold mb-4">Informasi Transaksi</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="pelanggan_id"
                                    class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                                <div class="flex space-x-2">
                                    <select name="pelanggan_id" id="pelanggan_id"
                                        class="flex-1 mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('pelanggan_id') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Pelanggan</option>
                                        @foreach ($pelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}"
                                                {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                                {{ $pelanggan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openPelangganModal()"
                                        class="mt-1 bg-blue-500 text-white px-3 py-2 rounded-md hover:bg-blue-600">
                                        + Tambah Pelanggan 
                                    </button>
                                </div>
                                @error('pelanggan_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <div>
                                <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Masuk</label>
                                <input type="datetime-local" name="tanggal_masuk" id="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', now()->format('Y-m-d\TH:i')) }}"
                                    class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tanggal_masuk') border-red-500 @enderror"
                                    required>
                                @error('tanggal_masuk')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Detail Layanan -->
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Detail Layanan</h3>
                            <button type="button" onclick="tambahLayanan()"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                + Tambah Layanan
                            </button>
                        </div>

                        <div id="layanan-container">
                            <!-- Template untuk layanan akan ditambahkan di sini -->
                        </div>
                    </div>

                    <!-- Status dan Total -->
                    <div class="border-b border-gray-200 pb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror"
                                    required>
                                    <option value="Proses" {{ old('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="Diambil" {{ old('status') == 'Diambil' ? 'selected' : '' }}>Diambil
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                                <div class="text-2xl font-bold text-gray-900" id="total_harga_keseluruhan">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <a href="{{ route('transaksi.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Kembali
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
            <!-- Modal Tambah Pelanggan -->
            <div id="pelangganModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Tambah Pelanggan Baru</h3>
                    <form method="POST" action="{{ route('pelanggan.store') }}">
                        @csrf
                        <input type="hidden" name="from_pesanan" value="1">
                        <div class="mb-4">
                            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="nama" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="mb-4">
                            <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                WhatsApp</label>
                            <input type="text" name="nomor_whatsapp" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closePelangganModal()"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Batal
                            </button>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <!-- Template untuk layanan -->
    <template id="layanan-template">
        <div class="layanan-item border rounded-lg p-4 mb-4 relative">
            <button type="button" onclick="hapusLayanan(this)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Layanan</label>
                    <select name="layanan_id[]"
                        class="layanan-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">Pilih Layanan</option>
                        @foreach ($layanans as $layanan)
                            <option value="{{ $layanan->id }}" data-satuan="{{ $layanan->satuan }}"
                                data-harga="{{ $layanan->harga_per_satuan }}">
                                {{ $layanan->nama_layanan }} (Rp
                                {{ number_format($layanan->harga_per_satuan, 0, ',', '.') }}/{{ $layanan->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span
                            class="satuan-label"></span></label>
                    <input type="number" name="jumlah_satuan[]"
                        class="jumlah-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        min="1" step="1" required>
                </div>

                <div class="ukuran-container hidden md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Panjang (cm)</label>
                            <input type="number" name="panjang_cm[]"
                                class="panjang-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lebar (cm)</label>
                            <input type="number" name="lebar_cm[]"
                                class="lebar-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga Layanan</label>
                    <div class="text-xl font-bold text-gray-900 total-harga-layanan">Rp 0</div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        function openPelangganModal() {
            document.getElementById('pelangganModal').classList.remove('hidden');
        }

        function closePelangganModal() {
            document.getElementById('pelangganModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formPelangganBaru');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const nama = document.getElementById('nama_pelanggan').value;
                    const nomor = document.getElementById('nomor_whatsapp').value;

                    fetch("{{ route('pelanggan.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                nama: nama,
                                nomor_whatsapp: nomor
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Tambahkan ke select
                            const select = document.getElementById('pelanggan_id');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.text = data.nama;
                            option.selected = true;
                            select.appendChild(option);

                            closePelangganModal();
                            document.getElementById('formPelangganBaru').reset();
                        })
                        .catch(error => {
                            console.error('Gagal menambahkan pelanggan:', error);
                            alert('Terjadi kesalahan saat menyimpan pelanggan.');
                        });
                });
            }

        });

        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan layanan pertama saat halaman dimuat
            tambahLayanan();
        });


        function tambahLayanan() {
            const template = document.getElementById('layanan-template');
            const container = document.getElementById('layanan-container');
            const clone = template.content.cloneNode(true);

            // Setup event listeners untuk layanan baru
            const layananSelect = clone.querySelector('.layanan-select');
            const jumlahInput = clone.querySelector('.jumlah-input');
            const panjangInput = clone.querySelector('.panjang-input');
            const lebarInput = clone.querySelector('.lebar-input');
            const ukuranContainer = clone.querySelector('.ukuran-container');
            const satuanLabel = clone.querySelector('.satuan-label');

            layananSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const satuan = selectedOption.getAttribute('data-satuan');

                // Update label satuan
                satuanLabel.textContent = satuan ? `(${satuan})` : '';

                // Set step dan validasi berdasarkan satuan
                if (satuan === 'Kg') {
                    jumlahInput.step = '0.1';
                    jumlahInput.min = '0.1';
                    jumlahInput.value = '';
                } else {
                    jumlahInput.step = '1';
                    jumlahInput.min = '1';
                    jumlahInput.value = '';
                }

                toggleUkuranFields(this);
                hitungTotalHarga(this.closest('.layanan-item'));
            });

            jumlahInput.addEventListener('input', function() {
                const layananSelect = this.closest('.layanan-item').querySelector('.layanan-select');
                const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                const satuan = selectedOption.getAttribute('data-satuan');

                if (satuan === 'Kg') {
                    // Batasi input ke 1 desimal untuk Kg
                    let value = this.value;
                    if (value.includes('.')) {
                        const parts = value.split('.');
                        if (parts[1].length > 1) {
                            this.value = parseFloat(value).toFixed(1);
                        }
                    }
                } else {
                    // Untuk satuan lain, bulatkan ke bilangan bulat dan minimal 1
                    this.value = Math.max(1, Math.round(this.value));
                }

                hitungTotalHarga(this.closest('.layanan-item'));
            });

            panjangInput.addEventListener('input', function() {
                hitungTotalHarga(this.closest('.layanan-item'));
            });

            lebarInput.addEventListener('input', function() {
                hitungTotalHarga(this.closest('.layanan-item'));
            });

            container.appendChild(clone);
            updateTotalKeseluruhan();
        }

        function hapusLayanan(button) {
            const layananItem = button.closest('.layanan-item');
            layananItem.remove();
            updateTotalKeseluruhan();
        }

        function toggleUkuranFields(select) {
            const layananItem = select.closest('.layanan-item');
            const ukuranContainer = layananItem.querySelector('.ukuran-container');
            const panjangInput = layananItem.querySelector('.panjang-input');
            const lebarInput = layananItem.querySelector('.lebar-input');

            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || selectedOption.value === '') {
                ukuranContainer.classList.add('hidden');
                panjangInput.required = false;
                lebarInput.required = false;
                return;
            }

            const satuan = selectedOption.getAttribute('data-satuan');
            if (satuan === 'Meter') {
                ukuranContainer.classList.remove('hidden');
                panjangInput.required = true;
                lebarInput.required = true;
            } else {
                ukuranContainer.classList.add('hidden');
                panjangInput.required = false;
                lebarInput.required = false;
            }
        }

        function hitungTotalHarga(layananItem) {
            const select = layananItem.querySelector('.layanan-select');
            const selectedOption = select.options[select.selectedIndex];
            const totalDisplay = layananItem.querySelector('.total-harga-layanan');

            if (!selectedOption || selectedOption.value === '') {
                totalDisplay.textContent = 'Rp 0';
                updateTotalKeseluruhan();
                return;
            }

            const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const jumlah = parseFloat(layananItem.querySelector('.jumlah-input').value) || 0;
            const satuan = selectedOption.getAttribute('data-satuan');
            let totalHarga = 0;

            if (satuan === 'Meter') {
                const panjang = (parseFloat(layananItem.querySelector('.panjang-input').value) || 0) / 100;
                const lebar = (parseFloat(layananItem.querySelector('.lebar-input').value) || 0) / 100;
                const luasMeter = panjang * lebar;
                totalHarga = harga * luasMeter * jumlah;
            } else {
                totalHarga = harga * jumlah;
            }

            totalDisplay.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            updateTotalKeseluruhan();
        }

        function updateTotalKeseluruhan() {
            const totalElements = document.querySelectorAll('.total-harga-layanan');
            let totalKeseluruhan = 0;

            totalElements.forEach(element => {
                const harga = parseInt(element.textContent.replace(/\D/g, '')) || 0;
                totalKeseluruhan += harga;
            });

            document.getElementById('total_harga_keseluruhan').textContent = 'Rp ' + totalKeseluruhan
                .toLocaleString(
                    'id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
        }
    </script>
@endpush
