@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan</h1>

    <!-- Card untuk Filter -->
    <div class="card-body">
        <form action="{{ route('laporan.penjualan.filter') }}" method="POST" id="filterForm">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? old('start_date') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? old('end_date') }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">Tampilkan Laporan</button>
                    <a href="#" id="exportPdfLink" class="btn btn-danger">Export to PDF</a>
                </div>
            </div>
        </form>
    </div>


    <!-- Card untuk Tabel Laporan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Laporan Penjualan</h6>
            <span class="text-muted">Periode: {{ $startDateFormatted ?? '' }} - {{ $endDateFormatted ?? '' }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah Terjual</th>
                            <th>Harga Satuan</th>
                            <th>Total Penjualan</th>
                            <th>Untung</th>
                            <th>Total Final</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total Omset:</th>
                            <th>{{ 'Rp ' . number_format($totalOmset, 0, ',', '.') }}</th>
                            <th>{{ 'Rp ' . number_format($totalUntung, 0, ',', '.') }}</th>
                            <th>{{ 'Rp ' . number_format($totalOmset, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($tableData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['kode_barang'] }}</td>
                            <td>{{ $data['nama_barang'] }}</td>
                            <td>{{ $data['kategori'] }}</td>
                            <td>{{ $data['jumlah'] }}</td>
                            <td>{{ 'Rp ' . number_format($data['harga_satuan'], 0, ',', '.') }}</td>
                            <td>{{ 'Rp ' . number_format($data['total_penjualan'], 0, ',', '.') }}</td>
                            <td>{{ 'Rp ' . number_format($data['untung'], 0, ',', '.') }}</td>
                            <td>{{ 'Rp ' . number_format($data['total_penjualan'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data penjualan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const exportPdfLink = document.getElementById('exportPdfLink');

        function updateExportPdfLink() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (startDate && endDate) {
                const exportUrl = `{{ route('laporan.penjualan.exportPdf') }}?start_date=${startDate}&end_date=${endDate}`;
                exportPdfLink.setAttribute('href', exportUrl);
            } else {
                exportPdfLink.setAttribute('href', '#');
            }
        }

        document.getElementById('start_date').addEventListener('change', updateExportPdfLink);
        document.getElementById('end_date').addEventListener('change', updateExportPdfLink);

        updateExportPdfLink();
    });
</script>
@endsection
