<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
        $table->enum('metode_pembayaran', ['tunai', 'qris'])->default('tunai')->after('total_harga');
        $table->string('status_pembayaran')->default('pending')->after('metode_pembayaran'); // pending, success, expire
        $table->string('snap_token')->nullable()->after('status_pembayaran'); // Untuk menyimpan token Midtrans
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
