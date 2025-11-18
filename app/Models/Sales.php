<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\RiwayatSales;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_sales','no_telp','alamat'];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_sales');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatSales::class, 'sales_id');
    }
}
