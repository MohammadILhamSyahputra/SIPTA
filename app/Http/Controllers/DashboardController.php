<?php

namespace App\Http\Controllers;

use App\Models\Barang; // Asumsikan Anda memiliki model ini
use App\Models\Kategori; // Asumsikan Anda memiliki model ini
use App\Models\RiwayatSales; // Asumsikan Anda memiliki model ini
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data ringkasan
        $total_barang = Barang::count();
        $total_kategori = Kategori::count();
        $total_sales_bulan_ini = RiwayatSales::whereMonth('tanggal_kunjungan', now()->month)
                                            ->whereYear('tanggal_kunjungan', now()->year)
                                            ->count();

        // Data dummy untuk chart (sesuaikan dengan data real Anda)
        $earnings_monthly = 40000;
        $earnings_annual = 215000;

        // Kirim data ke view
        return view('dashboard.dashboard', [
            'total_barang' => $total_barang,
            'total_kategori' => $total_kategori,
            'total_sales_bulan_ini' => $total_sales_bulan_ini,
            'earnings_monthly' => $earnings_monthly,
            'earnings_annual' => $earnings_annual,
            // Tambahkan data lain untuk chart jika diperlukan
        ]);
    }
}
