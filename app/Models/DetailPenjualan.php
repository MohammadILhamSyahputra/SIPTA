<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualans';
    protected $fillable = ['transaksi_id', 'barang_id', 'jumlah', 'harga_satuan'];

    /**
     * Relasi ke tabel Sales
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    /**
     * Relasi ke tabel Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}