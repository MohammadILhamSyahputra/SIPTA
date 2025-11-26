<?php

namespace App\Http\Controllers;

use App\Models\DetailRiwayatSales;
use App\Models\RiwayatSales;
use App\Models\Barang;
use Illuminate\Http\Request;

class DetailRiwayatSalesController extends Controller
{
    public function index()
    {
        $detail = DetailRiwayatSales::with(['riwayat', 'barang'])->get();
        return view('detail_riwayat.index', compact('detail'));
    }

    public function create()
    {
        $riwayat = RiwayatSales::all();
        $barang = Barang::all();
        return view('detail_riwayat_sales.create-detail', compact('riwayat','barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'riwayat_id' => 'required|exists:riwayat_sales,id',
            'barang_id' => 'required|exists:barang,id',
            'qty_masuk' => 'required|integer|min:0',
            'qty_retur' => 'required|integer|min:0',
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        if ($request->qty_retur > 0) {
            if ($barang->stok < $request->qty_retur) {
                return back()->with('error', 'Stok tidak cukup untuk retur!');
            }
            $barang->stok -= $request->qty_retur;
        }
        if ($request->qty_masuk > 0) {
            $barang->stok += $request->qty_masuk;
        }

        $barang->save();
        DetailRiwayatSales::create($request->all());

        return redirect()
            ->route('detail-riwayat.index')
            ->with('success', 'Data detail riwayat disimpan dan stok diperbarui!');
    }

    public function edit($id)
    {
        $detail = DetailRiwayatSales::findOrFail($id);
        $riwayat = RiwayatSales::all();
        $barang = Barang::all();
        return view('detail_riwayat.edit', compact('detail','riwayat','barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'riwayat_id' => 'required|exists:riwayat_sales,id',
            'barang_id' => 'required|exists:barang,id',
            'qty_masuk' => 'required|integer|min:0',
            'qty_retur' => 'required|integer|min:0',
        ]);

        $detail = DetailRiwayatSales::findOrFail($id);
        $barang = Barang::findOrFail($request->barang_id);
        $selisihMasuk = $request->qty_masuk - $detail->qty_masuk;

        if ($selisihMasuk > 0) {
            $barang->stok += $selisihMasuk;
        } else {
            $barang->stok += $selisihMasuk; // selisihMasuk negatif → stok berkurang otomatis
        }

        // Hitung selisih retur
        $selisihRetur = $request->qty_retur - $detail->qty_retur;

        if ($selisihRetur > 0) {
            // tambah retur → kurangi stok
            if ($barang->stok < $selisihRetur) {
                return back()->with('error', 'Stok tidak cukup untuk update retur!');
            }
            $barang->stok -= $selisihRetur;
        } else {
            // retur berkurang → stok kembali
            $barang->stok -= $selisihRetur; // selisihRetur negatif → stok bertambah otomatis
        }

        $barang->save();
        $detail->update($request->all());

        return redirect()
            ->route('detail-riwayat.index')
            ->with('success', 'Data detail berhasil diperbarui & stok disesuaikan!');
    }
}
