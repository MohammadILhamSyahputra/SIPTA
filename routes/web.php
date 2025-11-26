<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\DetailRiwayatSalesController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\RiwayatSalesController;
use App\Http\Controllers\KategoriController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('barang', BarangController::class);

// Sales
Route::resource('sales', SalesController::class);

// Kategori
Route::resource('kategori', KategoriController::class);

// Riwayat Sales
Route::resource('riwayat-sales', RiwayatSalesController::class);

// Route::resource('detail-riwayat-sales', DetailRiwayatSalesController::class);

Route::get('riwayat-sales/{riwayat_sales_id}/detail/create', 
    [DetailRiwayatSalesController::class, 'create'])->name('detail-riwayat-sales.create');
Route::post('riwayat-sales/detail/store', 
    [App\Http\Controllers\DetailRiwayatSalesController::class, 'store'])->name('detail-riwayat-sales.store');
Route::get('detail-riwayat-sales/{id}/edit', 
    [\App\Http\Controllers\DetailRiwayatSalesController::class, 'edit'])->name('detail-riwayat-sales.edit'); 
Route::put('detail-riwayat-sales/{id}', 
    [\App\Http\Controllers\DetailRiwayatSalesController::class, 'update'])->name('detail-riwayat-sales.update');

// Transaksi
Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/create', [TransaksiController::class, 'create'])->name('create');
    Route::post('/store', [TransaksiController::class, 'store'])->name('store');
    Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('destroy');
});

// Detail Transaksi
Route::prefix('detail-transaksi')->name('detail-transaksi.')->group(function () {
    Route::get('/', [DetailTransaksiController::class, 'index'])->name('index');
    Route::get('/create', [DetailTransaksiController::class, 'create'])->name('create');
    Route::post('/store', [DetailTransaksiController::class, 'store'])->name('store');
    Route::delete('/{id}', [DetailTransaksiController::class, 'destroy'])->name('destroy');
});

// Detail Riwayat Sales
// Route::prefix('detail-riwayat-sales')->name('detail-riwayat-sales.')->group(function () {
//     Route::get('/', [DetailRiwayatSalesController::class, 'index'])->name('index');
//     Route::get('/create', [DetailRiwayatSalesController::class, 'create'])->name('create');
//     Route::post('/store', [DetailRiwayatSalesController::class, 'store'])->name('store');
//     Route::delete('/{id}', [DetailRiwayatSalesController::class, 'destroy'])->name('destroy');
// });
