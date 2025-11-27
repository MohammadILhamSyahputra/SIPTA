<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Sales;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang (Management Stok, default view)
     */
    public function index()
    {
        $barang = Barang::with(['kategori', 'sales'])->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * Menampilkan form untuk membuat barang baru
     */
    public function create()
    {
        $kategori = Kategori::all();
        $sales = Sales::all();
        return view('barang.create', compact('kategori','sales'));
    }

    /**
     * Menyimpan barang baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255', 
            'stok' => 'required|integer',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'id_kategori' => 'required|exists:kategori,id',
            'id_sales' => 'required|exists:sales,id',
        ]);

        Barang::create($request->all());

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit barang
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        $sales = Sales::all();
        return view('barang.edit', compact('barang','kategori','sales'));
    }

    /**
     * Memperbarui barang di database
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'id_kategori' => 'required|exists:kategori,id',
            'id_sales' => 'required|exists:sales,id',
            'stok' => 'required|integer',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang dari database
     */
    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
    
    // ===========================================
    // METHOD BARU UNTUK LAPORAN STOK
    // ===========================================
    /**
     * Menampilkan laporan stok barang dengan data pergerakan fiktif.
     */
    public function laporanStok(Request $request)
    {
        // 1. Mendapatkan tanggal dari query string atau menggunakan default
        $tglMulai = $request->input('tgl_mulai', date('Y-m-01')); // Default: tgl 1 bulan ini
        $tglAkhir = $request->input('tgl_akhir', date('Y-m-d')); // Default: hari ini

        // Validasi sederhana
        if (strtotime($tglMulai) > strtotime($tglAkhir)) {
             $tglMulai = date('Y-m-01');
             $tglAkhir = date('Y-m-d');
        }

        // 2. Ambil semua barang
        $barangs = Barang::all();
        $laporanData = collect();

        // 3. Loop dan hitung data dari tabel transaksi
        foreach ($barangs as $barang) {
            
            // --- PERHITUNGAN STOK MASUK (DETAIL RIWAYAT SALES) ---
            $totalMasuk = DB::table('detail_riwayat_sales')
                ->where('id_barang', $barang->id)
                ->whereBetween(DB::raw('DATE(created_at)'), [$tglMulai, $tglAkhir])
                ->sum('jumlah');

            // --- PERHITUNGAN STOK KELUAR (DETAIL TRANSAKSI / TERJUAL) ---
            $totalTerjual = DB::table('detail_transaksi as dt')
                ->join('transaksi as t', 'dt.id_transaksi', '=', 't.id')
                ->where('dt.id_barang', $barang->id)
                ->whereBetween(DB::raw('DATE(t.created_at)'), [$tglMulai, $tglAkhir]) // Filter berdasarkan tanggal transaksi
                ->sum('dt.jumlah');

            // --- PERHITUNGAN STOK AWAL PERIODE ---
            // Stok Akhir adalah stok saat ini (stok global di tabel barang)
            $stokAkhirGlobal = $barang->stok; 
            
            // Stok Awal Periode = Stok Akhir Global - Total Masuk (Periode Filter) + Total Terjual (Periode Filter)
            // Ini adalah cara termudah dan tercepat untuk mendapatkan Stok Awal, 
            // asalkan kolom 'stok' di tabel barang selalu mencerminkan stok akhir yang benar.
            $stokAwalPeriode = $stokAkhirGlobal - $totalMasuk + $totalTerjual;

            // Pastikan Stok Awal tidak negatif
            $stokAwalPeriode = max(0, $stokAwalPeriode);
            
            // 4. Masukkan data yang sudah dihitung ke koleksi
            $laporanData->push((object)[
                'kode_barang' => $barang->kode_barang,
                'nama' => $barang->nama,
                'harga_beli' => $barang->harga_beli,
                'harga_jual' => $barang->harga_jual,
                'stok_awal' => $stokAwalPeriode,
                'total_masuk' => $totalMasuk,
                'total_terjual' => $totalTerjual,
                'stok_akhir' => $stokAkhirGlobal,
            ]);
        }

        // 5. Kirim data dan tanggal filter ke view
        return view('laporan barang.laporan_stok', compact('laporanData', 'tglMulai', 'tglAkhir'));
    }
}