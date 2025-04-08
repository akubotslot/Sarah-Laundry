@forelse($transaksis as $transaksi)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
            {{ $transaksi->kode_transaksi }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
            {{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
            {{ $transaksi->pelanggan->nama }}
        </td>
        <td class="px-6 py-4 text-sm">
            @foreach($transaksi->details as $detail)
                {{ $detail->layanan->nama_layanan }}
                <br>
                <small class="text-gray-500">
                    {{ rtrim(rtrim(number_format($detail->jumlah_satuan, 2), '0'), '.') }} {{ $detail->layanan->satuan === 'Meter' ? 'Pcs' : $detail->layanan->satuan }}
                    @if($detail->layanan->satuan === 'Meter' && $detail->panjang_cm && $detail->lebar_cm)
                        ({{ $detail->panjang_cm }}cm x {{ $detail->lebar_cm }}cm)
                    @endif
                </small>
                @if(!$loop->last)<hr class="my-1">@endif
            @endforeach
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($transaksi->status === 'Selesai')
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Selesai
                </span>
            @elseif($transaksi->status === 'Proses')
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Proses
                </span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    Diambil
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
            <a href="{{ route('transaksi.show', $transaksi) }}"
                class="text-indigo-600 hover:text-indigo-900 mr-2">Detail</a>
            <a href="{{ route('transaksi.edit', $transaksi) }}"
                class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" class="inline" onsubmit="confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center">
            Tidak ada data transaksi
        </td>
    </tr>
@endforelse