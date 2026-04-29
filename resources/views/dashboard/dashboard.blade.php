@extends('layouts.master')

@section('title', 'Dashboard Toko Ardiyana')

@section('content')

{{-- Notifikasi Stok Menipis (Hanya Muncul untuk Owner) --}}
@if(auth()->user()->userType == 'owner' && count($stok_menipis) > 0)
<div class="row">
    <div class="col-12 mb-4">
        <div class="alert alert-danger shadow-sm border-left-danger fade show" role="alert" style="border-left: 0.25rem solid #e74a3b !important;">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Peringatan Stok Menipis
                    </div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        Ada {{ count($stok_menipis) }} item barang dengan stok kurang dari 10 unit.
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-danger btn-sm shadow-sm" data-bs-toggle="collapse" data-bs-target="#collapseStok">
                        <i class="fas fa-list fa-sm text-white-50"></i> Lihat Detail
                    </button>
                </div>
            </div>

            <div class="collapse mt-3" id="collapseStok">
                <div class="card card-body p-0 border-0 bg-transparent">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-white mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stok_menipis as $index => $item)
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">{{ $item->nama }}</td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-danger px-3 text-dark">{{ $item->stok }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="text-danger small font-weight-bold">Perlu Restok</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Baris Kotak Statistik --}}
<div class="row">
    {{-- 1. Kotak Jumlah Barang --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="background: linear-gradient(135deg, #004d40 0%, #00796b 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Barang</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $total_barang }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Kotak Jumlah Kategori --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="background: linear-gradient(135deg, #004d40 0%, #00796b 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Kategori</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $total_kategori }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Kotak Jumlah Sales --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="background: linear-gradient(135deg, #004d40 0%, #00796b 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Sales</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $total_sales }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik/Chart --}}
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-black">Distribusi Stok per Item Barang</h6>
            </div>
            <div class="card-body">
                <p class="text-center h1 font-weight-bold">{{ array_sum($item_stok_data) }} Unit</p>
                <div style="height: 350px;">
                    <canvas id="itemStokChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-black">Jumlah Item Barang per Kategori</h6>
            </div>
            <div class="card-body">
                <div style="height: 445px;">
                    <canvas id="barangPerKategoriChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const kategoriLabels = @json($kategori_labels);
    const kategoriData = @json($kategori_data);
    const itemLabels = @json($item_labels);
    const itemStokData = @json($item_stok_data);
    
    function getRandomColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            colors.push(`rgba(${r},${g},${b}, 0.7)`);
        }
        return colors;
    }
    
    const ctxStok = document.getElementById('itemStokChart');
    if (ctxStok) {
        new Chart(ctxStok, {
            type: 'doughnut',
            data: {
                labels: itemLabels, 
                datasets: [{
                    data: itemStokData, 
                    backgroundColor: getRandomColors(itemLabels.length),
                    hoverOffset: 4
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    const ctxKategori = document.getElementById('barangPerKategoriChart');
    if (ctxKategori) {
        new Chart(ctxKategori, {
            type: 'bar',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    label: 'Jumlah Item',
                    data: kategoriData,
                    backgroundColor: [
                        'rgba(142, 246, 255, 1)',
                        'rgba(54, 163, 235, 1)',
                        'rgba(255, 207, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 160, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
</script>
@endsection