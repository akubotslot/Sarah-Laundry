<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'details.layanan'])->latest('tanggal_masuk');

        // Pencarian berdasarkan kode transaksi atau nama pelanggan
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_transaksi', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('pelanggan', function ($q) use ($searchTerm) {
                        $q->where('nama', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan pelanggan
        if ($request->filled('pelanggan_id')) {
            $query->where('pelanggan_id', $request->pelanggan_id);
        }

        $transaksis = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('transaksi.partials.transaction-rows', compact('transaksis'))->render();
        }

        $pelanggans = Pelanggan::all();
        return view('transaksi.index', compact('transaksis', 'pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $layanans = Layanan::all();
        return view('transaksi.create', compact('pelanggans', 'layanans'));
    }

    private function generateKodeTransaksi($tanggalMasuk)
    {
        $tanggal = Carbon::parse($tanggalMasuk);
        $tahun = $tanggal->format('Y');
        $bulan = $tanggal->format('m');
        $tanggal = $tanggal->format('d');

        $lastTransaction = Transaksi::whereDate('tanggal_masuk', $tanggalMasuk)
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->kode_transaksi, -4));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorUrut = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $kodeTransaksi = "SL-{$tahun}{$bulan}{$tanggal}-{$nomorUrut}";

        while (Transaksi::where('kode_transaksi', $kodeTransaksi)->exists()) {
            $nextNumber++;
            $nomorUrut = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $kodeTransaksi = "SL-{$tahun}{$bulan}{$tanggal}-{$nomorUrut}";
        }

        return $kodeTransaksi;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_masuk' => 'required|date',
            'layanan_id' => 'required|array',
            'layanan_id.*' => 'required|exists:layanans,id',
            'jumlah_satuan' => 'required|array',
            'jumlah_satuan.*' => 'required|numeric|min:0.1',
            'panjang_cm' => 'nullable|array',
            'panjang_cm.*' => 'nullable|integer|min:0',
            'lebar_cm' => 'nullable|array',
            'lebar_cm.*' => 'nullable|integer|min:0',
            'status' => 'required|in:Proses,Selesai,Diambil',
        ]);

        // Validasi tambahan untuk layanan dengan satuan Meter dan Kg
        foreach ($request->layanan_id as $index => $layananId) {
            $layanan = Layanan::findOrFail($layananId);
            if ($layanan->satuan === 'Meter') {
                if (empty($request->panjang_cm[$index]) || empty($request->lebar_cm[$index])) {
                    return back()
                        ->withInput()
                        ->withErrors(['ukuran' => 'Panjang dan lebar harus diisi untuk layanan dengan satuan Meter.']);
                }
            }

            if ($layanan->satuan === 'Kg') {
                if (!is_numeric($request->jumlah_satuan[$index]) || floor($request->jumlah_satuan[$index] * 10) != $request->jumlah_satuan[$index] * 10) {
                    return back()
                        ->withInput()
                        ->withErrors(['jumlah_satuan.' . $index => 'Jumlah satuan untuk Kilogram (Kg) harus dalam format desimal dengan 1 angka dibelakang koma (contoh: 1.5)']);
                }
            } else {
                if (!is_numeric($request->jumlah_satuan[$index]) || floor($request->jumlah_satuan[$index]) != $request->jumlah_satuan[$index]) {
                    return back()
                        ->withInput()
                        ->withErrors(['jumlah_satuan.' . $index => 'Jumlah satuan harus berupa bilangan bulat untuk satuan selain Kilogram (Kg)']);
                }
            }
        }

        // Buat transaksi baru
        $transaksi = new Transaksi();
        $transaksi->pelanggan_id = $request->pelanggan_id;
        $transaksi->tanggal_masuk = $request->tanggal_masuk;
        $transaksi->status = $request->status;
        $transaksi->kode_transaksi = $this->generateKodeTransaksi($request->tanggal_masuk);

        // Hitung total harga dari semua layanan
        $totalHargaKeseluruhan = 0;

        // Simpan transaksi terlebih dahulu untuk mendapatkan ID
        $transaksi->total_harga = 0; // Sementara set 0
        $transaksi->save();

        // Proses setiap layanan
        foreach ($request->layanan_id as $index => $layananId) {
            $layanan = Layanan::findOrFail($layananId);
            $jumlahSatuan = $request->jumlah_satuan[$index];

            // Format jumlah satuan berdasarkan tipe
            if ($layanan->satuan === 'Kg') {
                $jumlahSatuan = round($jumlahSatuan, 1); // Pembulatan ke 1 desimal
            } else {
                $jumlahSatuan = round($jumlahSatuan); // Pembulatan ke bilangan bulat
            }

            // Hitung total harga untuk layanan ini
            if ($layanan->satuan === 'Meter') {
                $panjangMeter = $request->panjang_cm[$index] / 100;
                $lebarMeter = $request->lebar_cm[$index] / 100;
                $luas = $panjangMeter * $lebarMeter;
                $totalHarga = $layanan->harga_per_satuan * $luas * $jumlahSatuan;
            } else {
                $totalHarga = $layanan->harga_per_satuan * $jumlahSatuan;
            }

            // Simpan detail transaksi
            $detail = new TransaksiDetail([
                'layanan_id' => $layananId,
                'jumlah_satuan' => $jumlahSatuan,
                'panjang_cm' => $request->panjang_cm[$index] ?? null,
                'lebar_cm' => $request->lebar_cm[$index] ?? null,
                'total_harga' => $totalHarga
            ]);

            $transaksi->details()->save($detail);
            $totalHargaKeseluruhan += $totalHarga;
        }

        // Update total harga keseluruhan
        $transaksi->total_harga = $totalHargaKeseluruhan;
        $transaksi->save();

        return redirect()->route('transaksi.show', $transaksi)
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'details.layanan']);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $pelanggans = Pelanggan::all();
        $layanans = Layanan::all();
        return view('transaksi.edit', compact('transaksi', 'pelanggans', 'layanans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_masuk' => 'required|date',
            'layanan_id' => 'required|array',
            'layanan_id.*' => 'required|exists:layanans,id',
            'jumlah_satuan' => 'required|array',
            'jumlah_satuan.*' => 'required|numeric|min:0.1',
            'panjang_cm' => 'nullable|array',
            'panjang_cm.*' => 'nullable|integer|min:0',
            'lebar_cm' => 'nullable|array',
            'lebar_cm.*' => 'nullable|integer|min:0',
            'status' => 'required|in:Proses,Selesai,Diambil',
        ]);

        // Validasi tambahan untuk layanan dengan satuan Meter dan Kg
        foreach ($request->layanan_id as $index => $layananId) {
            $layanan = Layanan::findOrFail($layananId);
            if ($layanan->satuan === 'Meter') {
                if (empty($request->panjang_cm[$index]) || empty($request->lebar_cm[$index])) {
                    return back()
                        ->withInput()
                        ->withErrors(['ukuran' => 'Panjang dan lebar harus diisi untuk layanan dengan satuan Meter.']);
                }
            }

            if ($layanan->satuan === 'Kg') {
                if (!is_numeric($request->jumlah_satuan[$index]) || floor($request->jumlah_satuan[$index] * 10) != $request->jumlah_satuan[$index] * 10) {
                    return back()
                        ->withInput()
                        ->withErrors(['jumlah_satuan.' . $index => 'Jumlah satuan untuk Kilogram (Kg) harus dalam format desimal dengan 1 angka dibelakang koma (contoh: 1.5)']);
                }
            } else {
                if (!is_numeric($request->jumlah_satuan[$index]) || floor($request->jumlah_satuan[$index]) != $request->jumlah_satuan[$index]) {
                    return back()
                        ->withInput()
                        ->withErrors(['jumlah_satuan.' . $index => 'Jumlah satuan harus berupa bilangan bulat untuk satuan selain Kilogram (Kg)']);
                }
            }
        }

        // Update data transaksi utama
        if ($transaksi->tanggal_masuk->format('Y-m-d') !== Carbon::parse($request->tanggal_masuk)->format('Y-m-d')) {
            $transaksi->kode_transaksi = $this->generateKodeTransaksi($request->tanggal_masuk);
        }

        $transaksi->pelanggan_id = $request->pelanggan_id;
        $transaksi->tanggal_masuk = $request->tanggal_masuk;
        $transaksi->status = $request->status;

        // Hapus semua detail transaksi yang lama
        $transaksi->details()->delete();

        // Hitung total harga dari semua layanan
        $totalHargaKeseluruhan = 0;

        // Proses setiap layanan
        foreach ($request->layanan_id as $index => $layananId) {
            $layanan = Layanan::findOrFail($layananId);
            $jumlahSatuan = $request->jumlah_satuan[$index];

            // Format jumlah satuan berdasarkan tipe
            if ($layanan->satuan === 'Kg') {
                $jumlahSatuan = round($jumlahSatuan, 1); // Pembulatan ke 1 desimal
            } else {
                $jumlahSatuan = round($jumlahSatuan); // Pembulatan ke bilangan bulat
            }

            // Hitung total harga untuk layanan ini
            if ($layanan->satuan === 'Meter') {
                $panjangMeter = $request->panjang_cm[$index] / 100;
                $lebarMeter = $request->lebar_cm[$index] / 100;
                $luas = $panjangMeter * $lebarMeter;
                $totalHarga = $layanan->harga_per_satuan * $luas * $jumlahSatuan;
            } else {
                $totalHarga = $layanan->harga_per_satuan * $jumlahSatuan;
            }

            // Simpan detail transaksi
            $detail = new TransaksiDetail([
                'layanan_id' => $layananId,
                'jumlah_satuan' => $jumlahSatuan,
                'panjang_cm' => $request->panjang_cm[$index] ?? null,
                'lebar_cm' => $request->lebar_cm[$index] ?? null,
                'total_harga' => $totalHarga
            ]);

            $transaksi->details()->save($detail);
            $totalHargaKeseluruhan += $totalHarga;
        }

        // Update total harga keseluruhan
        $transaksi->total_harga = $totalHargaKeseluruhan;
        $transaksi->save();

        return redirect()->route('transaksi.show', $transaksi)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')
            ->with('success', 'Data transaksi berhasil dihapus');
    }

    public function print(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'details.layanan']);
        return view('transaksi.print', compact('transaksi'));
    }

    public function printA6(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'details.layanan']);
        return view('transaksi.print-a6', compact('transaksi'));
    }

    public function printPdf(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'details.layanan']);
        $pdf = Pdf::loadView('transaksi.pdf.nota', compact('transaksi'));
        return $pdf->download('nota-' . $transaksi->kode_transaksi . '.pdf');
    }

    public function kirimPesanWhatsapp($kode_transaksi)
{
    // Ambil transaksi beserta relasi pelanggan dan details
    $transaksi = Transaksi::with(['details.layanan', 'pelanggan'])
                    ->where('kode_transaksi', $kode_transaksi)
                    ->first();

    if (!$transaksi) {
        return back()->with('error', 'Transaksi tidak ditemukan.');
    }

    $details = $transaksi->details;
    $pelanggan = $transaksi->pelanggan;

    if (!$pelanggan) {
        return back()->with('error', 'Data pelanggan tidak ditemukan.');
    }

    // Format pesan seperti sebelumnya
    $pesan = "*Detail Transaksi*\n";
    $pesan .= "Kode: {$transaksi->kode_transaksi}\n";
    $pesan .= "Tanggal Masuk: " . $transaksi->tanggal_masuk->format('d/m/Y H:i') . "\n";
    $pesan .= "Status: {$transaksi->status}\n";
    $pesan .= "Nama Pelanggan: {$pelanggan->nama}\n";
    $pesan .= "No WA: {$pelanggan->nomor_whatsapp}\n";
    $pesan .= "\n*Rincian Layanan:*\n";

    foreach ($details as $detail) {
        $layanan = $detail->layanan;
        $jumlahSatuan = rtrim(rtrim(number_format($detail->jumlah_satuan, 2, '.', ''), '0'), '.');
        $pesan .= "- {$layanan->nama_layanan} ({$jumlahSatuan} {$layanan->satuan}) = Rp " . number_format($detail->total_harga, 0, ',', '.') . "\n";
        
        if ($layanan->satuan === 'Meter' && $detail->panjang_cm && $detail->lebar_cm) {
            $pesan .= "  Ukuran: {$detail->panjang_cm}cm x {$detail->lebar_cm}cm\n";
        }
    }

    $pesan .= "\nTotal: *Rp " . number_format($transaksi->total_harga, 0, ',', '.') . "*";

    // Ambil nomor WhatsApp dari pengguna yang sedang login

    $nomorWhatsApp = Auth::user()->nomor_whatsapp;

    // Kirim ke Fonnte
    $response = Http::withHeaders([
        'Authorization' => 'e11EpcJzAcaEsmHJEcNW',
    ])->post('https://api.fonnte.com/send', [
        'target' => $nomorWhatsApp, // Gunakan nomor WhatsApp dari pengguna yang sedang login
        'message' => $pesan,
        'countryCode' => '62',
    ]);

    if ($response->successful()) {
        return back()->with('success', 'Pesan WhatsApp berhasil dikirim.');
    } else {
        logger()->error('Gagal kirim WA:', ['res' => $response->body()]);
        return back()->with('error', 'Gagal mengirim pesan WhatsApp.');
    }
}

}
