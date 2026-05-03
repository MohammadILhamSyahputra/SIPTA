<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    protected $fillable = ['order_id', 'total_harga', 'metode_pembayaran', 'status_pembayaran', 'snap_token', 'total_bayar', 'kembalian', 'tanggal'];
    
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
