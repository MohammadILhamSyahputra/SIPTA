<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function index()
    {
        \Log::info('Kasir index accessed by user: ' . \Auth::user()?->id);
        return view('kasir.utama');
    }

    public function test()
    {
        return response()->json(['status' => 'ok']);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $barang = Barang::where('nama', 'like', "%{$query}%")
            ->orWhere('kode_barang', 'like', "%{$query}%")
            ->select('id', 'kode_barang', 'nama', 'harga_jual', 'stok')
            ->get();

        return response()->json($barang);
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Kasir Store Request:', $request->all());
            
            // Validasi input
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id_barang' => 'required|numeric',
                'items.*.qty' => 'required|numeric|min:1',
                'items.*.harga_satuan' => 'required|numeric|min:1',
                'items.*.subtotal' => 'required|numeric|min:1',
                'total_harga' => 'required|numeric|min:1',
                'total_bayar' => 'required|numeric|min:1',
                'kembalian' => 'required|numeric|min:0',
            ]);

            \Log::info('Validated data:', $validated);

            // Validasi barang ada di database
            foreach ($validated['items'] as $item) {
                $barang = Barang::find($item['id_barang']);
                if (!$barang) {
                    \Log::warning('Barang not found:', ['id' => $item['id_barang']]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Barang dengan ID ' . $item['id_barang'] . ' tidak ditemukan',
                    ], 422);
                }

                // Check stok
                if ($barang->stok < $item['qty']) {
                    \Log::warning('Stok not enough:', [
                        'barang' => $barang->nama,
                        'available' => $barang->stok,
                        'requested' => $item['qty']
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok ' . $barang->nama . ' tidak cukup. Stok tersedia: ' . $barang->stok,
                    ], 422);
                }
            }

            \Log::info('Barang validation passed');

            // Create transaction
            $transaksi = Transaksi::create([
                'total_harga' => intval($validated['total_harga']),
                'total_bayar' => intval($validated['total_bayar']),
                'kembalian' => intval($validated['kembalian']),
                'tanggal' => now(),
            ]);

            \Log::info('Transaction created:', ['id' => $transaksi->id]);

            // Create detail transactions and update stock
            foreach ($validated['items'] as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_barang' => intval($item['id_barang']),
                    'qty' => intval($item['qty']),
                    'harga_satuan' => intval($item['harga_satuan']),
                    'subtotal' => intval($item['subtotal']),
                ]);

                \Log::info('Detail transaction created for barang:', ['id_barang' => $item['id_barang']]);

                // Update stock
                $barang = Barang::find($item['id_barang']);
                if ($barang) {
                    $barang->decrement('stok', intval($item['qty']));
                    \Log::info('Stock updated:', ['barang' => $barang->nama, 'qty' => $item['qty']]);
                }
            }

            \Log::info('Transaction completed successfully');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'transaksi_id' => $transaksi->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store error:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function history()
    {
        $today = Carbon::now()->toDateString();
        $transaksi = Transaksi::whereDate('tanggal', $today)
            ->with('detail.barang')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('kasir.history', compact('transaksi'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::with('detail')->findOrFail($id);

        // Restore stock
        foreach ($transaksi->detail as $detail) {
            $barang = Barang::find($detail->id_barang);
            if ($barang) {
                $barang->update(['stok' => $barang->stok + $detail->qty]);
            }
        }

        // Delete detail transactions first
        DetailTransaksi::where('id_transaksi', $id)->delete();
        
        // Delete transaction
        $transaksi->delete();

        return redirect()
            ->route('kasir.history')
            ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan!');
    }
}
