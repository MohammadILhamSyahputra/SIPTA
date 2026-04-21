<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RiwayatSales;
use App\Models\Barang;

class DetailRiwayatSales extends Model
{
    use HasFactory;
    protected $table = 'detail_riwayat_sales';
    protected $primaryKey = 'id';
    protected $fillable = ['riwayat_sales_id', 'barang_id', 'qty_masuk', 'qty_retur'];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatSales::class, 'riwayat_sales_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }
}
