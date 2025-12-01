@extends('layouts.master')

@section('title', 'Dashboard Toko Ardiyana')

@section('content')
<div class="row">

    {{-- 1. Kotak Jumlah Barang (Biru) --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="background: linear-gradient(135deg, #004d40 0%, #00796b 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Jumlah Barang
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ $total_barang }}</div>
                        {{-- <small><a href="{{ route('barang.index') }}" class="text-white">Lihat Data Barang &rarr;</a></small> --}}
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Kotak Jumlah Kategori (Hijau) --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="background: linear-gradient(135deg, #004d40 0%, #00796b 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Jumlah Kategori
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ $total_kategori }}</div>
                        {{-- <small><a href="{{ route('kategori.index') }}" class="text-white">Lihat Data Kategori &rarr;</a></small> --}}
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Kotak Jumlah Sales (Kuning) --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="background: linear-gradient(135deg, #004d40 0%, #00796b 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Jumlah Sales
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ $total_sales }}</div>
                        {{-- <small><a href="{{ route('sales.index') }}" class="text-white">Lihat Data Sales &rarr;</a></small> --}}
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x"></i> {{-- Icon Sales --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik/Chart --}}
<div class="row">

    {{-- Chart Kiri: Total Stok Barang Saat Ini (Gunakan Bar/Doughnut Chart) --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-black">Distribusi Stok per Item Barang</h6>
            </div>
            <div class="card-body">
                <p class="text-center h1 font-weight-bold">{{ array_sum($item_stok_data) }} Unit</p>
                <p class="text-center text-muted">Proporsi stok setiap barang yang ada di toko saat ini.</p>
                <div style="height: 350px;">
                    <canvas id="itemStokChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Kanan: Jumlah Barang per Kategori (Gunakan Pie/Bar Chart) --}}
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
                    backgroundColor: getRandomColors(itemLabels.length), // Dikembalikan ke warna acak
                    hoverOffset: 4
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: false,
                    }
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
                    label: 'Jumlah Barang',
                    data: kategoriData,
                    backgroundColor: [
                        'rgba(142, 246, 255, 1)',
                        'rgba(54, 163, 235, 1)',
                        'rgba(255, 207, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 160, 64, 1)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 133, 0.53)',
                        'rgba(54, 163, 235, 0.63)',
                        'rgba(255, 207, 86, 0.57)',
                        'rgba(75, 192, 192, 0.61)',
                        'rgba(153, 102, 255, 0.61)',
                        'rgba(255, 160, 64, 0.56)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endsection