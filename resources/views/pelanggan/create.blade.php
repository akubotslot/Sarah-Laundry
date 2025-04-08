@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Pelanggan Baru
            </h2>
        </div>

        <form action="{{ route('pelanggan.store') }}" method="POST" class="mt-6">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                    <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('nomor_whatsapp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('pelanggan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 