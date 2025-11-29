<?php

namespace App\Http\Controllers;

use App\Models\Barang; 
use App\Models\Kategori;
use App\Models\Sales; 
use App\Models\RiwayatSales; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_barang = Barang::count();
        $total_kategori = Kategori::count();
        $total_sales = Sales::count();
        
        $stok_per_item = Barang::select('nama', 'stok')
                                 ->where('stok', '>', 0)
                                 ->get();

        $item_labels = $stok_per_item->pluck('nama')->toArray();
        $item_stok_data = $stok_per_item->pluck('stok')->toArray();
        
        $barang_per_kategori = DB::table('barang')
            ->join('kategori', 'barang.id_kategori', '=', 'kategori.id')
            ->select('kategori.nama_kategori', DB::raw('COUNT(barang.id) as jumlah_barang'))
            ->groupBy('kategori.nama_kategori')
            ->get();
            
        $kategori_labels = $barang_per_kategori->pluck('nama_kategori')->toArray();
        $kategori_data = $barang_per_kategori->pluck('jumlah_barang')->toArray();

        return view('dashboard.dashboard', [
            'total_barang' => $total_barang,
            'total_kategori' => $total_kategori,
            'total_sales' => $total_sales, 

            'item_labels' => $item_labels, 
            'item_stok_data' => $item_stok_data, 

            'kategori_labels' => $kategori_labels,
            'kategori_data' => $kategori_data,
        ]);
    }
}
