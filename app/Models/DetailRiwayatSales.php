<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RiwayatSales;
use App\Models\Barang;

class DetailRiwayatSales extends Model
{
    protected $table = 'detail_riwayat_sales';
    protected $fillable = ['riwayat_id','barang_id','qty_masuk','qty_retur'];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatSales::class, 'riwayat_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
