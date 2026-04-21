<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Models\DetailPenjualan; // Ganti dari SaleDetail
use Illuminate\Support\Facades\DB;
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
     * Memfilter laporan berdasarkan rentang tanggal yang dikirim dari form.
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

    /**
     * Mengekspor laporan sebagai file PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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
     * Mengambil dan memproses data laporan (Omset & Keuntungan).
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getLaporanData($startDate, $endDate)
    {
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        $penjualanAggregates = DB::table('detail_transaksi as dt')
            ->select(
                'b.kode_barang',
                'b.nama as nama_barang',
                'k.nama_kategori as kategori',
                
                'b.harga_beli', 
                
                DB::raw('MAX(dt.harga_satuan) as harga_satuan'), 
                
                DB::raw('SUM(dt.qty) as total_qty'), 
                DB::raw('SUM(dt.qty * dt.harga_satuan) as total_omset'),
                DB::raw('SUM((dt.harga_satuan - b.harga_beli) * dt.qty) as total_untung')
            )
            ->join('transaksi as t', 'dt.id_transaksi', '=', 't.id')
            ->join('barang as b', 'dt.id_barang', '=', 'b.id')
            ->join('kategori as k', 'b.id_kategori', '=', 'k.id')
            ->whereBetween('t.tanggal', [$startDateTime, $endDateTime])
            
            ->groupBy('b.kode_barang', 'b.nama', 'b.harga_beli', 'k.nama_kategori')
            ->get();
            
        $tableData = $penjualanAggregates->map(function ($detail) {
            $totalOmset = $detail->total_omset;
            $totalUntung = $detail->total_untung;
            $marginPersentase = ($totalOmset > 0) ? ($totalUntung / $totalOmset) * 100 : 0;

            return [
                'kode_barang' => $detail->kode_barang,
                'nama_barang' => $detail->nama_barang,
                'kategori' => $detail->kategori,
                'jumlah' => $detail->total_qty, 
                'harga_satuan' => $detail->harga_satuan, 
                'total_penjualan' => $totalOmset, 
                'untung' => $totalUntung,
                'margin_persentase' => $marginPersentase,
            ];
        });

        $totalOmset = $tableData->sum('total_penjualan');
        $totalUntung = $tableData->sum('untung');
        
        $totalMarginPersentase = ($totalOmset > 0) ? ($totalUntung / $totalOmset) * 100 : 0;

        $startDateFormatted = Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y');
        $endDateFormatted = Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y');

        return [
            'tableData' => $tableData, 
            'totalOmset' => $totalOmset,
            'totalUntung' => $totalUntung,
            'totalMarginPersentase' => $totalMarginPersentase, 
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startDateFormatted' => $startDateFormatted,
            'endDateFormatted' => $endDateFormatted,
        ];
    }
}