<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['total_harga','total_bayar','kembalian','tanggal'];

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
