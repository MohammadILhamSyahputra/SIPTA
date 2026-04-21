<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSales;
use App\Models\Sales;
use App\Models\DetailRiwayatSales;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class RiwayatSalesController extends Controller
{
    public function index()
    {
        $riwayat = RiwayatSales::with(['sales', 'detail'])->get();
        return view('riwayat-sales.index', compact('riwayat'));
    }

    public function create()
    {
        $sales = Sales::all();
        return view('riwayat-sales.create', compact('sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|exists:sales,id',
            'status' => 'required|in:belum datang,proses,sudah datang', 
            'tanggal_kunjungan' => [
                'nullable',
                'date',
                Rule::requiredIf($request->status === 'sudah datang'), 
            ],
        ]);
        $data = $request->all();
        if (empty($data['tanggal_kunjungan'])) {
            $data['tanggal_kunjungan'] = null;
        }
        
        RiwayatSales::create($data);

        return redirect()
            ->route('riwayat-sales.index')
            ->with('success', 'Riwayat sales berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $riwayat = RiwayatSales::findOrFail($id);
        $sales = Sales::all();

        return view('riwayat-sales.edit', compact('riwayat', 'sales'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sales_id' => 'required|exists:sales,id',
            'status' => 'required|in:belum datang,proses,sudah datang', 
            'tanggal_kunjungan' => [
                'nullable',
                'date',
                Rule::requiredIf($request->status === 'sudah datang'), 
            ],
        ]);

        $riwayat = RiwayatSales::findOrFail($id);
        $data = $request->all();
        if (empty($data['tanggal_kunjungan'])) {
            $data['tanggal_kunjungan'] = null;
        }

        $riwayat->update($data);

        return redirect()
            ->route('riwayat-sales.index')
            ->with('success', 'Riwayat sales berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $riwayat = RiwayatSales::with('detail')->findOrFail($id);

        // Loop melalui detail. Gunakan try-catch untuk menangani Barang yang sudah dihapus permanen.
        foreach ($riwayat->detail as $detail) {
            try {
                // Coba temukan barang (termasuk yang soft deleted)
                // Jika barang sudah di-hard delete (permanen), ini akan melempar exception
                $barang = Barang::withTrashed()->findOrFail($detail->barang_id);

                // --- LOGIKA PEMBALIKAN STOK ---
                // Membalikkan QTY MASUK (yang menambah stok)
                $barang->stok -= $detail->qty_masuk;

                // Membalikkan QTY RETUR (yang mengurangi stok)
                $barang->stok += $detail->qty_retur; 
                
                // Simpan perubahan stok
                $barang->save();
                
            } catch (ModelNotFoundException $e) {
                // Jika Barang sudah dihapus PERMANEN (hard delete), 
                // kita tidak bisa membalikkan stoknya. Kita hanya mencatat dan melanjutkan.
                // Anda bisa tambahkan logging di sini jika diperlukan.
            }
            
            // HAPUS DETAIL: Detail harus selalu dihapus terlepas dari status Barang
            $detail->delete(); 
        }

        // Hapus RiwayatSales (PARENT)
        $riwayat->delete();

        return redirect()
            ->route('riwayat-sales.index')
            ->with('success', 'Riwayat sales berhasil dihapus. Stok disesuaikan (jika barang masih ada).');
    }

    public function show($id)
    {
        $riwayat = RiwayatSales::with(['sales', 'detail.barang'])->findOrFail($id);
        
        return view('riwayat-sales.detail', compact('riwayat'));
    }
}
