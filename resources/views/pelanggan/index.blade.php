@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Daftar Pelanggan</h2>
                <a href="{{ route('pelanggan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700
                    text-base lg:text-xs">
                    Tambah Pelanggan
                </a>

            </div>
            <div class="relative flex items-center mb-4 ">
                <input type="text" id="searchInput" placeholder="Cari nama pelanggan..."
                    class="w-full mr-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <div class="absolute right-5 top-2.5 text-gray-400"> <!-- Ubah right dari 3 ke 16 -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor
                                WhatsApp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="pelangganTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach ($pelanggans as $pelanggan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ($pelanggans->currentPage() - 1) * $pelanggans->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pelanggan->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pelanggan->nomor_whatsapp }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="confirmDelete(event)">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>

            <div class="mt-4">
                {{ $pelanggans->links() }}
            </div>
        </div>
    </div>

    <script>
         document.getElementById('searchInput').addEventListener('input', function () {
        let keyword = this.value;

        fetch(`{{ route('pelanggan.search') }}?keyword=${keyword}`)
            .then(response => response.json())
            .then(data => {
                let tbody = document.getElementById('pelangganTableBody');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada data ditemukan.</td></tr>';
                } else {
                    data.forEach((pelanggan, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pelanggan.nama}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pelanggan.nomor_whatsapp}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="/pelanggan/${pelanggan.id}/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="/pelanggan/${pelanggan.id}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                }
            });
    });
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
    </script>
@endsection
