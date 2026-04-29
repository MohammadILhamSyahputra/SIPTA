@extends('layouts.master')
@section('title', 'Laporan Penjualan') 
@section('styles')
<style>
    /* Desain Input Tanggal Kustom dd/mm/yyyy */
    .date-input-custom {
        position: relative;
        color: transparent !important;
    }

    .date-input-custom::before {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        content: attr(data-date);
        color: #495057;
        pointer-events: none;
    }

    .date-input-custom::-webkit-calendar-picker-indicator {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 1;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.penjualan.filter') }}" method="POST" id="filterForm">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date" class="font-weight-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control date-input-custom" 
                            value="{{ $startDate ?? old('start_date') }}" 
                            data-date="{{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'dd/mm/yyyy' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="font-weight-bold">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control date-input-custom" 
                            value="{{ $endDate ?? old('end_date') }}" 
                            data-date="{{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'dd/mm/yyyy' }}" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success mr-2">Tampilkan Laporan</button>
                        <span class="mx-1"></span>
                        <a href="#" id="exportPdfLink" class="btn btn-danger">Export to PDF</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-light">
            <h6 class="m-0 font-weight-bold text-success">Data Laporan Penjualan</h6>
            <span class="text-muted small">Periode: {{ $startDateFormatted ?? '-' }} - {{ $endDateFormatted ?? '-' }}</span>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Periksa kesalahan input tanggal.</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jml Terjual</th>
                            <th>Harga Satuan</th>
                            <th>Total Penjualan</th>
                            <th>Untung</th>
                            <th>Margin (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tableData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['kode_barang'] }}</td>
                            <td class="text-left">{{ $data['nama_barang'] }}</td>
                            <td>{{ $data['kategori'] }}</td>
                            <td>{{ $data['jumlah'] }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($data['harga_satuan'], 0, ',', '.') }}</td>
                            <td class="text-right font-weight-bold text-success">{{ 'Rp ' . number_format($data['total_penjualan'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($data['untung'], 0, ',', '.') }}</td>
                            
                            {{-- KOLOM MARGIN PERSENTASE BARU --}}
                            <td class="text-right font-weight-bold">
                                {{ number_format($data['margin_persentase'], 2, ',', '.') . ' %' }} 
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data penjualan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <th colspan="6" class="text-right">Total Keseluruhan:</th>
                            
                            {{-- Total Omset --}}
                            <th class="text-right">{{ 'Rp ' . number_format($totalOmset, 0, ',', '.') }}</th>
                            
                            {{-- Total Untung --}}
                            <th class="text-right">{{ 'Rp ' . number_format($totalUntung, 0, ',', '.') }}</th>
                            
                            {{-- Total Margin Persentase --}}
                            <th class="text-right font-weight-bold text-success">
                                {{ number_format($totalMarginPersentase, 2, ',', '.') . ' %' }} 
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const exportPdfLink = document.getElementById('exportPdfLink');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Fungsi Update Format Visual dd/mm/yyyy
        function updateVisualFormat(input) {
            let val = input.value;
            if (val) {
                let date = new Date(val);
                let d = ("0" + date.getDate()).slice(-2);
                let m = ("0" + (date.getMonth() + 1)).slice(-2);
                let y = date.getFullYear();
                input.setAttribute('data-date', d + '/' + m + '/' + y);
            } else {
                input.setAttribute('data-date', 'dd/mm/yyyy');
            }
        }

        function updateExportPdfLink() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            // Jalankan update visual setiap ada perubahan
            updateVisualFormat(startDateInput);
            updateVisualFormat(endDateInput);

            if (startDate && endDate) {
                const exportUrl = `{{ route('laporan.penjualan.exportPdf') }}?start_date=${startDate}&end_date=${endDate}`;
                exportPdfLink.setAttribute('href', exportUrl);
                exportPdfLink.classList.remove('disabled');
            } else {
                exportPdfLink.setAttribute('href', '#');
                exportPdfLink.classList.add('disabled');
            }
        }

        startDateInput.addEventListener('change', updateExportPdfLink);
        endDateInput.addEventListener('change', updateExportPdfLink);

        // Jalankan saat pertama kali load untuk nilai default
        updateExportPdfLink();
    });
</script>
@endsection