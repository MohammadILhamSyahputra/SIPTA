@extends('layouts.master') {{-- Menggunakan layout 'layouts.master' sesuai permintaan Anda --}}

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-1 text-primary font-weight-bold">Laporan Stok Barang</h2>
            <p class="text-center text-muted mb-4">Pergerakan Stok Berdasarkan Filter Tanggal</p>

            <div class="card shadow p-4 border-0 mb-4">
                {{-- FORM FILTER TANGGAL --}}
                <form action="{{ route('laporan barang.laporan_stok') }}" method="GET" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label for="tgl_mulai" class="form-label font-weight-bold">Tanggal Mulai:</label>
                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" 
                                value="{{ $tglMulai ?? date('Y-m-01') }}" required>
                        </div>
                        <div class="col-md-5">
                            <label for="tgl_akhir" class="form-label font-weight-bold">Tanggal Akhir:</label>
                            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" 
                                value="{{ $tglAkhir ?? date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter mr-2"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>
                {{-- AKHIR FORM FILTER TANGGAL --}}

                <div class="card-header bg-warning text-dark font-weight-bold p-3 rounded-top mb-3">
                    <i class="fas fa-chart-bar mr-2"></i> Hasil Laporan
                    @if(isset($tglMulai) && isset($tglAkhir))
                        <span class="float-right badge badge-primary p-2">
                            Periode: {{ date('d M Y', strtotime($tglMulai)) }} s/d {{ date('d M Y', strtotime($tglAkhir)) }}
                        </span>
                    @endif
                </div>
                
                <div class="table-responsive">
                    {{-- Perlu 2 baris header karena ada gabungan kolom (Stok Awal, Akhir, Masuk, Keluar) --}}
                    <table class="table table-bordered table-hover text-center align-middle" style="min-width: 900px;">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th rowspan="2" class="p-2 align-middle">Kode Barang</th>
                                <th rowspan="2" class="p-2 align-middle">Nama Barang</th>
                                <th rowspan="2" class="p-2 align-middle">Harga Beli (Rp)</th>
                                <th rowspan="2" class="p-2 align-middle">Harga Jual (Rp)</th>
                                <th rowspan="2" class="p-2 align-middle">Stok Awal</th> 
                                <th colspan="2" class="p-2">Pergerakan Periode</th>
                                <th rowspan="2" class="p-2 align-middle text-primary">Stok Akhir</th> 
                            </tr>
                            <tr>
                                <th class="p-2 text-success">Stok Masuk</th>
                                <th class="p-2 text-danger">Stok Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop melalui data laporan yang sudah dihitung di Controller (variabel $laporanData) --}}
                            @forelse ($laporanData as $data)
                            <tr>
                                <td class="font-weight-bold">{{ $data->kode_barang }}</td>
                                <td class="text-left">{{ $data->nama }}</td>
                                
                                <td class="text-right">{{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($data->harga_jual, 0, ',', '.') }}</td>
                                
                                <td>{{ $data->stok_awal }}</td>
                                
                                <td class="text-success font-weight-bold">{{ $data->total_masuk }}</td>
                                <td class="text-danger font-weight-bold">{{ $data->total_terjual }}</td>
                                
                                <td class="font-weight-bold text-primary">
                                    {{ $data->stok_akhir }} 
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">Tidak ada data barang atau pergerakan dalam periode yang dipilih.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection