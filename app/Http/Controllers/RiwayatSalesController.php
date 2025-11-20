<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSales;
use App\Models\Sales;
use Illuminate\Http\Request;

class RiwayatSalesController extends Controller
{
    public function index()
    {
        $riwayat = RiwayatSales::with(['sales', 'detail'])->get();
        return view('riwayat.index', compact('riwayat'));
    }

    public function create()
    {
        $sales = Sales::all();
        return view('riwayat.create', compact('sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|exists:sales,id',
            'status' => 'required',
            'tanggal_kunjungan' => 'required|date'
        ]);

        RiwayatSales::create($request->all());

        return redirect()
            ->route('riwayat.index')
            ->with('success', 'Riwayat sales berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $riwayat = RiwayatSales::findOrFail($id);
        $sales = Sales::all();

        return view('riwayat.edit', compact('riwayat', 'sales'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sales_id' => 'required|exists:sales,id',
            'status' => 'required',
            'tanggal_kunjungan' => 'required|date'
        ]);

        $riwayat = RiwayatSales::findOrFail($id);
        $riwayat->update($request->all());

        return redirect()
            ->route('riwayat.index')
            ->with('success', 'Riwayat sales berhasil diperbarui!');
    }

    public function destroy($id)
    {
        RiwayatSales::findOrFail($id)->delete();

        return redirect()
            ->route('riwayat.index')
            ->with('success', 'Riwayat berhasil dihapus!');
    }
}
