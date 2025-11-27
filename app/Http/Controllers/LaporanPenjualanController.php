<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPenjualan; // Ganti dari SaleDetail
use Carbon\Carbon;
use PDF;

class LaporanPenjualanController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        $data = $this->getLaporanData($startDate, $endDate);
        return view('laporan_penjualan', $data);
    }

    /**
     * Memfilter laporan berdasarkan rentang tanggal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->getLaporanData($startDate, $endDate);
        return view('laporan_penjualan', $data);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = $this->getLaporanData($startDate, $endDate);
        $pdf = PDF::loadView('pdf.laporan_penjualan_pdf', $data);
        $fileName = 'Laporan_Penjualan_' . $startDate . '_s_d_' . $endDate . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Mengambil dan memproses data laporan.
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\View\View
     */
    private function getLaporanData($startDate, $endDate)
    {
        $penjualanDetails = DetailPenjualan::with(['barang.kategori'])
            ->whereHas('transaksi', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get();

        $tableData = $penjualanDetails->map(function ($detail) {
            $hargaBeli = $detail->barang->harga_beli ?? 0;
            $untungPerItem = $detail->harga_satuan - $hargaBeli;
            $totalUntung = $untungPerItem * $detail->jumlah;

            return [
                'kode_barang' => $detail->barang->kode_barang,
                'nama_barang' => $detail->barang->nama_barang,
                'kategori' => $detail->barang->kategori->nama_kategori,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan,
                'total_penjualan' => $detail->jumlah * $detail->harga_satuan,
                'untung' => $totalUntung,
            ];
        });

        $totalOmset = $tableData->sum('total_penjualan');
        $totalUntung = $tableData->sum('untung');

        $startDateFormatted = Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y');
        $endDateFormatted = Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y');

        return [
            'tableData' => $tableData,
            'totalOmset' => $totalOmset,
            'totalUntung' => $totalUntung,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startDateFormatted' => $startDateFormatted,
            'endDateFormatted' => $endDateFormatted,
        ];
    }
}