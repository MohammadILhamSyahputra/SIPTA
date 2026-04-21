@extends('layouts.master') 

@section('title', 'Tambah Item Transaksi Sales') 

@section('content')

    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Form Tambah Detail Riwayat Sales</h2>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-success text-white">
            <h6 class="m-0 font-weight-bold">Riwayat Kunjungan</h6>
        </div>
        <div class="card-body">
            <input type="hidden" name="riwayat_sales_id" value="{{ $riwayat->id }}">
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Sales:</strong> {{ $riwayat->sales->nama_sales }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success text-white">{{ strtoupper($riwayat->status) }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal Kunjungan:</strong> 
                        {{ \Carbon\Carbon::parse($riwayat->tanggal_kunjungan)->locale('id')->translatedFormat('l, d F Y H:i') }}
                    </p>
                    <p><strong>Dibuat Pada:</strong> 
                        {{ \Carbon\Carbon::parse($riwayat->created_at)->locale('id')->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-success text-white">
            <h6 class="m-0 font-weight-bold">Input Item Transaksi Baru</h6>
        </div>
        
        <form action="{{ route('detail-riwayat-sales.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="riwayat_sales_id" value="{{ $riwayat->id }}">
            
            <div class="card-body">
                <div class="row">
                    
                    {{-- Kolom Kiri: Nama Barang --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Nama Barang</label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id }}" {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                        
                                        {{ $item->nama }} 
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan: QTY Masuk & QTY Retur --}}
                    <div class="col-md-6">
                        
                        <div class="mb-3">
                            <label for="qty_masuk" class="form-label">QTY Masuk</label>
                            <input type="number" class="form-control @error('qty_masuk') is-invalid @enderror" id="qty_masuk" name="qty_masuk" value="{{ old('qty_masuk', 0) }}" min="0" required>
                            @error('qty_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- <small class="form-text text-muted">Jumlah barang yang **dikembalikan** ke gudang.</small> --}}
                        </div>
                        
                        <div class="mb-3">
                            <label for="qty_retur" class="form-label">QTY Retur</label>
                            <input type="number" class="form-control @error('qty_retur') is-invalid @enderror" id="qty_retur" name="qty_retur" value="{{ old('qty_retur', 0) }}" min="0" required>
                            @error('qty_retur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- <small class="form-text text-muted">Jumlah barang yang **dikeluarkan** dari gudang (Misal: barang yang terjual oleh sales).</small> --}}
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('riwayat-sales.show', $riwayat->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Item Transaksi
                </button>
            </div>
        </form>
    </div>
    
@endsection