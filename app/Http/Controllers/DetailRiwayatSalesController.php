<?php

namespace App\Http\Controllers;

use App\Models\DetailRiwayatSales;
use App\Models\RiwayatSales;
use App\Models\Barang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DetailRiwayatSalesController extends Controller
{
    public function index()
    {
        $detail = DetailRiwayatSales::with(['riwayat', 'barang'])->get();
        return view('detail_riwayat.index', compact('detail'));
    }

    public function create($riwayat_sales_id)
    {
        $riwayat = RiwayatSales::findOrFail($riwayat_sales_id); 
        $barang = Barang::all();
        return view('detail-riwayat-sales.create-detail', compact('riwayat', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'riwayat_sales_id' => 'required|exists:riwayat_sales,id',
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
            ->route('riwayat-sales.show', $request->riwayat_sales_id) 
            ->with('success', 'Data detail riwayat disimpan dan stok diperbarui!');
    }

    public function edit($id)
    {
        $detail = DetailRiwayatSales::with('barang', 'riwayat.sales')->findOrFail($id);
        $barang = Barang::all();
        return view('detail-riwayat-sales.edit-detail', compact('detail', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'riwayat_sales_id' => 'required|exists:riwayat_sales,id',
            'barang_id' => 'required|exists:barang,id',
            'qty_masuk' => 'required|integer|min:0',
            'qty_retur' => 'required|integer|min:0',
        ]);

        $detail = DetailRiwayatSales::findOrFail($id);
        $barang = Barang::findOrFail($detail->barang_id);

        $selisihMasuk = $request->qty_masuk - $detail->qty_masuk;
        $barang->stok += $selisihMasuk;

        $selisihRetur = $request->qty_retur - $detail->qty_retur;
        
        if ($selisihRetur > 0) {
            if ($barang->stok < $selisihRetur) {
                return back()->with('error', 'Stok tidak cukup untuk update retur!');
            }
            $barang->stok -= $selisihRetur;
        } else {
            $barang->stok -= $selisihRetur;
        }
        
        $barang->save();
        $detail->update($request->all());

        return redirect()
            ->route('riwayat-sales.show', $detail->riwayat_sales_id)
            ->with('success', 'Detail transaksi berhasil diperbarui dan stok disesuaikan!');
    }

    public function destroy($id)
    {
        // 1. Temukan detail yang akan dihapus
        $detail = DetailRiwayatSales::findOrFail($id);
        $riwayatSalesId = $detail->riwayat_sales_id;
        
        // Dapatkan kuantitas yang akan dibatalkan
        $qtyMasukDibatalkan = $detail->qty_masuk;
        $qtyReturDibatalkan = $detail->qty_retur;

        // 2. LOGIKA PEMBALIKAN STOK (Menggunakan Try-Catch untuk Barang yang Hilang)
        try {
            // Coba temukan barang (termasuk yang soft deleted)
            $barang = Barang::withTrashed()->findOrFail($detail->barang_id);

            // A. Balikkan QTY MASUK (yang menambah stok)
            $barang->stok -= $qtyMasukDibatalkan;

            // B. Balikkan QTY RETUR (yang mengurangi stok)
            $barang->stok += $qtyReturDibatalkan; 
            
            // 3. Simpan perubahan stok barang
            $barang->save();
            
        } catch (ModelNotFoundException $e) {
            // Jika Barang sudah dihapus permanen, kita tidak bisa membalikkan stok.
            // Biarkan logika ini dilewati dan lanjutkan penghapusan detail.
        } catch (\Exception $e) {
            // Tangani error umum lainnya jika terjadi
            return back()->with('error', 'Gagal memproses stok: ' . $e->getMessage());
        }

        // 4. Hapus detail riwayat sales
        // Karena kita sudah menangani pembalikan stok, kita bisa langsung menghapus detail.
        // Penghapusan ini TIDAK melanggar Foreign Key karena tabel detail_riwayat_sales
        // adalah tabel yang direferensikan, bukan yang mereferensi.
        $detail->delete(); 

        // 5. Redirect kembali
        return redirect()
            ->route('riwayat-sales.show', $riwayatSalesId)
            ->with('success', 'Detail barang berhasil dihapus dan stok telah disesuaikan (jika barang masih ada)!');
    }
}
