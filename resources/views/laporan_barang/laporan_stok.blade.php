@extends('layouts.master') 

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-1 text-success font-weight-bold">Laporan Barang Terlaris</h2>
            <p class="text-center text-muted mb-4">Hasil Penjualan Berdasarkan Filter Tanggal</p>

            <div class="card shadow p-4 border-0 mb-4">
                {{-- FORM FILTER TANGGAL --}}
                <form action="{{ route('laporan_barang.laporan_stok') }}" method="GET" class="mb-4">
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
                            <button type="submit" class="btn btn-success w-100">
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
                
                {{-- CHART BARANG TERLARIS --}}
                <div class="mb-4">
                    <div class="card shadow p-3">
                        <h5 class="text-center text-succsess font-weight-bold mb-3">Top 10 Barang Terlaris</h5>
                        <div style="height: 400px;"> <canvas id="barangTerlarisChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle" style="min-width: 900px;">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th class="p-2 align-middle">Kode Barang</th>
                                <th class="p-2 align-middle">Nama Barang</th>
                                <th class="p-2 align-middle">Harga Beli (Rp)</th>
                                <th class="p-2 align-middle">Harga Jual (Rp)</th>
                                <th class="p-2 text-danger">QTY Terjual (Unit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporanData as $data)
                            <tr>
                                <td class="font-weight-bold">{{ $data->kode_barang }}</td>
                                <td class="text-left">{{ $data->nama }}</td>
                                <td class="text-right">{{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($data->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-danger font-weight-bold">{{ $data->total_terjual }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-danger">Tidak ada data penjualan dalam periode yang dipilih.</td>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    const ctxBarangTerlaris = document.getElementById('barangTerlarisChart');
    if (ctxBarangTerlaris) {
        new Chart(ctxBarangTerlaris, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'QTY Terjual',
                    data: chartData,
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                indexAxis: 'y', 
                plugins: {
                    legend: {
                        display: false 
                    },
                    title: {
                        display: false,
                        text: 'Top 10 Barang Terlaris'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'QTY Terjual'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Nama Barang'
                        }
                    }
                }
            }
        });
    }
</script>
@endsection