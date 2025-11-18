<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sales;
use App\Models\DetailRiwayatSales;

class RiwayatSales extends Model
{
    protected $table = 'riwayat_sales';
    protected $fillable = ['sales_id','status','tanggal_kunjungan'];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailRiwayatSales::class, 'riwayat_id');
    }
}
