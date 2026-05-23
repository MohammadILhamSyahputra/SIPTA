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
        
        // Fitur Baru: Ambil barang yang stoknya menipis (< 10)
        $stok_menipis = Barang::where('stok', '<', 10)
                            ->where('stok', '>=', 0)
                            ->get();

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
            'stok_menipis' => $stok_menipis, // Kirim data stok menipis

            'item_labels' => $item_labels, 
            'item_stok_data' => $item_stok_data, 

            'kategori_labels' => $kategori_labels,
            'kategori_data' => $kategori_data,
        ]);
    }
    
    public function cekAksiRestok(Request $request)
    {
        $barangId = $request->get('barang_id');
        
        // Ambil data barang beserta relasi sales-nya
        // Catatan: Pastikan di model Barang sudah kamu buat fungsi relasi bernama 'sales'
        $barang = Barang::with('sales')->findOrFail($barangId);

        // Mengambil id_sales dari barang tersebut (misal: Sabun Lifebuoy terikat ke PT UNILEVER)
        $salesId = $barang->id_sales; 
        $namaSales = $barang->sales ? $barang->sales->nama_sales : 'Sales Terkait';

        // Skenario 1: Cek apakah ada jadwal kunjungan untuk SALES tersebut yang statusnya 'BELUM DATANG'
        // Sesuaikan nama kolom status_kunjungan dan value 'BELUM DATANG' dengan database kelompokmu
        $jadwalBelumDatang = RiwayatSales::where('sales_id', $salesId)
                            ->where('status', 'BELUM DATANG')
                            ->first();

        if ($jadwalBelumDatang) {
            // Jika ada jadwal aktif tapi belum datang, balikkan ke dashboard dengan flash message warning
            return redirect()->back()->with('warning_presensi', "Sales \"{$namaSales}\" untuk produk ini masih berstatus 'BELUM DATANG'. Silakan lakukan konfirmasi kedatangan terlebih dahulu di menu Riwayat Sales.");
        }

        // Skenario 2: Jika tidak ada jadwal aktif (kosong atau semua jadwal lama sudah 'SUDAH DATANG')
        // Alihkan ke form tambah jadwal kunjungan baru dengan membawa parameter sales_id agar auto-selected
        // Sesuaikan nama route tujuan kalian untuk halaman tambah jadwal kunjungan sales
        return redirect()->route('riwayat-sales.create', [
            'sales_id' => $salesId
        ])->with('info_restok', "Tolong tambah jadwal sales \"{$namaSales}\" untuk restok produk ini.");
    }
}
