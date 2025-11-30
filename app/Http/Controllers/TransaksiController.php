<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::all();
        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_harga' => 'required|integer',
            'total_bayar' => 'required|integer',
            'kembalian' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        Transaksi::create($request->all());

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_harga' => 'required|integer',
            'total_bayar' => 'required|integer',
            'kembalian' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($request->all());

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Transaksi::findOrFail($id)->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }

    public function today()
    {
        $transaksi = Transaksi::with(['details.barang'])
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('tanggal', 'desc')
            ->get();

        $result = $transaksi->map(function($t) {
            return [
                'id' => $t->id,
                'date' => $t->tanggal->format('d/m/Y H:i:s'),
                'total' => $t->total_harga,
                'total_bayar' => $t->total_bayar,
                'kembalian' => $t->kembalian,
                'items' => $t->details->map(function($detail) {
                    return [
                        'id' => $detail->id,
                        'nama' => $detail->barang->nama,
                        'qty' => $detail->qty,
                        'harga' => $detail->harga_satuan,
                        'barang_id' => $detail->id_barang
                    ];
                })
            ];
        });

        return response()->json($result);
    }

    public function storeKasir(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'total_bayar' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $totalBayar = $request->total_bayar ?? $request->total;
            $kembalian = $totalBayar - $request->total;
            
            $transaksi = Transaksi::create([
                'total_harga' => $request->total,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian,
                'tanggal' => now()
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga'];
                
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_barang' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $subtotal
                ]);
                
                
                $barang = Barang::find($item['barang_id']);
                $barang->stok -= $item['qty'];
                $barang->save();
            }

            DB::commit();

            $transaksi->load(['details.barang']);
            
            return response()->json([
                'id' => $transaksi->id,
                'date' => $transaksi->tanggal->format('d/m/Y H:i:s'),
                'total' => $transaksi->total_harga,
                'total_bayar' => $transaksi->total_bayar,
                'kembalian' => $transaksi->kembalian,
                'items' => $transaksi->details->map(function($detail) {
                    return [
                        'id' => $detail->id,
                        'nama' => $detail->barang->nama,
                        'qty' => $detail->qty,
                        'harga' => $detail->harga_satuan
                    ];
                })
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function updateKasir(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.barang_id' => 'nullable|exists:barang,id',
            'items.*.nama' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            foreach ($transaksi->details as $oldDetail) {
                $barang = Barang::find($oldDetail->id_barang);
                $barang->stok += $oldDetail->qty;
                $barang->save();
            }

            $transaksi->details()->delete();
            
            $kembalian = $transaksi->total_bayar - $request->total;
            
            $transaksi->update([
                'total_harga' => $request->total,
                'kembalian' => $kembalian
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga'];
                
                $barang = null;
                if (isset($item['barang_id'])) {
                    $barang = Barang::find($item['barang_id']);
                } else {
                    $barang = Barang::where('nama', $item['nama'])->first();
                }
                
                if ($barang) {
                    DetailTransaksi::create([
                        'id_transaksi' => $transaksi->id,
                        'id_barang' => $barang->id,
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'subtotal' => $subtotal
                    ]);
                    
                    $barang->stok -= $item['qty'];
                    $barang->save();
                }
            }

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil diupdate']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal mengupdate transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function destroyKasir($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            foreach ($transaksi->details as $detail) {
                $barang = Barang::find($detail->id_barang);
                $barang->stok += $detail->qty;
                $barang->save();
            }
            
            $transaksi->delete();
            
            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()], 500);
        }
    }
}
