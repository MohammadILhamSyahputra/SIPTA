<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with(['kategori', 'sales'])->get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $sales = Sales::all();
        return view('barang.create', compact('kategori','sales'));
    }

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

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        $sales = Sales::all();
        return view('barang.edit', compact('barang','kategori','sales'));
    }

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

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete(); 
    
        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
    

    public function laporanStok(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', date('Y-m-01')); 
        $tglAkhir = $request->input('tgl_akhir', date('Y-m-d'));
        
        if (strtotime($tglMulai) > strtotime($tglAkhir)) {
            $tglMulai = date('Y-m-01');
            $tglAkhir = date('Y-m-d');
        }

        $laporanData = DB::table('detail_transaksi as dt')
            ->select(
                'b.kode_barang',
                'b.nama',
                'b.harga_beli',
                'b.harga_jual',
                DB::raw('SUM(dt.qty) as total_terjual')
            )
            ->join('transaksi as t', 'dt.id_transaksi', '=', 't.id')
            ->join('barang as b', 'dt.id_barang', '=', 'b.id')
            ->whereBetween(DB::raw('DATE(t.created_at)'), [$tglMulai, $tglAkhir])
            ->groupBy('b.kode_barang', 'b.nama', 'b.harga_beli', 'b.harga_jual')
            ->having('total_terjual', '>', 0)
            ->orderByDesc('total_terjual')
            ->get();

        $topBarangChart = $laporanData->take(10); 
        $chartLabels = $topBarangChart->pluck('nama')->toArray();
        $chartData = $topBarangChart->pluck('total_terjual')->toArray();

        return view('laporan_barang.laporan_stok', compact('laporanData', 'tglMulai', 'tglAkhir', 'chartLabels', 'chartData'));
    }
}