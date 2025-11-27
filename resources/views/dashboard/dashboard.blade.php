@extends('layouts.master') {{-- Pastikan nama layout file-nya benar --}}

@section('title', 'Dashboard Toko Ardiyana')

@section('content')
<div class="row">

    {{-- 1. Kotak Jumlah Barang --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="background-color: #3b82f6; color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Jumlah Barang
                        </div>
                        {{-- Ganti '123' dengan variabel aktual dari controller Anda, contoh: $total_barang --}}
                        <div class="h5 mb-0 font-weight-bold">123</div>
                        <small><a href="{{ route('barang.index') }}" class="text-white">Lihat Data Barang &rarr;</a></small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box-open fa-2x"></i> {{-- Icon barang --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Kotak Jumlah Kategori --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="background-color: #10b981; color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Jumlah Kategori
                        </div>
                        {{-- Ganti '15' dengan variabel aktual dari controller Anda, contoh: $total_kategori --}}
                        <div class="h5 mb-0 font-weight-bold">15</div>
                        <small><a href="{{ route('kategori.index') }}" class="text-white">Lihat Data Kategori &rarr;</a></small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x"></i> {{-- Icon kategori --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Kotak Jumlah Sales/Transaksi --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="background-color: #f59e0b; color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Transaksi (Bulan Ini)
                        </div>
                        {{-- Ganti '24' dengan variabel aktual dari controller Anda, contoh: $total_sales_bulan_ini --}}
                        <div class="h5 mb-0 font-weight-bold">24</div>
                        <small><a href="{{ route('sales.index') }}" class="text-white">Lihat Riwayat Sales &rarr;</a></small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x"></i> {{-- Icon sales --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Kotak Pendapatan Tahunan (Opsional, Meniru Gambar Pertama) --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" style="background-color: #ef4444; color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Pendapatan Tahunan
                        </div>
                        {{-- Ganti '$215,000' dengan variabel aktual --}}
                        <div class="h5 mb-0 font-weight-bold">Rp215.000.000</div>
                        <small><a href="#" class="text-white">Lihat Laporan Keuangan &rarr;</a></small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Tambahkan bagian untuk chart/grafik di bawahnya (seperti pada gambar pertama) --}}
<div class="row">
    {{-- Contoh Chart/Grafik 1: Earnings Breakdown --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Breakdown</h6>
            </div>
            <div class="card-body">
                {{-- Di sini tempat Anda menaruh elemen <canvas> untuk chart --}}
                <p>Area untuk grafik kenaikan pendapatan (seperti garis biru di gambar pertama).</p>
                <div style="height: 300px;"></div>
            </div>
        </div>
    </div>

    {{-- Contoh Chart/Grafik 2: Monthly Revenue --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue</h6>
            </div>
            <div class="card-body">
                {{-- Di sini tempat Anda menaruh elemen <canvas> untuk chart --}}
                <p>Area untuk grafik batang pendapatan bulanan (seperti grafik batang di gambar pertama).</p>
                <div style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

@endsection
