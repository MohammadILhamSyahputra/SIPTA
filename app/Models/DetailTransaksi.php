<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\Transaksi;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $fillable = ['id_transaksi','id_barang','qty','harga_satuan','subtotal'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
