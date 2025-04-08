@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Edit Layanan</h2>

        <form action="{{ route('layanan.update', $layanan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nama_layanan" class="block text-sm font-medium text-gray-700">Nama Layanan</label>
                <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('nama_layanan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                <select name="satuan" id="satuan" required onchange="updateHargaLabel()"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="">Pilih Satuan</option>
                    <option value="Kg" {{ (old('satuan', $layanan->satuan) == 'Kg') ? 'selected' : '' }}>Kilogram (Kg)</option>
                    <option value="Pcs" {{ (old('satuan', $layanan->satuan) == 'Pcs') ? 'selected' : '' }}>Pieces (Pcs)</option>
                    <option value="Meter" {{ (old('satuan', $layanan->satuan) == 'Meter') ? 'selected' : '' }}>Meter</option>
                </select>
                @error('satuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="harga_per_satuan" id="label_harga" class="block text-sm font-medium text-gray-700">Harga per Satuan</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="harga_per_satuan" id="harga_per_satuan" value="{{ old('harga_per_satuan', $layanan->harga_per_satuan) }}" required
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md"
                        min="0" step="100">
                </div>
                @error('harga_per_satuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('layanan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 mr-2">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateHargaLabel() {
    const satuan = document.getElementById('satuan').value;
    const labelHarga = document.getElementById('label_harga');
    if (satuan) {
        labelHarga.textContent = `Harga per ${satuan}`;
    } else {
        labelHarga.textContent = 'Harga per Satuan';
    }
}

// Panggil saat halaman dimuat untuk mengatur label awal
document.addEventListener('DOMContentLoaded', updateHargaLabel);
</script>

@endsection 