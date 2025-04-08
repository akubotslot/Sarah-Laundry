@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Edit Pelanggan</h2>
        
        <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                <input type="text" name="nama" id="nama" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required value="{{ old('nama', $pelanggan->nama) }}">
            </div>

            <div class="mb-4">
                <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required value="{{ old('nomor_whatsapp', $pelanggan->nomor_whatsapp) }}">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 