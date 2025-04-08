<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PengeluaranController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('pelanggan', PelangganController::class);
    Route::get('/search-pelanggan', [PelangganController::class, 'search'])->name('pelanggan.search');
    Route::resource('layanan', LayananController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::get('transaksi/{transaksi}/pdf', [TransaksiController::class, 'printPdf'])->name('transaksi.pdf');
    Route::get('/transaksi/{transaksi}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
    Route::get('/transaksi/{transaksi}/print-a6', [TransaksiController::class, 'printA6'])->name('transaksi.print.a6');
    Route::get('/get-transaksi-id/{kode_transaksi}', function ($kode_transaksi) {
        $transaksi = \App\Models\Transaksi::where('kode_transaksi', $kode_transaksi)->first();

        if ($transaksi) {
            return response()->json(['exists' => true, 'id' => $transaksi->id]);
        } else {
            return response()->json(['exists' => false]);
        }
    });
    Route::get('/download-barcode/{kode_transaksi}', [BarcodeController::class, 'generateBarcode'])->name('isirouteuntukdownloadbarcode');
    Route::get('/transaksi/wa/{kode_transaksi}', [TransaksiController::class, 'kirimPesanWhatsapp'])->name('transaksi.wa');
    Route::get('/export-laporan', [ExportController::class, 'exportLaporan'])->name('export.laporan');

});

    
