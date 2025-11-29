<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\DetailRiwayatSalesController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\RiwayatSalesController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanSalesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('barang', BarangController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('riwayat-sales', RiwayatSalesController::class);
    Route::get('riwayat-sales/{riwayat_sales_id}/detail/create',
        [DetailRiwayatSalesController::class, 'create'])->name('detail-riwayat-sales.create');
    Route::post('riwayat-sales/detail/store',
        [DetailRiwayatSalesController::class, 'store'])->name('detail-riwayat-sales.store');
    Route::get('detail-riwayat-sales/{id}/edit',
        [DetailRiwayatSalesController::class, 'edit'])->name('detail-riwayat-sales.edit');
    Route::put('detail-riwayat-sales/{id}',
        [DetailRiwayatSalesController::class, 'update'])->name('detail-riwayat-sales.update');

});

Route::middleware(['auth', 'owner'])->group(function () {
    Route::get('laporan-stok-barang', [BarangController::class, 'laporanStok'])
        ->name('laporan_barang.laporan_stok');
    Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan.index');
    Route::post('/laporan-penjualan', [LaporanPenjualanController::class, 'filter'])->name('laporan.penjualan.filter');

    Route::get('/laporan-penjualan/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.exportPdf');
});

Route::resource('laporan-sales', SalesController::class);

Route::middleware(['auth', 'kasir'])->group(function () {
    Route::resource('kategori', KategoriController::class);
});

// Route::resource('barang', BarangController::class);
// Route::resource('sales', SalesController::class);
// Route::resource('kategori', KategoriController::class);
// Route::resource('riwayat-sales', RiwayatSalesController::class);
// Route::get('riwayat-sales/{riwayat_sales_id}/detail/create',
//     [DetailRiwayatSalesController::class, 'create'])->name('detail-riwayat-sales.create');
// Route::post('riwayat-sales/detail/store',
//     [DetailRiwayatSalesController::class, 'store'])->name('detail-riwayat-sales.store');
// Route::get('detail-riwayat-sales/{id}/edit',
//     [DetailRiwayatSalesController::class, 'edit'])->name('detail-riwayat-sales.edit');
// Route::put('detail-riwayat-sales/{id}',
//     [DetailRiwayatSalesController::class, 'update'])->name('detail-riwayat-sales.update');


Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/create', [TransaksiController::class, 'create'])->name('create');
    Route::post('/store', [TransaksiController::class, 'store'])->name('store');
    Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('destroy');
});

Route::prefix('detail-transaksi')->name('detail-transaksi.')->group(function () {
    Route::get('/', [DetailTransaksiController::class, 'index'])->name('index');
    Route::get('/create', [DetailTransaksiController::class, 'create'])->name('create');
    Route::post('/store', [DetailTransaksiController::class, 'store'])->name('store');
    Route::delete('/{id}', [DetailTransaksiController::class, 'destroy'])->name('destroy');
});

// Detail Riwayat Sales
Route::prefix('detail-riwayat-sales')->name('detail-riwayat-sales.')->group(function () {
    Route::get('/', [DetailRiwayatSalesController::class, 'index'])->name('index');
    Route::get('/create', [DetailRiwayatSalesController::class, 'create'])->name('create');
    Route::post('/store', [DetailRiwayatSalesController::class, 'store'])->name('store');
    Route::delete('/{id}', [DetailRiwayatSalesController::class, 'destroy'])->name('destroy');
});

// Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan.index');
// Route::post('/laporan-penjualan', [LaporanPenjualanController::class, 'filter'])->name('laporan.penjualan.filter');

// Route::get('/laporan-penjualan/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.exportPdf');
