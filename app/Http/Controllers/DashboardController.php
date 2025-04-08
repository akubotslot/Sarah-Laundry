<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $today = now()->format('Y-m-d');
        $transaksiHariIni = Transaksi::whereDate('tanggal_masuk', $today)->count();
        $pendapatanHariIni = Transaksi::whereDate('tanggal_masuk', $today)->sum('total_harga');

        // Statistik bulan ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $transaksBulanIni = Transaksi::whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth])->count();
        $pendapatanBulanIni = Transaksi::whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth])->sum('total_harga');

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::with(['pelanggan'])
            ->latest()
            ->take(5)
            ->get();

        // Layanan terpopuler
        $layananTerpopuler = DB::table('transaksi_details')
            ->select('layanan_id', DB::raw('count(*) as total'))
            ->groupBy('layanan_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $layananIds = $layananTerpopuler->pluck('layanan_id');
        $layanans = Layanan::whereIn('id', $layananIds)->get();

        $layananTerpopuler = $layananTerpopuler->map(function($item) use ($layanans) {
            $item->layanan = $layanans->find($item->layanan_id);
            return $item;
        });

        // Total data
        $totalPelanggan = Pelanggan::count();
        $totalLayanan = Layanan::count();
        $totalTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::sum('total_harga');

        // Tambah total per status
        $totalDiproses = Transaksi::where('status', 'Proses')->count();
        $totalSelesai = Transaksi::where('status', 'Selesai')->count();
        $totalDiambil = Transaksi::where('status', 'Diambil')->count();

        return view('dashboard', compact(
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksBulanIni',
            'pendapatanBulanIni',
            'transaksiTerbaru',
            'layananTerpopuler',
            'totalPelanggan',
            'totalLayanan',
            'totalTransaksi',
            'totalPendapatan',
            'totalDiproses',
            'totalSelesai',
            'totalDiambil'
        ));
    }
}
