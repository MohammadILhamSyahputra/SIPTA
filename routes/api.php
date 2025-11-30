<?php
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;


Route::prefix('api')->group(function () {

    Route::get('/barang/search', [BarangController::class, 'search']);
    
    Route::post('/transaksi', [TransaksiController::class, 'storeKasir']);
    Route::get('/transaksi/today', [TransaksiController::class, 'today']);
    Route::put('/transaksi/{id}', [TransaksiController::class, 'updateKasir']);
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroyKasir']);
});

