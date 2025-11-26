@extends('layouts.master') 

@section('title', 'Tambah Detail Riwayat Sales Mandiri') 

@section('content')

    <div class="row">
        <div class="col-md-10 offset-md-1">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Tambah Detail Riwayat Sales</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Input Item Transaksi Baru</h6>
                </div>
                <div class="card-body">
                    
                    {{-- Form: Menargetkan DetailRiwayatSalesController@store --}}
                    <form action="{{ route('detail-riwayat-sales.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                
                                {{-- 1. Pilih Riwayat Sales (PENTING untuk mandiri) --}}
                                <div class="mb-3">
                                    <label for="riwayat_sales_id" class="form-label">Pilih Riwayat Kunjungan</label>
                                    <select 
                                        class="form-select @error('riwayat_sales_id') is-invalid @enderror" 
                                        id="riwayat_sales_id" 
                                        name="riwayat_sales_id" 
                                        required>
                                        
                                        <option value="">Pilih Riwayat (Tanggal Kunjungan & Sales)</option>
                                        {{-- $riwayat harus diteruskan dari controller --}}
                                        @foreach ($riwayat as $r)
                                            <option 
                                                value="{{ $r->id }}"
                                                {{ (old('riwayat_sales_id') == $r->id) ? 'selected' : '' }}>
                                                {{ $r->tanggal_kunjungan ? \Carbon\Carbon::parse($r->tanggal_kunjungan)->format('d/m/Y') : 'Jadwal Baru' }} - ({{ $r->sales->nama_sales ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('riwayat_sales_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- 2. Barang (Dropdown/Combobox) --}}
                                <div class="mb-3">
                                    <label for="barang_id_create" class="form-label">Nama Barang</label>
                                    <select 
                                        class="form-select @error('barang_id') is-invalid @enderror" 
                                        id="barang_id_create" 
                                        name="barang_id" 
                                        required>
                                        
                                        <option value="">Pilih Barang</option>
                                        {{-- $barang harus diteruskan dari controller --}}
                                        @foreach ($barang as $b)
                                            <option 
                                                value="{{ $b->id }}"
                                                {{ (old('barang_id') == $b->id) ? 'selected' : '' }}>
                                                {{ $b->nama }} (Stok: {{ $b->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('barang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-6">
                                
                                {{-- 3. QTY Masuk (Penjualan/Stok Keluar) --}}
                                <div class="mb-3">
                                    <label for="qty_masuk_create" class="form-label">QTY Masuk (Penjualan Sales)</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('qty_masuk') is-invalid @enderror" 
                                        id="qty_masuk_create" 
                                        name="qty_masuk" 
                                        min="0" 
                                        value="{{ old('qty_masuk', 0) }}"
                                        required>
                                    <small class="form-text text-muted">Jumlah barang yang dikeluarkan dari gudang. (Mengurangi Stok Gudang)</small>
                                    @error('qty_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 4. QTY Retur (Pengembalian Stok) --}}
                                <div class="mb-3">
                                    <label for="qty_retur_create" class="form-label">QTY Retur (Pengembalian Sales)</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('qty_retur') is-invalid @enderror" 
                                        id="qty_retur_create" 
                                        name="qty_retur" 
                                        min="0" 
                                        value="{{ old('qty_retur', 0) }}"
                                        required>
                                    <small class="form-text text-muted">Jumlah barang yang dikembalikan ke gudang. (Menambah Stok Gudang)</small>
                                    @error('qty_retur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between pt-3 border-top mt-4">
                            <a href="{{ route('riwayat-sales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Item Transaksi
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
    
@endsection