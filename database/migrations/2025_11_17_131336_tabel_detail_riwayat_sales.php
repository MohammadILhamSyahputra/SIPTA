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
        Schema::create('detail_riwayat_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riwayat_sales_id')->constrained('riwayat_sales');
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('qty_masuk');
            $table->integer('qty_retur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_riwayat_sales');
    }
};
