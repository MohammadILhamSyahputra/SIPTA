<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::all();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sales' => 'required|max:255',
            'no_telp'    => 'required|max:50',
            'alamat'     => 'required|max:255',
        ]);

        Sales::create([
            'nama_sales' => $request->nama_sales,
            'no_telp'    => $request->no_telp,
            'alamat'     => $request->alamat,
        ]);

        return redirect()->route('sales.index')->with('success', 'Sales berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sales = Sales::findOrFail($id);
        return view('sales.edit', compact('sales'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sales' => 'required|max:255',
            'no_telp'    => 'required|max:50',
            'alamat'     => 'required|max:255',
        ]);

        $sales = Sales::findOrFail($id);
        $sales->update([
            'nama_sales' => $request->nama_sales,
            'no_telp'    => $request->no_telp,
            'alamat'     => $request->alamat,
        ]);

        return redirect()->route('sales.index')->with('success', 'Sales berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Sales::findOrFail($id)->delete();

        return redirect()->route('sales.index')->with('success', 'Sales berhasil dihapus!');
    }
}
