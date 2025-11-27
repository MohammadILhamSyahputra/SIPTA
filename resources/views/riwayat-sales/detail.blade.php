@extends('layouts.master') 

@section('title', 'Detail Transaksi Kunjungan') 

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Transaksi Kunjungan Sales</h1>
        <a href="{{ route('riwayat-sales.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Informasi Kunjungan</h6>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Sales:</strong> {{ $riwayat->sales->nama_sales }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success text-white">{{ strtoupper($riwayat->status) }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal Kunjungan:</strong> 
                        {{ \Carbon\Carbon::parse($riwayat->tanggal_kunjungan)->locale('id')->translatedFormat('l, d F Y H:i') }}
                    </p>
                    <p><strong>Dibuat Pada:</strong> 
                        {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Barang Transaksi</h6>
            <a href="{{ route('detail-riwayat-sales.create', ['riwayat_sales_id' => $riwayat->id]) }}" 
            class="btn btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang Baru
            </a>
        </div>
        <div class="card-body">
            @if ($riwayat->detail->isEmpty())
                <div class="alert alert-info">
                    Tidak ada data barang yang tercatat untuk transaksi ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>QTY Masuk </th>
                                <th>QTY Retur </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat->detail as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->barang->nama ?? 'Barang Dihapus' }}</td>
                                    <td>{{ number_format($detail->qty_masuk) }}</td>
                                    <td>{{ number_format($detail->qty_retur) }}</td>
                                    <td>
                                        <a href="{{ route('detail-riwayat-sales.edit', $detail->id) }}" class="btn btn-warning btn-sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total Item Transaksi</strong></td>
                                <td><strong>{{ number_format($riwayat->detail->sum('qty_masuk')) }}</strong></td>
                                <td><strong>{{ number_format($riwayat->detail->sum('qty_retur')) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
@endsection