<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSales;
use App\Models\Sales;
use Illuminate\Http\Request;
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
        RiwayatSales::findOrFail($id)->delete();

        return redirect()
            ->route('riwayat-sales.index')
            ->with('success', 'Riwayat berhasil dihapus!');
    }

    public function show($id)
    {
        $riwayat = RiwayatSales::with(['sales', 'detail.barang'])->findOrFail($id);
        
        return view('riwayat-sales.detail', compact('riwayat'));
    }
}
