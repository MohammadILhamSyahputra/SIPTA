<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $detail = DetailTransaksi::with(['barang', 'transaksi'])->get();
        return view('detail_transaksi.index', compact('detail'));
    }

    public function create()
    {
        $barang = Barang::all();
        $transaksi = Transaksi::all();
        return view('detail_transaksi.create', compact('barang', 'transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id',
            'id_barang' => 'required|exists:barang,id',
            'qty' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:1',
            'subtotal' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($request->id_barang);
        if ($barang->stok < $request->qty) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi!');
        }
        $barang->stok -= $request->qty;
        $barang->save();
        DetailTransaksi::create($request->all());
        return redirect()
            ->route('detail-transaksi.index')
            ->with('success', 'Detail transaksi berhasil ditambahkan dan stok berkurang!');
    }

    public function edit($id)
    {
        $detail = DetailTransaksi::findOrFail($id);
        $barang = Barang::all();
        $transaksi = Transaksi::all();

        return view('detail_transaksi.edit', compact('detail', 'barang', 'transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id',
            'id_barang' => 'required|exists:barang,id',
            'qty' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:1',
            'subtotal' => 'required|integer|min:1',
        ]);

        $detail = DetailTransaksi::findOrFail($id);
        $barang = Barang::findOrFail($request->id_barang);
        $selisih = $request->qty - $detail->qty;
        if ($selisih > 0 && $barang->stok < $selisih) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk update!');
        }
        $barang->stok -= $selisih;
        $barang->save();
        $detail->update($request->all());
        return redirect()
            ->route('detail-transaksi.index')
            ->with('success', 'Detail transaksi berhasil diperbarui dan stok disesuaikan!');
    }

    public function destroy($id)
    {
        $detail = DetailTransaksi::findOrFail($id);
        $barang = Barang::findOrFail($detail->id_barang);
        $barang->stok += $detail->qty;
        $barang->save();
        $detail->delete();

        return redirect()
            ->route('detail-transaksi.index')
            ->with('success', 'Detail transaksi dihapus dan stok dikembalikan!');
    }
}
