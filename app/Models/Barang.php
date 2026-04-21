<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use App\Models\Sales;
use App\Models\DetailTransaksi;
use App\Models\DetailRiwayatSales;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $fillable = ['kode_barang','nama','stok','harga_beli','harga_jual','id_kategori','id_sales'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'id_sales');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang');
    }

    public function detailRiwayatSales()
    {
        return $this->hasMany(DetailRiwayatSales::class, 'barang_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_barang');
    }
}
