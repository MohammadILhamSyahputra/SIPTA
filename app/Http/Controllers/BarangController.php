<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Sales;
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
            'nama' => 'required',
            'id_kategori' => 'required|exists:kategori,id',
            'id_sales' => 'required|exists:sales,id',
            'stok' => 'required|integer',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
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
}
